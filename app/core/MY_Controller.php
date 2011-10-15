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
	
	protected function check_user_logged_in()
	{
		$user_id = $this->session->userdata('user_id');
		
		if ( ! empty($user_id) && is_numeric($user_id))
		{
			try{
				$this->data['user'] = new User_model($user_id);
			}
			catch (UserException $e) {
				$this->session->set_flashdata('error', $e->getMessage());
				redirect('/');
				exit;
			}
		}
		else
		{
			// User not logged in
			$current_class = $this->router->fetch_class();
			$current_method = $this->router->fetch_method();
			$public_uris = $this->config->item('public_uris');
			
			if (in_array($current_class, $public_uris)) {
				return true; }
			elseif (in_array($current_class.'/'.$current_method, $public_uris)) {
				return true; }
			
			// Set a session for where the user was trying to access before the login redirect
			$this->session->set_userdata('redirect_url', current_url());
			
			// User not supposed to be here, put them to the login screen.
			redirect('/login');
		}
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
	public $user = null; // The current users object
	
	public function __construct()
	{
		parent::__construct();
		
		$this->check_user_logged_in();
	}
}

// Admin area stuff
class Admin_Controller extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->check_user_logged_in();
		
		$this->check_user_is_admin();
	}
	
	private function check_user_is_admin()
	{
		if ($this->data['user']->admin != 1)
		{
			// User not supposed to be here, put them to the dashboard.
			$this->session->set_flashdata('error', 'You are not an admin, don\'t be silly.');
			redirect('/dashboard');
		}
		else
		{
			return true;
		}
	}

}

/* End of file MY_Controller.php */
/* Location: ./app/controllers/MY_Controller.php */