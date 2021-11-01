<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 图片匹配 专用类
 * @property-read common_model $common_model
 */
class Picmatch_model extends POP_Model
{
    private $ssf_signature_len = 32;
    private $ssf_client_version = '1';
    private $strPol = 'abcdefghijklmnopqrstuvwxyz0123456789';
    private $secret_key = 's8ygcYsyiTKC0Ihcl1blVZpfntg';
    private $access_key_id = 'popop';
    private $ssf_client_api = 'https://api.productai.cn/search/4tzj7h2l/';
    private $fabric_match_table = ['product_graphicitem'];
    private $imgSearch;
    //图片上传参数
    private $allowed_types = ['gif', 'jpg', 'png', 'jpeg'];    //允许上传的文件格式
    private $max_size = 5120;    //最大值(kb)

    public function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->imgSearch = ImageSearch::getInstance();

    }

    /*
    * 调用接口头
    */
    public function getHeader($body) {
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

    /*
    * 调用面料接口
    */
    public function getInterfaceData($postField = [], $retType = false, $col = 82) {
        $header = $this->getHeader($postField);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->ssf_client_api);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        #curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        #curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false); // required as of PHP 5.6.0
        $postField = http_build_query($postField);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postField);
        $http_resp = curl_exec($ch);
        $e1 = curl_error($ch);
        $e2 = curl_errno($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $http_resp = json_decode($http_resp, true);

        //去掉已删除和重复的图片
        if ($http_resp['results']) {
            $_aTemp = [];
            foreach ($http_resp['results'] as $_key => $_results) {
                $condition['pop_id'] = $_results['metadata'];
                // 条件：图案是家纺与服装共用solr，现在只取服装的.
                $condition['aWebsite'][] = 1;
                //验证solr是否存在这条数据
                $_iTotal = POPSearch::wrapQueryPopFashionMerger('', $condition, $ret, 0, 1, []);

                //去重,排除已删除
                if (in_array($_results['metadata'], $_aTemp) || !$_iTotal) {
                    unset($http_resp['results'][$_key]);
                } else {
                    $_aTemp[] = $_results['metadata'];
                }
            }

            $http_resp['results'] = array_values($http_resp['results']);
        }

        if ($retType == true) {
            //只取表和ID时
            return $http_resp;
            exit();
        }
        $catgory = [];
        if ($http_resp['is_err'] == 0) {
            //成功
            $result['success'] = 1;
            $result['usetime'] = $http_resp['time'];
            if ($http_resp['results']) {
                foreach ($http_resp['results'] as $key => $_results) {
                    $metadata = explode('_', $_results['metadata']);
                    $id = intval(array_pop($metadata));
                    $tablename = implode('_', $metadata);
                    if (!in_array($tablename, $this->fabric_match_table)) {
                        continue;
                    }
                    $data = OpPopFashionMerger::getProductData($id, $tablename);
                    //图案是家纺与服装共用solr，现在只取服装的.
                    $allow_site = 'fashion';
                    $channel_site = isset($data[$id]['channel']) ? $data[$id]['channel'] : 'fashion';// jiafang,fashion
                    if (in_array($allow_site, explode(',', $channel_site))) {

                        if (strpos($data[$id][memo], '实物面料') !== false) {
                            $_col = 117;//实物面料特殊，属于展会面料栏目
                        } else {
                            $_col = $col;
                        }
                        $path = getImagePath($tablename, $data[$id]);
                        //https://www.pop-fashion.com/details/style/t_templateitem-id_64174-col_80/
                        $result['data'][$key]['path'] = $path['cover'];
                        $result['data'][$key]['link'] = '/details/style/t_' . getProductTableName($tablename) . '-id_' . $id . '-col_' . $_col . '/';
                        $result['data'][$key]['create_time'] = date('Y-m-d', strtotime($data[$id]['create_time']));    //创建时间
                        $result['data'][$key]['sPatternContent'] = "";
                        if ($data[$id]['sPatternContent']) {
                            foreach (explode(',', trim($data[$id]['sPatternContent'])) as $val) {
                                if (!empty($val)) {
                                    $_sName = GetCategory::getOtherFromIds($val, ['sName']);    //图案内容
                                    $result['data'][$key]['sPatternContent'] .= "$_sName";
                                    $category[$val]['name'] = $_sName;
                                    $category[$val]['count'] += 1;
                                    $category[$val]['id'] = $val;
                                    $result['data'][$key]['search'][] = $val;
                                }
                            }
                        } else {
                            $result['data'][$key]['search'][] = 1;
                            $category[1]['count'] += 1;
                            $category[1]['name'] = "其他";
                            $category[1]['id'] = 1;
                        }

                        if (isset($data[$id]['memo'])) {
                            $arr = OpPopFashionMerger:: getPatternAttribute($data[$id]['memo']);
                            $this->load->model('category_model');
                            $sPatternUse = $this->category_model->getAll('sPatternUse');
                            $result['data'][$key]['sPatternUseName'] = $sPatternUse[$arr['sPatternUse']];    //图案应用
                            //$sPatternFormat = $this->category_model->getAll('sPatternFormat');
                            //$result['data'][$key]['sPatternFormatName'] = $sPatternFormat[$arr['sPatternFormat']];	//图案格式
                        }
                    }
                }
                //排序
                if ($category[1]) {
                    $other = $category[1];
                    unset($category[1]);
                }
                foreach ($category as $key => $val) {
                    $sortArr[$key] = $val['count'];
                }
                array_multisort($sortArr, SORT_DESC, $category);
                if ($other) {
                    $category[] = $other;
                }
                $result['category'] = $category;
            } else {
                $result['data'] = [];
            }
        } else {
            //失败
            $result['success'] = 0;
            $result['msg'] = $http_resp['msg'];
        }
        return $result;
    }

    /*
    * 切图处理（备份的）
    */
    public function cutPicDealx($params = []) {
        $cut_pos = explode(',', $params['cut_pos']);
        $params['x'] = intval($cut_pos[0]);
        $params['y'] = intval($cut_pos[1]);
        $params['w'] = intval($cut_pos[2]);
        $params['h'] = intval($cut_pos[3]);
        $result = [];
        $id = 0;
        if ($params['tb_id']) {
            $p = explode('_', $params['tb_id']);
            $params['tb_id'] = getProductTableName($p[0]) . '_' . $p[1];
            $id = $p[1];
        }
        //用户自行上传图片
        if (preg_match('/^(\/material)/', $params['photo_url'])) {
            $source_image = str_replace('//', '/', $params['photo_url']);
            if (!file_exists(APPPATH . $source_image)) {
                $result['success'] = 0;
                $result['msg'] = '原图不存在';
                return $result;
            }
        } else  //来源于款式库的图片
        {
            $source_image = str_replace('//', '/', $params['photo_url']);
            if (!api_file_exists($source_image)) {
                $result['success'] = 0;
                $result['msg'] = '原图不存在';
                return $result;
            }
            $source_image = STATIC_URL1 . $source_image;
        }

        $source_info = getimagesize($source_image);
        $width = $source_info[0];
        $height = $source_info[1];
        $mime = $source_info['mime'];
        switch ($mime) {
            case 'image/gif':
                $image = imagecreatefromgif($source_image);
                break;

            case 'image/jpeg':
                $image = imagecreatefromjpeg($source_image);
                break;

            case 'image/png':
                $image = imagecreatefrompng($source_image);
                break;

            //case 'image/x-ms-bmp':
            //$image = $this->ImageCreateFromBMP($source_image);
            //break;

            default:
                return false;
                break;
        }
        if ($image == false) {
            $result['success'] = 0;
            $result['msg'] = '切图失败';
            return $result;
        }

        // 重新取样
        $image_p = imagecreatetruecolor($params['w'], $params['h']);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $params['w'], $params['h'], $width, $height);

        //年月日/(大图id/1000000)/(大图id/1000)/大图id/名字
        $imgpath = 'material/design_area/' . date('Ymd') . '/' . ceil($id / 1000000) . '/' . ceil($id / 1000) . '/' . $id . '/';
        $this->_mkdir(APPPATH . $imgpath);

        $path = APPPATH . $imgpath . uniqid($id) . time() . '.jpg';

        $new_file_name = date('ymdHis') . rand(0, 10000) . '.jpg';

        if (imagejpeg($image_p, $path, 100)) {
            imagedestroy($image_p);
            imagedestroy($image);
            $cutsourceimage = imagecreatefromjpeg($path);
            $image_n = imagecreatetruecolor($params['w'] > 250 ? 250 : $params['w'], $params['h'] > 250 ? 250 : $params['h']);
            $white = imagecolorallocate($image_n, 255, 255, 255);
            imagefill($image_n, 0, 0, $white);
            imagecopy($image_n, $cutsourceimage, 0, 0, $params['w'] > 250 ? $params['x'] : 0, $params['h'] > 250 ? $params['y'] : 0, $params['w'] > 250 ? 250 : $params['w'], $params['h'] > 250 ? 250 : $params['h']);
            if (imagejpeg($image_n, APPPATH . $imgpath . $new_file_name, 100)) {
                imagedestroy($cutsourceimage);
                imagedestroy($image_n);
                $data = [];
                $this->load->model('member_model');
                $user_info = $this->member_model->logonMessage();
                $data['sAccountId'] = $this->_getUserId();
                $data['sDesignAreaImg'] = '/' . $imgpath . $new_file_name;
                $data['iSourceType'] = $p[0] == 'uploadpicmaterial' ? 2 : 1;
                $data['sSourceID'] = $params['tb_id'];    //假表名_id
                $data['dCreateTime'] = date('Y-m-d H:i:s');    //时间
                $res = $this->db()->insert(self::T_FASHION_FM_DESIGN_AREA, $data, true);
                $result['success'] = 1;
                $result['path'] = '/picmatch/loading/?path=' . urlencode($data['sDesignAreaImg']);
                $result['imgPath'] = urlencode(STATIC_URL1 . $data['sDesignAreaImg']);
                @unlink($path);
            } else {
                $result['success'] = 0;
                $result['msg'] = '切图失败';
            }
        } else {
            $result['success'] = 0;
            $result['msg'] = '切图失败';
        }
        return $result;
    }

    /*
	* 切图处理
	*/
    public function cutPicDeal($params = []) {
        $this->load->library('POP_Uploadpic');
        //imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
        $source_image = str_replace('//', '/', $params['photo_url']);
        $cut_pos = explode(',', $params['cut_pos']);
        $dst_x = intval($cut_pos[0]);
        $dst_y = intval($cut_pos[1]);
        $dst_w = intval($cut_pos[2]);
        $dst_h = intval($cut_pos[3]);
        $cut_w = intval($cut_pos[4]);
        $cut_h = intval($cut_pos[5]);
        $id = 0;
        if ($params['tb_id']) {
            $p = explode('_', $params['tb_id']);
            $params['tb_id'] = getProductTableName($p[0]) . '_' . $p[1];
            $id = $p[1];
        }
        $body = [
            'src_image' => $source_image,
            'dst_x' => $dst_x,
            'dst_y' => $dst_y,
            'dst_w' => $dst_w,
            'dst_h' => $dst_h,
            'cut_w' => $cut_w,
            'cut_h' => $cut_h,
            'imgid' => $id,
            'action' => 'cutPicDeal',
        ];

        $json = $this->pop_uploadpic->curlRequest($body);
        $jsonArr = json_decode($json, true);
        $result = [];
        if ($jsonArr['status'] == 1) {
            $data = [];
            $this->load->model('member_model');
            $user_info = $this->member_model->logonMessage();
            $data['sAccountId'] = $this->_getUserId();
            $sDesignAreaImg = $jsonArr['info'];
            $data['sDesignAreaImg'] = $sDesignAreaImg;
            $data['iSourceType'] = $p[0] == 'uploadpicmaterial' ? 2 : 1;
            $data['sSourceID'] = $params['tb_id'];    //假表名_id
            $data['dCreateTime'] = date('Y-m-d H:i:s');    //时间
            $res = $this->db()->insert(self::T_FASHION_FM_DESIGN_AREA, $data, true);
            $result['success'] = 1;
            $result['path'] = '/picmatch/loading/?path=' . urlencode($sDesignAreaImg);
            $result['imgPath'] = urlencode(STATIC_URL1 . $sDesignAreaImg);
        } else {
            $result['success'] = 0;
            $result['msg'] = $jsonArr['info'];
        }
        return $result;
    }

    /*
    * 文件上传（备份的）
    */
    public function uploadfilex() {
        $form_type = intval($this->input->post('form_type', true));
        $config = [];
        $result = [];
        $config['overwrite'] = false;
        $config['upload_path'] = APPPATH . '/material/upload_pic_material/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';    //图片路径
        $config['allowed_types'] = $this->allowed_types;    //允许的文件格式
        $config['max_size'] = $this->max_size;    //允许的文件格式
        $config['file_name'] = md5(date('ymdHis') . rand(0, 10000));    //文件名字
        $this->_mkdir($config['upload_path']);
        $this->load->library('upload');
        $this->upload->initialize($config);
        if ($form_type == 1) {
            $error = $this->upload->do_upload('userfile1');
        } else {
            $error = $this->upload->do_upload('userfile');
        }
        if (!$error) {
            //$result['success'] = 0;
            return $this->upload->display_errors('', '');
        } else {
            $data = $this->upload->data();
            $path = str_replace(APPPATH, '', $config['upload_path']) . $data['file_name'];
            $result['success'] = 1;
            $row = [];
            $row['sAccountId'] = $this->_getUserId();
            $row['sUploadPicImg'] = $path;
            $row['dCreateTime'] = date('Y-m-d H:i:s');
            $this->db()->insert(self::T_FASHION_FM_UPLOAD_PIC_MATERIAL, $row, true);
            $id = $this->db()->insert_id();
            $result['path'] = '/picmatch/piccutlist/t_uploadpicmaterial-id_' . $id . '/?path=' . urlencode($path);
            $result['imgPath'] = urlencode($path);
            return json_encode($result);
        }
    }

    /*
	* 文件上传
	*/
    public function uploadfile() {
        $this->load->library('POP_Uploadpic');
        $form_type = intval($this->input->post('form_type', true));
        if ($form_type == 1) {
            $field = 'userfile1';
        } else {
            $field = 'userfile';
        }
        $res = $this->pop_uploadpic->getFileStream($_FILES[$field]);

        $result = [];
        if (!$res) {
            $result['success'] = 0;
            return json_encode($result);
        }

        //上传
        $savePath = 'upload_pic_material/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';    //图片路径
        $body = [
            'fName' => md5(date('ymdHis') . rand(0, 10000)) . '.' . $res['ext'],
            'fTargetPath' => $savePath,
            'fStream' => $res['stream'],
            'fWidth' => $res['width'],
            'fHeight' => $res['height'],
            'fPrefix' => 'material',
            'allowSuffix' => implode(',', $this->allowed_types),
        ];
        $json = $this->pop_uploadpic->curlRequest($body);
        $jsonArr = json_decode($json, true);

        $result['success'] = $jsonArr['status'];
        if ($result['success'] == 1) {
            $path = $jsonArr['info'];

            $row = [];
            $row['sAccountId'] = $this->_getUserId();
            $row['sUploadPicImg'] = $path;
            $row['dCreateTime'] = date('Y-m-d H:i:s');
            $this->db()->insert(self::T_FASHION_FM_UPLOAD_PIC_MATERIAL, $row, true);
            $id = $this->db()->insert_id();

            $result['path'] = '/picmatch/piccutlist/t_uploadpicmaterial-id_' . $id . '/?path=' . urlencode($path);
            $result['imgPath'] = urlencode($path);

            return json_encode($result);
        } else {
            // 失败
            // return $jsonArr['info'];
            $result['success'] = 0;
            return json_encode($result);
        }
    }

    /*
    * 目录权限
    */
    private function _mkdir($path = '') {
        if (!is_dir($path)) {
            mkdir($path, DIR_WRITE_MODE, true);
        }
    }

    /*
    * 获取用户id
    */
    private function _getUserId() {
        $user_info = $this->member_model->logonMessage();
        $sAccountId = $user_info['sChildID'] ? $user_info['sChildID'] : ($user_info['id'] ? $user_info['id'] : '');
        return $sAccountId;
    }

    private function convert_headers($headers) {
        $ret = array();
        foreach ($headers as $key => $val) {
            $ret[] = sprintf("%s:%s", $key, $val);
        }
        return $ret;
    }

    private function short_uuid($length) {
        $str = null;
        $strPol = $this->strPol;
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }

    private function get_millisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return substr(sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000), 0, 10);
    }

    private function client_signature($headers, $body) {
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

    private function ImageCreateFromBMP($filename) {
        //Ouverture du fichier en mode binaire
        if (!$f1 = fopen($filename, "rb"))
            return false;

        //1 : Chargement des ent�tes FICHIER
        $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
        if ($FILE['file_type'] != 19778)
            return false;

        //2 : Chargement des ent�tes BMP
        $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' .
            '/Vcompression/Vsize_bitmap/Vhoriz_resolution' .
            '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));
        $BMP['colors'] = pow(2, $BMP['bits_per_pixel']);
        if ($BMP['size_bitmap'] == 0)
            $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
        $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel'] / 8;
        $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
        $BMP['decal'] = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
        $BMP['decal'] -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
        $BMP['decal'] = 4 - (4 * $BMP['decal']);
        if ($BMP['decal'] == 4)
            $BMP['decal'] = 0;

        //3 : Chargement des couleurs de la palette
        $PALETTE = array();
        if ($BMP['colors'] < 16777216) {
            $PALETTE = unpack('V' . $BMP['colors'], fread($f1, $BMP['colors'] * 4));
        }

        //4 : Cr�ation de l'image
        $IMG = fread($f1, $BMP['size_bitmap']);
        $VIDE = chr(0);
        $res = imagecreatetruecolor($BMP['width'], $BMP['height']);
        $P = 0;
        $Y = $BMP['height'] - 1;
        while ($Y >= 0) {
            $X = 0;
            while ($X < $BMP['width']) {
                if ($BMP['bits_per_pixel'] == 32) {
                    $COLOR = unpack("V", substr($IMG, $P, 3));
                    $B = ord(substr($IMG, $P, 1));
                    $G = ord(substr($IMG, $P + 1, 1));
                    $R = ord(substr($IMG, $P + 2, 1));
                    $color = imagecolorexact($res, $R, $G, $B);
                    if ($color == -1)
                        $color = imagecolorallocate($res, $R, $G, $B);
                    $COLOR[0] = $R * 256 * 256 + $G * 256 + $B;
                    $COLOR[1] = $color;
                } elseif ($BMP['bits_per_pixel'] == 24)
                    $COLOR = unpack("V", substr($IMG, $P, 3) . $VIDE);
                elseif ($BMP['bits_per_pixel'] == 16) {
                    $COLOR = unpack("n", substr($IMG, $P, 2));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 8) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, $P, 1));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 4) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                    if (($P * 2) % 2 == 0)
                        $COLOR[1] = ($COLOR[1] >> 4);
                    else
                        $COLOR[1] = ($COLOR[1] & 0x0F);
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 1) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                    if (($P * 8) % 8 == 0)
                        $COLOR[1] = $COLOR[1] >> 7;
                    elseif (($P * 8) % 8 == 1)
                        $COLOR[1] = ($COLOR[1] & 0x40) >> 6;
                    elseif (($P * 8) % 8 == 2)
                        $COLOR[1] = ($COLOR[1] & 0x20) >> 5;
                    elseif (($P * 8) % 8 == 3)
                        $COLOR[1] = ($COLOR[1] & 0x10) >> 4;
                    elseif (($P * 8) % 8 == 4)
                        $COLOR[1] = ($COLOR[1] & 0x8) >> 3;
                    elseif (($P * 8) % 8 == 5)
                        $COLOR[1] = ($COLOR[1] & 0x4) >> 2;
                    elseif (($P * 8) % 8 == 6)
                        $COLOR[1] = ($COLOR[1] & 0x2) >> 1;
                    elseif (($P * 8) % 8 == 7)
                        $COLOR[1] = ($COLOR[1] & 0x1);
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } else
                    return false;
                imagesetpixel($res, $X, $Y, $COLOR[1]);
                $X++;
                $P += $BMP['bytes_per_pixel'];
            }
            $Y--;
            $P += $BMP['decal'];
        }

        //Fermeture du fichier
        fclose($f1);

        return $res;
    }


    /*************************新的以图搜图**************************************/
    /*
	* 文件上传
	*/
    public function new_upload_file() {
        $this->load->library('POP_Uploadpic');

        $result = [];
        $file = $_FILES['userfile'];
        $fileInfo = getimagesize($file['tmp_name']);
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        // 如果是图像文件 检测文件格式
        if( in_array(strtolower($file['type']), ['image/gif','image/jpg','image/jpeg','image/bmp','image/png','image/swf']) && false === $fileInfo) {
            return json_encode(array('code' => 100, 'msg' => '非法图像文件'));
        }

        $width = $fileInfo[0];
        $heigth = $fileInfo[1];

        $fileSize = filesize($file['tmp_name']);

        if($fileSize > 4194304 || $width > 4096 || $heigth > 4096 ) {
            $msg = '抱歉，当前图片太大啦，换一张图试试吧！（图片不得大于4M或4096px）';
            $code = 1001;
            return json_encode(array('code' => $code, 'msg' => $msg));
        }

        $handle = fopen($file['tmp_name'], "rb");
        $fileStream = fread($handle, $fileSize);
        fclose($handle);
        $res= ['width'=>$width, 'height'=>$heigth, 'stream'=>$fileStream, 'ext'=>$fileExt];

        //上传
        $savePath = 'upload_pic_material/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';    //图片路径
        $body = [
            'fName' => md5(date('ymdHis') . rand(0, 10000)) . '.' . $res['ext'],
            'fTargetPath' => $savePath,
            'fStream' => $res['stream'],
            'fWidth' => $res['width'],
            'fHeight' => $res['height'],
            'fPrefix' => 'material',
            'allowSuffix' => implode(',', $this->allowed_types),
        ];
        $json = $this->pop_uploadpic->curlRequest($body);
        $jsonArr = json_decode($json, true);

        $result['code'] = $jsonArr['status'];
        if ($result['code'] == 1) {
            $path = $jsonArr['info'];
            $result['imgPath'] = urlencode($path);
            $result['code'] = 200;
            return json_encode($result);
        } else {
            // 失败
            // return $jsonArr['info'];
            $result['code'] = 100;
            $result['msg'] = 'error';
            return json_encode($result);
        }
    }

    /*
  * 切图处理
  */
    public function new_cut_pic($params = []) {
        $this->load->library('POP_Uploadpic');

        $source_image = str_replace('//', '/', $params['photo_url']);

        $cut_pos = explode(',', $params['cut_pos']);
        $dst_x = intval($cut_pos[0]);
        $dst_y = intval($cut_pos[1]);
        $dst_w = intval($cut_pos[2]);
        $dst_h = intval($cut_pos[3]);
        $cut_w = intval($cut_pos[4]);
        $cut_h = intval($cut_pos[5]);

        $body = [
            'src_image' => $source_image,
            'dst_x' => $dst_x,
            'dst_y' => $dst_y,
            'dst_w' => $dst_w,
            'dst_h' => $dst_h,
            'cut_w' => $cut_w,
            'cut_h' => $cut_h,
            'imgid' => date('YmdH'),
            'action' => 'cutPicDeal',
        ];

        $json = $this->pop_uploadpic->curlRequest($body);
        $jsonArr = json_decode($json, true);
        $result = [];
        if ($jsonArr['status'] == 1) {
            $sDesignAreaImg = $jsonArr['info'];
            $result['code'] = 200;
            //搜索结果页url
            $result['path'] = '/picmatch/similarpic/?path=' . urlencode($sDesignAreaImg) . '&original_img=' . $source_image;

        } else {
            $result['code'] = 100;
            $result['msg'] = $jsonArr['info'];
        }
        return $result;
    }

    //获取接口数据-
    public function get_interface_filter($path = '') {
        //图案内容一级标签
        $sPatternContents = GetCategory::getSomeAttr(25, '', false);
        $pattern_ids = [];
        foreach ($sPatternContents as $val) {
            // 图案内容一级标签设置了前台不显示
            if (!$val['iDisplay']) {
                continue;
            }
            $pattern_ids[$val['iAttributeId']] = $val['sName'];
        }


        $url = STATIC_URL1 . $path;
        $memKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_new_picmatch_' . md5($path);
        $result = $this->cache->memcached->get($memKey);
        $refresh = false;
        if (empty($result['pri_ids']) || empty($result['count']) || $this->input->get_post('refresh') || $refresh) {
            $response_data = $this->imgSearch->similarSearchByUrl($url, '', '1', '0', 500);
            //处理接口返回信息
            if (!empty($response_data['result'])) {
                foreach ($response_data['result'] as $k => $v) {
                    $pop_arr = json_decode($v['brief'], true);
                    $pri_ids[$pop_arr['pop_id']] = $v['score'];
                }
                $pop_ids = [];
                foreach ($pri_ids as $pop_id => $score) {
                    $t_id = explode('_', $pop_id);
                    $id = array_pop($t_id);
                    $t = implode('_', $t_id);
                    $pop_ids[$t][] = $id;
                }

                $result['pri_ids'] = $pri_ids;
                $result['pop_ids'] = $pop_ids;
                $result['count'] = $response_data['result_num'];

                //查询solr存在有数据的标签id
                $cond = $condition = [];
                foreach ($pop_ids as $table => $id_arr) {
                    $id_arr = array_filter($id_arr);
                    if (empty($table) || empty($id_arr)) {
                        continue;
                    }
                    $id_str = implode(' OR ', $id_arr);
                    if ($table != 'product_brochures_photo') {
                        $condition[] = "(tablename:{$table} AND pri_id:({$id_str}))";
                    }
                }
                if(!empty($condition)) {
                    $cond['other'][] = implode(' OR ', $condition);
                    $params['facet'] = 'true';
                    $params['facet.limit'] = -1;
                    $field = ['sCategory', 'sPatternContent'];
                    $params['facet.field'] = $field;
                    $params['raw'] = true;
                    // $_GET['debug_solr']=1;
                    $num = POPSearch::wrapQueryPopFashionMerger('', $cond, $res, 0, 1, [], $params);

                    foreach ($num['facet_counts']['facet_fields'] as $field => $value) {
                        if (is_array($value) && !empty($value)) {
                            $groupValue = $return = [];
                            foreach ($value as $key => $val) {
                                if (strpos($key, ',')) {
                                    foreach (explode(',', $key) as $k => $v) {
                                        if (!empty($v) && !empty($val) && !in_array($v, $groupValue)) {
                                            $name = GetCategory::getAttrNameById($v);
                                            $groupValue[$v] = !empty($name) ? $name : '';
                                        }

                                    }
                                } else {
                                    // id存在并且数量不为空
                                    if (!empty($key) && !empty($val) && !in_array($v, $groupValue)) {
                                        $name = GetCategory::getAttrNameById($key);
                                        $groupValue[$key] = !empty($name) ? $name : '';
                                    }
                                }
                            }
                        }

                        //有数据的标签id
                        $filters[$field] = array_filter($groupValue);

                    }
                }
                //只取图案内容一级标签
                $filters['sPatternContent'] = array_intersect_key($pattern_ids, $filters['sPatternContent']);
                $result['filters'] = $filters;
                $this->cache->memcached->save($memKey, $result, 28800);
            }
        }

        return $result;
    }

    /**
     * 相似图搜索
     * @param $path 图片路径
     * @param string $cat 筛选标签
     * @param int $page 页码
     * @param int $page_size 分页数
     * @return array    [总页数,列表数据]
     */
    public function image_search($path, $cat = '', $page = 1, $page_size = 60) {
        $return_data = [];


        $this->load->model(['category_model', 'details_model']);
        //调用百度智能云接口
        $result = $this->get_interface_filter($path);
        if (empty($result['pop_ids'])) {
            return [0, $return_data];
        }

        //有数据的标签项
        $filters = isset($result['filters']) ? $result['filters'] : [];
        $all_filters = $filters['sCategory'] + $filters['sPatternContent'];

        //获取列表数据
        $pop_ids = isset($result['pop_ids']) && is_array($result['pop_ids']) ? $result['pop_ids'] : [];
        $cond = $condition = $brochures_arr = [];
//        $pop_ids = [
//            'product_brochures_photo'=>[2816702,2816701,2816700,2816699,2816698],
//            'product_perform'=>[1989631,1989631],
//            'product_streetitem'=>[1786,1790],
//        ];

        foreach ($pop_ids as $table => $id_arr) {
            $id_arr = array_filter(array_unique($id_arr));
            if (empty($table) || empty($id_arr)) {
                continue;
            }
            if ($table == 'product_brochures_photo') {//广告大片
                $brochures_arr = $id_arr;
            } else {
                $id_str = implode(' OR ', $id_arr);
                $condition[] = "(tablename:{$table} AND pri_id:({$id_str}))";
            }
        }


        $select = !empty($condition) ? implode(' OR ', $condition) : '';
        if (!empty($cat) && (array_key_exists($cat, $all_filters) || in_array($cat, [4, 9]))) {//筛选标签查询
            if (in_array($cat, [4, 9])) {
                $select = "(" . implode(' OR ', $condition) . ") AND (iColumnId:{$cat})";
            } else {
                $select = "(" . implode(' OR ', $condition) . ") AND (aLabelIds:{$cat})";
            }
            //如果选了标签 广告大片数据不显示
            $brochures_arr = [];
        }
        if($select) {
            $cond['other'][] = $select;
        }

        $param_str = !empty($cond) ? http_build_query($cond) : '';
        $memKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_new_picmatch_solr_data_' . md5($path . $param_str);
        $res = $this->cache->memcached->get($memKey);
//        $_GET['debug_solr'] =1;
        $refresh = $_GET['refresh'] ? $_GET['refresh'] : false;
        if ($refresh || empty($res)) {
            if (!empty($brochures_arr)) {
                $brochures_res = OpPopFashionMerger::getProductData($brochures_arr, 'product_brochures_photo');
                $brochures_res = array_filter($brochures_res);
                foreach ($brochures_res as &$val) {
                    if (!empty($val)) {
                        $id = $val['id'];
                        $tableName = 'product_brochures_photo';
                        $pop_id = $tableName . '_' . $id;
                        $val['score'] = isset($result['pri_ids'][$pop_id]) ? $result['pri_ids'][$pop_id] : 0;
                        $val['tablename'] = $tableName;
                    }
                }
            }

            if (!empty($cond)) {
                $total = POPSearch::wrapQueryPopFashionMerger('', $cond, $res, 0, 500);

                foreach ($res as &$val) {
                    $id = $val['pri_id'];
                    $tableName = $val['tablename'];
                    $pop_id = $tableName . '_' . $id;
                    $val['score'] = isset($result['pri_ids'][$pop_id]) ? $result['pri_ids'][$pop_id] : 0;
                }
            }
            if (!empty($brochures_res)) {
                $res = array_merge($brochures_res, $res);
            }

            $res = twoDimensionSort($res, 'score');
            $this->cache->memcached->save($memKey, $res, 28800);
        }

        //无数据
        $total = count($res);
        if (!$total) {
            return [0, $return_data];
        }

        //总页数
        $total_page = ceil($total / $page_size);
        //每次处理一页的数量
        $offset = ($page - 1) * $page_size;
        $res = array_slice($res, $offset, $page_size);
        $return = [];

        //处理数据
        foreach ($res as $key => $val) {
            $info = [];
            $tableName = $val['tablename'];
            if ($tableName == 'product_brochures_photo') {//广告大片
                $info_p = OpPopFashionMerger::getProductData($val['borochid'], 'product_brochures');
                $info_p = $info_p[$val['borochid']];

                $id = $val['id'];
                $pop_id = $tableName . '_' . $id;
                $info['score'] = isset($result['pri_ids'][$pop_id]) ? $result['pri_ids'][$pop_id] : 0;
                $info['id'] = $id;
                $info['col'] = 71;
                $info['table'] = 'product_brochures_photo';
                $info['cover'] = getStaticUrl($path) . getFixedImgPath($val['pic']);// 图片路径
                //标题
                $info['label'] = $info_p['name_text'] ? $info_p['name_text'] : '';
                $info['detail_link'] = '/details/bookpresscon/t_brochuresphoto-id_' . $id . '-col_71/';

            } else {//款式和图案
                $id = $val['pri_id'];
                $col_pid = $val['iColumnId'][0];
                $col = array_pop($val['iColumnId']);

                $productData = OpPopFashionMerger::getProductData($id, $tableName);
                $productData = $productData[$id];
                if (!$productData) {
                    continue;
                }
                $pop_id = $tableName . '_' . $id;
                $info['score'] = isset($result['pri_ids'][$pop_id]) ? $result['pri_ids'][$pop_id] : 0;
                $info['id'] = $id;
                $info['col'] = $col;
                $info['table'] = getProductTableName($tableName);
                $imgPath = getImagePath($tableName, $productData);// 图片路径
                $info['cover'] = getFixedImgPath($imgPath['cover']);
                $info['detail_link'] = '/details/style/t_' . $info['table'] . '-id_' . $id . '-col_' . $col . '/';
                if ($col_pid == 4) {
                    $labels = $this->details_model->getPicInfo($id, $tableName, '', $col_pid);
                    //季节 品牌
                    $info['label'] = [$labels['iSeason_text'], $labels['brand_name']];
                    $info['label'] = implode(' ', $info['label']);
                }
                if ($col_pid == 9) {
                    $labels = [];
                    // 图案内容
                    $aPatternContent = explode(',', $productData['sPatternContent']);
                    // 格式--矢量/位图
                    $aFormat = explode(',', $productData['sFormat']);
                    // 图案内容
                    if ($aPatternContent) {
                        $aPatternContent = array_filter($aPatternContent);
                        foreach ($aPatternContent as $_id) {
                            $_aPatternContents = $this->category_model->getAll('sPatternContent');
                            $_name = $_aPatternContents[$_id];
                            if (empty($_name) || $_name == '其他') continue;
                            // $labels[$_id][] = $_name;
                            $labels[] = $_name;
                        }

                    }
                    // 矢量/位图
                    if ($aFormat) {
                        $aFormat = array_filter($aFormat);
                        $aFormatList = $this->category_model->getAll('sPatternFormat');
                        foreach ($aFormat as $_id) {
                            if ($aFormatList && isset($aFormatList[$_id])) {
                                $_name = $aFormatList[$_id];
                            } else {
                                continue;
                            }
                            $labels[] = $_name;
                        }
                    }
                    $info['label'] = implode(' ', $labels);

                }
            }

            $return[$pop_id] = $info;
        }

        $return = !empty($return) ? array_values($return) : [];
        $data = ['filters' => $filters, 'total_page' => $total_page, 'total_num' => $total];
        return [$data, $return];
    }


}
