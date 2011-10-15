<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Frontend_Controller {

	public function index()
	{
		$this->template->write_view('content', 'home/index', $this->data);
		$this->template->render();
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */