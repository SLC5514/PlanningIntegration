<?php
/**
 * Created by PhpStorm.
 * User: jiangwei
 * Date: 2019/11/12
 * Time: 10:09
 * 北服免登陆
 */

class Freelogin extends POP_Controller
{
    public function index($encrypt_user_id)
    {
        //在北服IP范围内的，默认跳转至北服
        if(empty($encrypt_user_id) && SsoMember::isAllowIp($this->input->ip_address(), SsoMember::$allow_ips['1524574'])){
             header('Location:/interface/freelogin/index/mHXRhsVF1WmUDgnSrzBFAg==');
        }
        $user_id = $this->decrypt($encrypt_user_id);
        $allow_ips = isset(SsoMember::$allow_ips[$user_id]) ? SsoMember::$allow_ips[$user_id] : [];
        if (!SsoMember::isAllowIp($this->input->ip_address(), $allow_ips)) {
            show_404();
            exit;
        }
        $child_user_id = '';
        $this->assign('encrypt_user_id', $encrypt_user_id);
        $this->assign('child_user_id', $child_user_id);
        $this->display('free_login/free_login.html');
    }

    private function decrypt($encrypt_user_id)
    {
        $vi = substr(POP_GLOBAL_KEYS, 0, 16);
        return openssl_decrypt(base64_decode($encrypt_user_id), 'AES-128-CBC', POP_GLOBAL_KEYS, OPENSSL_RAW_DATA, $vi);
    }

    public function encrypt($id)
    {
        $vi = substr(POP_GLOBAL_KEYS, 0, 16);
        echo  base64_encode(openssl_encrypt($id, 'AES-128-CBC', POP_GLOBAL_KEYS, OPENSSL_RAW_DATA, $vi));
    }
}