<?php
/**
 * Created by PhpStorm.
 * User: limeng
 * Date: 2017/11/3 13:57
 */
require getenv('BASEPATH') . '/image_search/ImageSearch.php';

class SimilarPatterns_model extends POP_Model
{
    private $ssf_signature_len = 32;
    private $ssf_client_version = '1';
    private $strPol = 'abcdefghijklmnopqrstuvwxyz0123456789';
    private $secret_key = 's8ygcYsyiTKC0Ihcl1blVZpfntg';
    private $access_key_id = 'popop';
    private $ssf_client_api = 'https://api.productai.cn/search/4tzj7h2l/';
    private $fabric_match_table = ['product_graphicitem'];

    private $memcachePref; //memcache前缀

    function __construct()
    {
        $this->memcachePref = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . "_Cloud_SimilarPatterns_BD_"; //memcache前缀
    }

    /*------------------------------------------------------------------------------------------------------------------
     * 获取显示图列表
     *----------------------------------------------------------------------------------------------------------------*/
    public function getFabricList($imgUrl, $cat, $offset = 0, $length = 0)
    {
        $postData['url'] = $imgUrl;
        $postData['count'] = 300;
        $this->load->driver('cache');
        $memKey = $this->memcachePref . 'FabricList_' . md5($imgUrl);
        //$result = $this->cache->memcached->get($memKey);
        if (empty($result) || $this->input->get('refresh')) {
            $_result = $this->getInterfaceData($postData);
            //调用接口
            $result = $this->dealInterfaceData($_result["data"]); //处理接口数据并分组
            $this->cache->memcached->save($memKey, $result, 3600);
        }
        //如果有取分类
        if (!empty($result['list']) && $cat) {
            $_data = [];
            foreach ($result['list'] as $key => $val) {
                if (in_array($cat, $val['search'])) {
                    $_data[$key] = $val;
                }
            }
            $result['list'] = $_data;
        }
        //分页($length为0时不分页)
        $_list = $length > 0 ? array_slice($result['list'], $offset, $length) : $result['list'];
        $result['list'] = array_values($_list);
        return $result;
    }


    //**********************************************相似图案接口[start]***********************************************//
    //百度图搜图，并且转化成老数据形式
    private function getMatchImageFromBaidu($image_url, $limit = 300)
    {
        $begin_time = microtime(true);
        $res = ImageSearch::getInstance(6)->similarSearchByUrl($image_url, '10', 0, 0, $limit);
        $results = array();
        if (!empty($res['result'])) {
            foreach ($res['result'] as $item) {
                $brief = json_decode($item['brief'], true);
                $results[] = array('metadata' => $brief['pop_id'], 'score' => $item['score']);
            }
        }
        $end_time = microtime(true);
        $time = number_format(($end_time - $begin_time), 2, '.', '');
        $http_resp = array(
            'results' => $results,
            'is_err' => 0,
            'msg' => '',
            'time' => $time,
        );
        return $http_resp;
    }

    /*------------------------------------------------------------------------------------------------------------------
	 * 调用面料接口(接口数据缓存)
	 *----------------------------------------------------------------------------------------------------------------*/
    private function getInterfaceData($postField = [], $retType = false, $col = 82)
    {
        $this->load->driver('cache');
        $memKey = $this->memcachePref . 'InterfaceData_' . md5($postField['url']);
        $result = $this->cache->memcached->get($memKey);
        if (empty($result) || $this->input->get('refresh')) {
            $http_resp = $this->getMatchImageFromBaidu($postField['url'],$postField['count']);
            if ($http_resp['is_err'] == 0) {
                $result['success'] = 1;
                $result['usetime'] = $http_resp['time'];
                if (is_array($http_resp['results']) && !empty($http_resp['results'])) {
                    $result['data'] = $http_resp['results'];
                } else {
                    $result['data'] = [];
                }
            } else {
                //失败
                $result['success'] = 0;
                $result['msg'] = $http_resp['msg'];
            }
            $this->cache->memcached->save($memKey, $result, 28800);
        }
        return $result;
    }

