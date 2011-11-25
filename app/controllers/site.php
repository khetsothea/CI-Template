<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends Frontend_Controller {
	
	protected $CI; // Holds the CI instance
	
	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}
	
	public function fourohfour()
	{
		$this->CI->template->write_view('content', 'site/fourohfour', $this->data);
		$this->CI->template->render();
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */