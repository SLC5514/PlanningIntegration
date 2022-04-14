<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/Smarty/Smarty.class.php';

/**
 * Smarty Class
 *
 */
class Pop_smarty extends Smarty
{

    public function __construct($params = [])
    {
        // parent::__construct();
        $this->initialize($params);
    }

    /**
     * Initialize Preferences
     *
     * @param    array $params Initialization parameters
     * @return Pop_smarty
     */
    public function initialize(array $params = [])
    {
        foreach ($params as $key => $val) {
            $_key = substr($key, strpos($key, '_') + 1);
            $this->$_key = $val;
        }

        return $this;
    }
}