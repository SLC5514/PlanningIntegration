<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo 图片匹配
 */
class Picmatch extends POP_Controller
{

    /**
     *
     * Class constructor
     * @return    void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
    * 切图列表页
    */
    public function piccutlist($params = '')
    {

        if ($params) {
            // $params为	t_perform-id_'+_id+'-col_52
            $params = $this->common_model->restoreParams($params);
            $paramsArr = $this->common_model->parseParams($params);
            // 参数(表别名 id)
            $id = intval($paramsArr['id']);
            $table = $paramsArr['t'];
            $tb_id = $table . '_' . $id;
            $col = $paramsArr['col'];
            $path = urldecode($this->input->get('path', TRUE));
            $path = STATIC_URL1 . $path;
            $mfs_path = parse_url($path, PHP_URL_PATH);
            $this->assign('photo_url', $mfs_path);
            $this->assign('mfs_path', $mfs_path);
            $this->assign('tb_id', $tb_id);
            if ($table == 'uploadpicmaterial') {
                $title = '找相似图案_找相关面料_智能识别-POP服装趋势网';
                $keywords = '智能识别,图片匹配,图像识别';
                $description = '在截取图像时，请您注意保持框选区域的清晰度及完整性，您可以使用鼠标任意拖拽图片，并用滚轮调整图片大小。';
            } else {
                $info = [];
                $this->load->model('details_model');
                $realtable = getProductTableName($table);
                if ($realtable != false) {
                    $info = $this->details_model->getPicInfo($id, $realtable, '', 0);
                }
                $title = $info['title'] . '-POP服装趋势网';
                $keywords = $info['title'];
                $description = '您可以对POP服装趋势网提供的' . $info['title'] . '素材信息，进行收藏、下载和分享。';
            }

            $this->assign('title', $title);
            $this->assign('keywords', $keywords);
            $this->assign('description', $description);
        } else {
            $this->assign('init', 1);
        }

        $title = '找相似图案_找相关面料_智能识别-POP服装趋势网';
        $keywords = '智能识别,图片匹配,图像识别';
        $description = '在截取图像时，请您注意保持框选区域的清晰度及完整性，您可以使用鼠标任意拖拽图片，并用滚轮调整图片大小。';
        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);
        $this->display('cutpic.html');
    }

    /*
    * 切图处理
    */
    public function dealdata()
    {
        $post = $this->input->post(NULL, TRUE);
        $this->load->model('picmatch_model');
        $this->load->helper('url');
        $res = $this->picmatch_model->cutPicDeal($post);
        echo json_encode($res);
    }

    /*
    * 找相似图案
    */
    public function fabriclist()
    {
        $stime = microtime(true);
        $path = $this->input->get('path', true);
        $cat = $this->input->get('cat', true);
        $postData['url'] = STATIC_URL1 . $path;
        $result = $this->getfabriclist($postData, $cat);
        $etime = microtime(true);
        if ($result['success'] != 1) {
            $result['data'] = [];
            //接口返回无数据时,自取匹配时间
            $result['usetime'] = number_format(($etime - $stime), 2, '.', '');
        }


        //优料宝面料
        $uliaobaourl = 'http://www.uliaobao.com/s/search/toSerchImage?imageUrl=' . $postData['url'];
        $this->assign('uliaobaourl', $uliaobaourl);
        $this->assign('backurl', $this->input->post('backurl', true));
        $this->assign('imageurl', $postData['url']);
        $this->assign('path', urlencode($path));
        $this->assign('result', $result);
        $this->assign('cat', $cat);
        $res = $this->fetch('lists/picmatch_list.html');
        echo $res;
    }

    /*
     * 找相似图案 ajax异步
     */
    public function fabriclistAjax()
    {
        $path = $this->input->get('path', true);
        $cat = $this->input->get('cat', true);
        $page = $this->input->get('page', true);
        $pageSize = 10;
        $postData['url'] = STATIC_URL1 . $path;
        $offset = ($page - 1) * $pageSize;
        $result = $this->getfabriclist($postData, $cat, $offset, $pageSize);
        echo json_encode($result['data']);
    }

    //
    private function getfabriclist($postData, $cat, $offset = 0, $length = 20)
    {
        $this->load->model('picmatch_model');
        $memKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_picmatch20170704_' . md5($postData['url']);
        $result = $this->cache->memcached->get($memKey);
        if (empty($result['data']) || $this->input->get('refresh')) {
            //测试
            //$postData['url'] = 'https://imgf2.pop-fashion.com/fashion/fashion_shows/coverimage/sCoverImg_1474508481.jpg';
            $postData['count'] = 300;
            $result = $this->picmatch_model->getInterfaceData($postData);
            $result['count'] = count($result['data']);
            $this->cache->memcached->save($memKey, $result, 28800);
        }
        if ($result['data'] && $cat) {
            $_data = [];
            foreach ($result['data'] as $key => $val) {
                if (in_array($cat, $val['search'])) {
                    $_data[$key] = $val;
                }
            }
            $result['data'] = $_data;
        }
        $result['data'] = array_slice($result['data'], $offset, $length);
        return $result;
    }

    /*
    * 文件上传
    */
    public function uploadpic()
    {
        $this->load->model('picmatch_model');
        $res = $this->picmatch_model->uploadfile();
        echo $res;
    }

    /*
    * loading
    */

    public function loading()
    {
        $path = $this->input->get('path', true);
        checkTrailInfo($path, 'picmatch:images');

        $refresh = $this->input->get('refresh', true);
        $cat = $this->input->get('cat', true);
        if ($_SERVER['HTTP_REFERER'] == null) {
            $backurl = config_item('base_url');
        } else {
            $backurl = $_SERVER['HTTP_REFERER'];
        }
        $pathData = $path;
        $pathData = !empty($refresh) ? $pathData . "&refresh={$refresh}" : $pathData;
        $pathData = !empty($cat) ? $pathData . "&cat={$cat}" : $pathData;
        $this->assign('pathData', $pathData);
        $this->assign('path', $path);
        $this->assign('backurl', $backurl);

        $this->assign('title', '图片识别_图案匹配-POP服装趋势网');
        $this->assign('keywords', '图案匹配,图片识别');
        $this->assign('description', '在本站图案素材中如果找不到合适的图案素材，返回重新截取或换张图再试，其中列表中排序越靠前的图案，系统判断与您的需求相似度越高。');
        $this->display('cutpicloading.html');
    }

    /*************************新的以图搜图**************************************/

    //上传文件
    public function new_upload_pic()
    {
        $this->load->model('picmatch_model');
        $res = $this->picmatch_model->new_upload_file();
        echo $res;
    }

    /*
    * 切图处理
    */
    public function new_cut_pic()
    {
        $post = $this->input->post(NULL, TRUE);
        $this->load->model('picmatch_model');
        $this->load->helper('url');

        $res = $this->picmatch_model->new_cut_pic($post);
        echo json_encode($res);
    }

    //相似图搜索
    public function similarpic()
    {
        // $this->imgSearch = ImageSearch::getInstance();
        // echo $url = STATIC_URL1.$_GET['path'];
        // $response_data = $this->imgSearch->similarSearchByUrl($url, '1,105', '1', '0', 500);
        // var_dump($response_data);
        // exit;
        $powers = memberPower();

        if ($powers['P_UserType'] == 5) {
            header('Location:/member/pagelogin/');
            die;
        }

        $this->load->model('picmatch_model');

        //列表数据
        $this->isAjax = $this->input->is_ajax_request();

        $type = $this->input->get('type');//智能识别页
        $path = $this->input->get_post('path');//框选图片URL
        $original_img = $this->input->get_post('original_img');//原始图片URL
        $cat = $this->input->get_post('cat');//筛选标签 (款式：4 图案：9)
        $page = max($this->input->get_post('page'), 1);//页码
        $pagesize = 60;

        //切图后的图片尺寸是否正确 (图片小于4M，最长边不大于4096px)
        $fileInfo = getimagesize(STATIC_URL1.$_GET['path']);
        $width = $fileInfo[0];
        $height = $fileInfo[1];
        if($width>4096 || $height>4096) {
            echo json_encode(array('code'=>1001,'msg'=>'抱歉！图片尺寸超出限制，请重新上传图片'));exit;
        }
        $fileSize = filesize(STATIC_URL1.$_GET['path']);
        if ($fileSize > 4*1024*1024) {
            echo json_encode(array('code'=>1001,'msg'=>'抱歉！图片尺寸超出限制，请重新上传图片'));exit;
        }

        // 检测普通用户权限20次 (修改键名，同原来逻辑一致)
        checkTrailInfo($path, 'newpicmatch:images');

        //测试url
        //     $path = '/fashion/stylegraphic/202001020000057820/women/2020SS/159/top/jersey/202001020007732386/big/1aaf031247c5c59c11a0dd17086e10fe.jpg';

        if ($this->isAjax) {

            if (empty($path)) {
                echo json_encode(['code' => 100, 'msg' => '图片为空', 'data' => []]);
                exit;
            }
            //获取匹配结果
            list($info, $list) = $this->picmatch_model->image_search($path, $cat, $page, $pagesize);
            if(empty($info)){
                //若错误码不为image size error，则展示该错误提示页面
                echo json_encode(array('code'=>1002,'msg'=>'抱歉，出了点问题，检索失败！'));exit;
            }

            $info['sCategory'] = isset($info['sCategory']) && is_array($info['sCategory']) ? $info['sCategory'] : [];
            $info['sPatternContent'] = isset($info['sPatternContent']) && is_array($info['sPatternContent']) ? $info['sPatternContent'] : [];
            echo json_encode(['code' => 200, 'msg' => 'ok', 'data' => $list,
                'info' => ['filters' => $info['filters'], 'total_page' => isset($info['total_page']) ? $info['total_page'] : 0,
                    'total_num' => isset($info['total_num']) ? $info['total_num'] : 0,]]);
            exit;
        }

        $this->assign('type', $type);
        $this->assign('cat', $cat);
        $this->assign('path', STATIC_URL1 . $path);
        $this->assign('origin_image', STATIC_URL1 . $original_img);
        $this->display('cutpicloading.html');

    }
    

}