<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16
 * Time: 11:14
 */

class Download_model extends POP_Model
{
    /**
     * 获取图案素材的下载格式
     */
    public function getPicType($id,$table){
        $this->load->model('PatternLibrary_model');
        //$column    = intval($this->input->post('col', TRUE)); // 栏目id
       // $column = 82;
        //$index = intval($this->input->post('index'));

        // 将table转为真实表名
        // $realTable    = getProductTableName($table);
        $detailResult = OpPopFashionMerger::getProductData($id, $table);
        // 数据不存在
        if (!$table || !$id  || empty($detailResult)) {
            $this->input->is_ajax_request() ? exit(json_encode(['success' => 0, 'code' => 1, 'msg' => 'xx'])) : show_404();
        }

        // 获取详情数据
        $tableInfo = $this->getPicInfo($id, $table, '', '', '0', $detailResult);

        $downloadType = [];
        // 获取款式模板|图案素材的下载格式
        // if (in_array($column, [80, 82, 117, 120])) {
            $downloadType = $this->PatternLibrary_model->getMaterialDown($tableInfo, $table);
        // } else {
        //     $details = $tableInfo['detail_img'];
        //     if (!empty($details)) {
        //         foreach ($details as $dk => $detail) {
        //             $realImgLoc = rtrim(FCPATH, '/') . parse_url($detail['bigPath'], PHP_URL_PATH);
        //             if (getenv('BASEENV') == false) {
        //                 $imgInfo = ['type' => '--', 'size' => '--'];
        //             } else {
        //                 $imgInfo = api_getPicSize($realImgLoc);
        //             }
        //             $details[$dk]['shape'] = $imgInfo['type'];
        //             $details[$dk]['size']  = $imgInfo['size'];
        //         }
        //     }
        //     $tableInfo['detail_img'] = $details;
        // }
        // var_dump($downloadType);die;
        return $downloadType;
    }


    // 获取面料图库细节图
    public function getFabricgalleryDetailImage($tablename, $related_flag, $id, $columnid)
    {
        $sql = "SELECT * FROM $tablename WHERE related_flag=? order by id asc";
        $result = $this->query($sql, [$related_flag]);
        $path = [];
        $img = [];
        $simpleName = getProductTableName($tablename);
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                $path = getImagePath($tablename, $val);
                $img[$key]['smallPath'] = $path['smallPath'];
                $img[$key]['bigPath'] = $path['bigPath'];
                $img[$key]['simpleName'] = $simpleName;
                $img[$key]['id'] = $id;
                $img[$key]['columnid'] = $columnid;
            }
        }
        return $img;
    }
    /**
     *  单张图片详情页获取图片详情
     *
     */
    public function getPicInfo($id, $tablename, $params, $columnid, $index = 0, &$_info = [], $columnPid = 0)
    {
        if (empty($id) || empty($tablename)) return [];

        if (empty($_info)) {
            $_info = OpPopFashionMerger::getProductData($id, $tablename);
        }

        // $this->_getLabelsInfo($tablename, $id, $params, $columnid, $_info);

        $info = $_info[$id];
        $tablename = strtolower($tablename);

        switch ($tablename) {
            case 'product_graphicitem'://图案花型

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                if ($path['detailImg']) { //新数据
                    foreach ($path['detailImg'] as $key => $_detailpath) {
                        $info['detail_img'][$key]['smallPath'] = $_detailpath['smallPath'];
                        $info['detail_img'][$key]['bigPath'] = $_detailpath['bigPath'];
                        $info['detail_img'][$key]['simpleName'] = $simpleName;
                        $info['detail_img'][$key]['id'] = $id;
                        $info['detail_img'][$key]['columnid'] = $columnid;
                    }
                } else { //老数据
                    $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                    $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                    $info['detail_img'][0]['simpleName'] = $simpleName;
                    $info['detail_img'][0]['id'] = $id;
                    $info['detail_img'][0]['columnid'] = $columnid;
                }

                break;
            case 'product_fabricgallery_item'://面料图库

                if ($columnid == 117) {

                    //图片信息
                    $path = getImagePath($tablename, $info);
                    $simpleName = getProductTableName($tablename);
                    $tablename_detail = 'product_fabricgallery_item_detail';
                    $info['detail_img'] = $this->getFabricgalleryDetailImage($tablename_detail, $info['related_flag'], $id, $columnid);
                    if (empty($info['detail_img'])) {
                        $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                        $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                        $info['detail_img'][0]['simpleName'] = $simpleName;
                        $info['detail_img'][0]['id'] = $id;
                        $info['detail_img'][0]['columnid'] = $columnid;
                    }
                    break;
                }


        }

        return $info;
    }


}