<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Probation 领取免费试用
 */
class Probation extends POP_Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function completed()
    {
        $this->display('free_probation_complete.html');
    }




}