<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends Frontend_Controller {

	public function fourohfour()
	{
		$this->template->write_view('content', 'site/fourohfour', $this->data);
		$this->template->render();
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */