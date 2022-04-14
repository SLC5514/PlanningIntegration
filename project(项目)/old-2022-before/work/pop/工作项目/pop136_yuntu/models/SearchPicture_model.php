<?php
/**
 * 云图app--以图搜图model类
 */
require getenv('BASEPATH') . '/image_search/ImageSearch.php';
class SearchPicture_model extends POP_Model
{
    //用户图片上传表
    const T_FASHION_FM_UPLOAD_PIC_MATERIAL = '`fashion`.`fm_upload_pic_material`';

    private $ssf_signature_len = 32;
    private $ssf_client_version = '1';
    private $strPol = 'abcdefghijklmnopqrstuvwxyz0123456789';
    private $secret_key = 's8ygcYsyiTKC0Ihcl1blVZpfntg';
    private $access_key_id = 'popop';
    private $ssf_client_api = 'https://api.productai.cn/search/4tzj7h2l/';
    private $fabric_match_table = ['product_graphicitem'];
    private $memcachePref; // memcache前缀

    public function __construct()
    {
        parent::__construct();
        // 云图app 的以图搜图 memcache前缀
        $this->memcachePref = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . "_App_Yuntu_Cloud_SimilarPatterns_BD_";
    }

    /**
     * 上传图片记录传到数据库
     * @param $userId
     * @param $imgPath
     */
    public function getUploadPic($userId, $imgPath)
    {
        if (!$userId || !$imgPath) return false;
        //插入数据库记录
        $data = ['sAccountId' => $userId, 'sUploadPicImg' => $imgPath, "dCreateTime" => date('Y-m-d H:i:s')];
        $this->executeSave(self::T_FASHION_FM_UPLOAD_PIC_MATERIAL, $data);

        //去域名（有些url带域名有些不带）
        $photo_url = parse_url($imgPath, PHP_URL_PATH);

        $result = array(
            //"imgPath" => getStaticUrl($imgPath) . $imgPath, // STATIC_URL1
            "imgPath" => STATIC_URL1 . $photo_url,
        );

        return $result;
    }

    /**
     * 获取以图搜图的列表数据
     * @param string $imgUrl
     * @param array $paramsArr
     * @param int $offset
     * @param int $limit
     * @param int $sort 3--默认--系统默认推荐匹配度最高
     */
    public function getList($imgUrl, $paramsArr = array(), $offset = 0, $limit = 0, $sort = 3)
    {
        $this->load->driver('cache');

        // 调取接口的参数
        $postData['url'] = $imgUrl;
        $postData['count'] = 300;

        $memKey = $this->memcachePref . 'FabricList_' . md5($imgUrl);
        $result = $this->cache->memcached->get($memKey);
        if (empty($result) || $this->input->get_post('refresh')) {
            $_result = $this->getInterfaceData($postData);       // 调用接口
            $result = $this->dealInterfaceData($_result["data"]); // 处理接口数据并分组
            $this->cache->memcached->save($memKey, $result, 3600);
        }

        // 合并用户选择的筛选项--标签ID这几个都是不同的，所以可以合并
        $params = array_filter(array_reduce($paramsArr, 'array_merge', array()));
        // 筛选条件与查询结果作对比
        if (!empty($result['list']) && $params) {
            $_data = array();
            foreach ($result['list'] as $key => $val) {
                if (array_intersect($params, $val['search'])) {
                    $_data[$key] = $val;
                }
            }
            $result['list'] = $_data;
        }

        // 排序： 1=>更新时间(最新发布),2=>浏览量(最受欢迎)  3--默认
        if ($sort == 1) {
            $result['list'] = twoDimensionSort($result['list'], "create_time");
        } elseif ($sort == 2) {
            $result['list'] = twoDimensionSort($result['list'], "view_count");
        }

        // 筛选后的总的数量
        $total = count($result['list']);

        //分页($limit为0时不分页)
        $_list = $limit > 0 ? array_slice($result['list'], $offset, $limit) : $result['list'];
        $result['list'] = array_values($_list);

        return array($result['list'], $total);
    }


