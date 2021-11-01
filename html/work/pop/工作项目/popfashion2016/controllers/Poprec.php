<?php

/**
 * Author: jiangwei
 * DateTime: 2019/6/25 17:48
 * POP推荐，实际是广告类，url不能带Ad防止过滤
 */
class Poprec extends POP_Controller
{
    //预处理所有的头部广告
    //(PS:暂时废弃，运行时间过长,nginx主动断开，将运行不完，脚本中有分条URL预处理)
    private function initTopAd()
    {
        $this->load->model('Ad_model');
        $genders = array(0, 1, 2, 5);
        $industrys = array(0, 6, 7, 8, 9, 10, 11, 12, 159);
        $regions = array(0, 272, 341, 335, 323);
        $columns = array(
            82, 120, 4, 50, 51, 52, 53, 54, 55, 56, 57, 122, 123, 1, 2, 20, 21, 125, 126, 127, 128, 129, 30, 31, 32, 33, 34, 35, 38, 3, 70, 71, 72, 115, 132
        );
        $params = array();
        foreach ($genders as $gender) {
            $param = array();
            $param['gen'] = $gender;
            foreach ($industrys as $industry) {
                $param['ind'] = $industry;
                $params[] = $param;
            }
        }
        foreach ($columns as $column) {
            foreach ($params as $param) {
                $paramStr = $this->common_model->parseParams($param, 2);
                $this->Ad_model->getColumnTopAd($column, $paramStr);
                if ($column == 126) {
                    $this->Ad_model->getColumnTopAd($column, $paramStr . "-stp_1");
                }
                if ($column == 3) {
                    foreach ($regions as $region) {
                        $_paramStr = $paramStr . '-reg_' . $region;
                        $this->Ad_model->getColumnTopAd($column, $_paramStr);
                        $this->Ad_model->getColumnTopAd($column, $_paramStr . "-typ_tshowpic");
                    }
                }
            }
        }
    }
    //获取头部广告
    public function top()
    {
        $this->load->model('Ad_model');
        $column = $this->input->get_post("col");
        $params = $this->input->get_post("params");
        $data = $this->Ad_model->getColumnTopAd($column, $params);
        $result = ["code" => 0, "msg" => "ok", "data" => $data, "total" => count($data)];
        echo json_encode($result);
    }
}