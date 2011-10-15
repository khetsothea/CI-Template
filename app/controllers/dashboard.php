<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Frontend_Controller {

	public function index()
	{
		$this->template->write_view('content', 'dashboard/index', $this->data);
		$this->template->render();
	}
	
	public function profile($user_id)
	{
		try{
			$user = new User_model($user_id);
		}
		catch (UserException $e) {
			$this->session->set_flashdata('error', $e->getMessage());
			redirect('/');
			exit;
		}
		
		echo $user->display_name;
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */