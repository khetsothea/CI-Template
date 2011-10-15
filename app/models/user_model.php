<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		
		$db_table = 'users';
		
		$fields = array();
		$fields['username'] = null;
		$fields['email'] = null;
		$fields['password'] = null;
		$fields['display_name'] = null;
		$fields['full_name'] = null;
		
		parent::set_params($db_table, $fields);
	}
	
}