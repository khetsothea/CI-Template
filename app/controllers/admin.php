<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

	public function index()
	{
		$this->template->write_view('content', 'admin/index', $this->data);
		$this->template->render();
	}

}

/* End of file admin.php */
/* Location: ./app/controllers/admin.php */