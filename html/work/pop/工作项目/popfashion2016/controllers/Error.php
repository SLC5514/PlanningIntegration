<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
  * @todo 错误页面
*/
class Error extends POP_Controller
{
	public function __construct() {
		parent::__construct();
	}
		
	public function index()
	{
		$this -> assign('title', '出错啦-POP服装趋势网');
		$this -> assign('keywords', 'error,404');
		$this -> assign('description', '您访问的页面出错！您可以稍后刷新或者返回网站首页');
		$this->display('errors/error.html');
	}
}