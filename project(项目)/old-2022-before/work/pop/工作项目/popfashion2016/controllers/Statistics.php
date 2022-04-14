<?php
/*
 * 统计相关
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('statistics_model');
    }

    /*
      + 统计广告点击量
      + link :/statistics/link/?url=base64_encode( $aid ).'_'.base64_encode( url );
      +
    */
    public function link()
    {
        $url = $this->input->get('url');
        $aUrl = explode('_', $url);
        $iAId = base64_decode($aUrl[0]); //广告id
        $sAdUrl = base64_decode($aUrl[1]); //广告链接
        // $sAdUrl = $this->details_model->getVedioPath($sAdUrl);// https与http兼容,固定连接不管
        if (intval($iAId) > 0) {
            $this->statistics_model->setAdsHits($iAId);
        }
        $this->load->helper('url');
        redirect($sAdUrl, 'location', 302);
    }

    /*
	  + 统计广告点击量
	  + link :/statistics/vendoradlink/?url=base64_encode( $id ).'_'.base64_encode( $url );
	  +
	*/
    public function vendorAdLink()
    {
        $url = $this->input->get('url');
        $aUrl = explode('_', $url);
        $iAId = base64_decode($aUrl[0]); //广告id
        $sAdUrl = base64_decode($aUrl[1]); //广告链接
        if (intval($iAId) > 0) {
            $this->statistics_model->vendorAdClick($iAId);
        }
        $this->load->helper('url');
        redirect($sAdUrl, 'location', 302);
    }


    /*
      + 统计韩国东大门资料下载量
      + link :/statistics/link/?url=base64_encode( $aid ).'_'.base64_encode( url );
      +
    */
    public function dongdaemun()
    {
        $url = $this->input->get('url');
        $aUrl = explode('_', $url);
        $id = base64_decode($aUrl[0]);
        $sUrl = base64_decode($aUrl[1]);
        if (intval($id) > 0) {
            $this->statistics_model->setDongdaemunDownloadNum($id);
        }
        $this->load->helper('url');
        redirect($sUrl, 'location', 302);
    }


    /**
     * 临时处理2018-08-03
     *
     * @param string $id
     */
    public function url($id = '')
    {
        $this->load->helper('url');

        $a = [
            substr(md5('/details/report/t_report-id_4603-col_125/'), 8, 16) => '/details/report/t_report-id_4603-col_125/',
            substr(md5('/details/report/t_report-id_4152-col_125/'), 8, 16) => '/details/report/t_report-id_4152-col_125/',
            substr(md5('/details/report/t_report-id_4189-col_125/'), 8, 16) => '/details/report/t_report-id_4189-col_125/',
        ];

        if (isset($a[$id])) {

            $key = 'url_statistics:' . $a[$id];

            $redis = new Redis();

            if (ENVIRONMENT == 'production') {
                $redis->connect('192.168.0.156', 6379);
            } else {
                $redis->connect('192.168.3.253', 6379);
            }

            $redis->select(15);
            $redis->incr($key);
            $redis->close();

            redirect($a[$id]);
        } else {
            redirect('/');
        }
    }

    public function test()
    {
        if (ENVIRONMENT == 'production') {
            die();
        }

        echo config_item('base_url') . 'statistics/url/' . substr(md5('/details/report/t_report-id_4603-col_125/'), 8, 16) . PHP_EOL;
        echo config_item('base_url') . 'statistics/url/' . substr(md5('/details/report/t_report-id_4152-col_125/'), 8, 16) . PHP_EOL;
        echo config_item('base_url') . 'statistics/url/' . substr(md5('/details/report/t_report-id_4189-col_125/'), 8, 16) . PHP_EOL;
    }


}