<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends App_Controller {

	public function index()
	{
		$this->template->write_view('content', 'dashboard/index', $this->data);
		$this->template->render();
	}
	
	public function login()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() === false)
		{
			$this->render();
		}
		else
		{
			// Lets try and log the user in
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			try{
				$users = $this->User_model->find_where(array('username' => $username, 'limit' => 1));
				$user = reset($users);
				
				$user->authenticate($password);
				
				// set sessions
				$this->session->set_userdata('user_id', $user->id);
				
				// Redirect the user to where the tried to get to before logging in
				$redirect_url = 'dashboard';
				
				if ($this->session->userdata('redirect_url')) {
					$redirect_url = $this->session->userdata('redirect_url');
					$this->session->unset_userdata('redirect_url');
				}
				
				redirect($redirect_url);
			}
			catch (UserException $e) {
				$this->session->set_flashdata('error', $e->getMessage());
				redirect('/login');
			}
			
		}
	}
	
	public function logout()
	{
		// Destroy the session
		$this->session->sess_destroy();
		
		// Redirect back to homepage
		redirect('/');
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
		
		echo $user->username;
	}
}

/* End of file dashboard.php */
/* Location: ./app/controllers/dashboard.php */