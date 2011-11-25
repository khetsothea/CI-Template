<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{
	public function __construct($user_id = null)
	{
		parent::__construct();
		
		$db_table = 'users';
		
		$fields = array();
		$fields['username'] = null;
		$fields['email'] = null;
		$fields['password'] = null;
		$fields['display_name'] = null;
		$fields['full_name'] = null;
		$fields['admin'] = 0;
		
		parent::set_params($db_table, $fields);
		
		if ( ! is_null($user_id)) {
			$this->find($user_id); }
	}
	
	
	
	public function authenticate($password)
	{
		$CI = get_instance();
		$CI->load->library('bcrypt', $CI->config->item('bcrypt_rounds'));
		
		if ($CI->bcrypt->verify($password, $this->password))
		{
			return true;
		}
		else
		{
			throw new Exception('Your Username / Password is wrong.', 401);
		}
	}
	
}

/* End of file user_model.php */
/* Location: ./app/models/user_model.php */