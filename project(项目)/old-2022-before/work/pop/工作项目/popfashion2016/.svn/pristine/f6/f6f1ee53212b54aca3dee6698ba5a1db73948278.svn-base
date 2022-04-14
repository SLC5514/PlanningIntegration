<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/Smarty/Smarty.class.php';
/**
 * Smarty Class
 * 
 */
class Pop_smarty extends Smarty {

	public function __construct($params = array())
	{
		$this->initialize($params);
		log_message('info', 'Pop_smarty Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @param	array	$params	Initialization parameters
	 * @return	CI_PopSmarty
	 */
	public function initialize(array $params = array())
	{
		foreach ($params as $key => $val)
		{
			$_key = substr($key, strpos($key,'_')+1);
			$this->$_key = $val;
			// smarty本身的限制
			/*if (property_exists($this, $_key))
			{
				$this->$_key = $val;
			}*/
		}

		return $this;
	}
}