    /*------------------------------------------------------------------------------------------------------------------
     * 处理相似图案接口数据（去solr中不存在数据、取更多信息、分组）
     *----------------------------------------------------------------------------------------------------------------*/
    private function dealInterfaceData($array)
    {
        $_tmp = $result = $list = $category = $otherCat = array();
        $this->load->model("PatternLibrary_model");
        foreach ($array as $key => $row) {
            if (!in_array($row["metadata"], $_tmp)) {
                $_tmp[] = $row["metadata"]; //用于除去接口返回数据中重复的值
                //判断solr中是否存在
                $condition["pop_id"] = $row["metadata"];
                $iTotal = POPSearch::wrapQueryPopFashionMerger('', $condition, $ret, 0, 1, []);

                //获取数据
                $metadata = explode('_', $row['metadata']);
                $id = intval(array_pop($metadata));
                $tablename = implode('_', $metadata);
                if (!in_array($tablename, $this->fabric_match_table) || $iTotal < 1) {
                    continue;
                }
                $list[$key]['t'] = getProductTableName($tablename);
                $list[$key]['id'] = $id;
                $_data = OpPopFashionMerger::getProductData($id, $tablename);
                $data = $_data[$id];
                $pathArr = getImagePath($tablename, $data);
                $list[$key]['path'] = $pathArr['cover'];
                $sPatternContentArr = explode(",", $data["sPatternContent"]);
                $sPatternContentArr = array_filter($sPatternContentArr);
                $list[$key]["dCreateTime"] = date("Y-m-d", strtotime($data["create_time"]));
                $Brand = OpPopFashionMerger::getBrandData(intval($data["iBrandId"]));
                $list[$key]["brandName"] = isset($Brand["name"]) ? $Brand["name"] : "";
                //分类与图案内容
                $sPatternContent = array();
                if (!empty($sPatternContentArr)) {
                    foreach ($sPatternContentArr as $val) {
                        $_sName = isset($this->Attr_model->Attribute[$val]) ? $this->Attr_model->Attribute[$val] : null;
                        if ($_sName) {
                            $sPatternContent[] = trim($_sName);
                            $category[$val]['name'] = $_sName;
                            $category[$val]['count'] += 1;
                            $category[$val]['id'] = $val;
                            $list[$key]['search'][] = $val;
                        } else {
                            $list[$key]['search'][] = 1;
                            $otherCat['count'] += 1;
                            $otherCat['name'] = "其他";
                            $otherCat['id'] = 1;
                        }

                    }
                } else {
                    $list[$key]['search'][] = 1;
                    $otherCat['count'] += 1;
                    $otherCat['name'] = "其他";
                    $otherCat['id'] = 1;
                }
                $list[$key]["description"] = implode(" ", $sPatternContent);
                $data = $this->PatternLibrary_model->getDetail($tablename, $id);
                $list[$key] = array_merge($list[$key], $data);
            }
        }
        //分类按数量排序
        $category = twoDimensionSort($category, "count");
        if (!empty($otherCat)) {
            $category[] = $otherCat;
        }
        if (count($category) > 0 && !empty($category)) {
            $first = ["name" => "全部", "count" => count($list), "id" => ""];
            array_unshift($category, $first);
        }
        $list = array_values($list);
        $result = array("list" => $list, "category" => $category);
        return $result;
    }

    /*------------------------------------------------------------------------------------------------------------------
    * 调用接口头及其相关函数
    *----------------------------------------------------------------------------------------------------------------*/
    private function getHeader($body)
    {
        $timestamp = $this->get_millisecond();
        $headers = [
            "x-ca-accesskeyid" => $this->access_key_id,
            "x-ca-version" => $this->ssf_client_version,
            "x-ca-timestamp" => $timestamp,
            "x-ca-signaturenonce" => $this->short_uuid($this->ssf_signature_len),
            "x-ca-signature" => "",
            "requestmethod" => 'post'
        ];
        $signature = $this->client_signature($headers, $body);
        $headers["x-ca-signature"] = $signature;
        return $this->convert_headers($headers);
    }

    private function convert_headers($headers)
    {
        $ret = array();
        foreach ($headers as $key => $val) {
            $ret[] = sprintf("%s:%s", $key, $val);
        }
        return $ret;
    }

    private function short_uuid($length)
    {
        $str = null;
        $strPol = $this->strPol;
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }

    private function get_millisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return substr(sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000), 0, 10);
    }

    private function client_signature($headers, $body)
    {
        $sort_value = array();
        foreach ($body as $key => $val) {
            $headers[$key] = $val;
        }

        ksort($headers);
        foreach ($headers as $key => $val) {
            if ($key != "x-ca-signature") {
                $merged = sprintf("%s=%s", trim($key), trim($val));
                array_push($sort_value, $merged);
            }
        }
        foreach ($body as $key => $val) {
            unset($headers[$key]);
        }
        $string_to_signature = join("&", $sort_value);
        $signature = hash_hmac('sha1', $string_to_signature, $this->secret_key, true);
        $signature = base64_encode($signature);
        return $signature;
    }
    //**********************************************相似图案接口[end]*************************************************//

}