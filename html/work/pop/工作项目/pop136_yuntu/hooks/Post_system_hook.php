<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @todo	在最终的页面发送到浏览器之后、在系统的最后期被调用。
 */
				
class Post_system_hook
{


	public function __construct()
	{
		log_message('info', 'Hi, My name is Post_system_hook!');
	}

	public function index()
	{
		
	}
}