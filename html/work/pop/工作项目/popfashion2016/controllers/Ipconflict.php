<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * IP冲突提示页
 */
class Ipconflict extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function Checkipconflit() {
		$this->load->model('member_model');
		$this->member_model->checkIpConflit();
	}

	public function Ipconflict( $params )
	{
		$ip = str_replace('ip_', '', $params );
		$backurl = $this->input->get( 'backurl' );

		if( $this->input->cookie('user_info') ) {
			$this->load->helper('url');
			redirect( $backurl );
		}
		$this->smarty->assign('Ip', $ip ? '(<em>IP:'.htmlspecialchars( $ip ).'</em>)' : '' );
		$this->smarty->assign('title', '账号IP冲突-POP服装趋势网');
		$this->smarty->assign('keywords', '账号IP冲突');
		$this->smarty->assign('description', '账号IP冲突');
		$this->smarty->display( 'errors/html/Ipconflict.html' );
	}
}