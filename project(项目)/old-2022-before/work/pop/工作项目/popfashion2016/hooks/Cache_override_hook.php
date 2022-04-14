<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 使用你自己的方法来替代 输出类 中的 _display_cache() 方法，这让你有自己的缓存显示机制。
 */
class Cache_override_hook
{

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        log_message('info', 'Hi, My name is Cache_override_hook!');
    }

    public function index()
    {

    }
}