    //**********************************************相似图案接口[start]***********************************************//
    /*------------------------------------------------------------------------------------------------------------------
	 * 调用面料接口(接口数据缓存)
	 *----------------------------------------------------------------------------------------------------------------*/
    private function getInterfaceData($postField = array(), $retType = false, $col = 82)
    {
        $this->load->driver('cache');

        $memKey = $this->memcachePref . 'InterfaceData_' . md5($postField['url']);
        $result = $this->cache->memcached->get($memKey);

        if (empty($result) || $this->input->get_post('refresh')) {
            $http_resp = $this->getMatchImageFromBaidu($postField['url'],$postField['count']);
            if ($http_resp['is_err'] == 0) {
                $result['success'] = 1;
                $result['usetime'] = $http_resp['time'];
                if (is_array($http_resp['results']) && !empty($http_resp['results'])) {
                    $result['data'] = $http_resp['results'];
                } else {
                    $result['data'] = array();
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
     * 处理相似图案接口数据（去solr中不存在数据、取更多信息、分组）
     *----------------------------------------------------------------------------------------------------------------*/
    private function dealInterfaceData($array)
    {
        $this->load->model("Graphicitem_model");
        $_tmp = $result = $list = $category = $otherCat = array();

        foreach ($array as $key => $row) {
            if (!in_array($row["metadata"], $_tmp)) {
                $_tmp[] = $row["metadata"]; //用于除去接口返回数据中重复的值

                //判断solr中是否存在
                $condition["pop_id"] = $row["metadata"];
                $iTotal = POPSearch::wrapQueryPopFashionMerger('', $condition, $ret, 0, 1, array());

                //获取数据
                $metadata = explode('_', $row['metadata']);
                $id = intval(array_pop($metadata));
                $tablename = implode('_', $metadata);

                if (!in_array($tablename, $this->fabric_match_table) || $iTotal < 1) {
                    continue;
                }

                // popid
                $list[$key]['popId'] = getProductTableName($tablename) . "_" . $id;
                $list[$key]['t'] = getProductTableName($tablename);
                $list[$key]['id'] = $id;
                $_data = OpPopFashionMerger::getProductData($id, $tablename);
                $data = $_data[$id];

                $pathArr = getImagePath($tablename, $data);
				$list[$key]['small'] = $list[$key]['imgPath'] = $pathArr[ 'smallPath' ];
				$list[$key]['mbig'] = $this->Graphicitem_model->getPath($pathArr['bigPath']);
				$list[$key]['big'] = $pathArr[ 'bigPath' ];

                $list[$key]["dCreateTime"] = date("Y-m-d", strtotime($data["create_time"]));
                $list[$key]["memo"] = $data["memo"];// 描述
                $list[$key]["view_count"] = $data["view_count"];// 浏览量
                $list[$key]["create_time"] = date("Y-m-d H:i:s", strtotime($data["create_time"]));//浏览时间

                // sAPP--局部/满身 sFor--图案格式 sGen--性别 sPat--图案内容 sTec--图案工艺
                // 图案内容 11708,12089,12091
                $sPat = $this->getAttr($data["sPatternContent"]);
                // 性别 1，2，3，4，5
                $sGen = $this->getAttr($data["sGender"]);
                // 矢量/位图(图案格式) 10961，10969，15090
                $sFor = $this->getAttr($data["sFormat"]);
                // 局部/满身(图案应用) 14102,14103
                $sAPP = $this->getAttr($data["sApplication"]);

                $list[$key]['search'] = array_filter(array_merge($sPat, $sGen, $sFor, $sAPP));

            }
        }

        $list = array_values($list);
        $result = array("list" => $list);
        return $result;
    }


    /*------------------------------------------------------------------------------------------------------------------
    * 标签属性id
    *----------------------------------------------------------------------------------------------------------------*/
    private function getAttr($item)
    {
        $rows = array();
        $result = explode(",", $item);
        $result = array_filter($result);
        if (!empty($result)) {
            foreach ($result as $val) {
                $rows[] = $val;
            }
        }
        return $rows;
    }

    /*------------------------------------------------------------------------------------------------------------------
    * 调用接口头及其相关函数
    *----------------------------------------------------------------------------------------------------------------*/
    private function getHeader($body)
    {
        $timestamp = $this->get_millisecond();
        $headers = [
            "x-ca-accesskeyid" => $this->access_key_id, // popop
            "x-ca-version" => $this->ssf_client_version, // 1
            "x-ca-timestamp" => $timestamp,
            "x-ca-signaturenonce" => $this->short_uuid($this->ssf_signature_len),// 32
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