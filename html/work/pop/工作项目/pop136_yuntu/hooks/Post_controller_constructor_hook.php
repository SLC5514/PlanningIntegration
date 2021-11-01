<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo    在你的控制器实例化之后立即执行，控制器的任何方法都还尚未调用。
 */
class Post_controller_constructor_hook extends POP_Controller
{

    public function __construct()
    {
        log_message('info', 'Hi, My name is Post_controller_constructor_hook!');
        $this->ci = &get_instance();
    }

    public function index()
    {

        //屏蔽控制器或方法名 全小写
        $aShieldControllers = ['api', 'looprequire'];

        //控制器
        $class = $this->ci->router->fetch_class();

        //方法名
        $method = $this->ci->router->fetch_method();

        //该控制器在所屏蔽数组中默认跳过
        if (false !== array_search(strtolower($class), $aShieldControllers)) {
            return;
        }

        //赋值token用于轮询
        //$ci = &get_instance();
        $this->ci->load->driver('cache');
        $userId = getUserId();
        $time = time();
        if (!empty($userId)) {
            $token = getToken($userId, $time);
            $this->ci->assign("loopToken", $token);
            $this->ci->assign("loopTime", $time);
        }

        //更新用户操作时间
        $UID = $this->ci->input->cookie("POP_UID");
        if (!empty($UID)) {
            $memcacheKey = UID_MEMKEY_PRE . $UID;
            $timeOut = USER_TIME_OUT;
            $time = $this->ci->cache->memcached->get($memcacheKey);
            if ($time && $time + $timeOut >= time()) {
                //未超时更新用户操作时间
                $this->ci->cache->memcached->save($memcacheKey, time(), UID_MEMKEY_TIME);
            } elseif ($time && $time + $timeOut < time()) {
                //超时自动删除缓存
                $this->ci->cache->memcached->delete($memcacheKey);
            }
        }
    }

}