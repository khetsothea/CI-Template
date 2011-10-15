<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Base controller stuff
class Base_Controller extends CI_Controller
{
	public $data = array(); // Holds all data sent to views etc
	
	public function __construct()
	{
		parent::__construct();
		
		$this->data['app_name'] = $this->config->item('app_name');
		$this->data['site_title'] = $this->config->item('site_title');
	}
}

// Front end site stuff
class Frontend_Controller extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
}

// Logged in app stuff
class App_Controller extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->check_user_logged_in();
	}
	
	public function check_user_logged_in()
	{
		
	}
}

// Admin area stuff
class Admin_Controller extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
}