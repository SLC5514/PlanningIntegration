<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @date 2017.05.12
 * @todo 软件，暂时作为浏览器不兼容时的提示页
 */
class Software extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->browser();
    }

    public function browser()
    {
        $this->assign('title', '升级浏览器-POP服装趋势网');
        $this->assign('keywords', '浏览器版本');
        $this->assign('description', '浏览器版本低，请升级浏览器');
        $this->display('software/browser.html');
    }
}