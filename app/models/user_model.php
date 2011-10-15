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
		
		if ( ! is_null($user_id))
		{
			try {
				$this->find($user_id);
			}
			catch (Exception $e) {
				throw new UserException($e->getMessage(), $e->getCode());
			}
		}
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
			throw new UserException('password_mismatch');
		}
	}
	
}

// Custom Exceptions
class UserException extends Exception
{
	public static function get_error_message($err_code)
	{
		switch ($err_code)
		{
			case 'not_found':
				return 'No User found with that ID';
			break;
			
			case 'multiple_matches':
				return 'Multiple rows found, this is not a primary key.';
			break;
			
			case 'password_mismatch':
				return 'Your Username / Password is wrong.';
			break;
			
			default:
				return 'Unknown User Error';
			break;
		}
	}
	
	public function __construct($err_msg, $err_code = null)
	{
		// Pass the error message and error code to php Exception class
		parent::__construct(UserException::get_error_message($err_msg), $err_code);
		// Log the message with CI
		log_message('info', UserException::get_error_message($err_msg));
	}
}

/* End of file user_model.php */
/* Location: ./app/models/user_model.php */