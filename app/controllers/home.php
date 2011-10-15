<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Frontend_Controller {

	public function index()
	{
		$this->template->write_view('content', 'home/index', $this->data);
		$this->template->render();
	}
	
	public function register()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('email', 'Email address', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|matches[conf_password]');
		$this->form_validation->set_rules('conf_password', 'Confirm Password');
		
		if ($this->form_validation->run() === false)
		{
			$this->template->write_view('content', 'home/register', $this->data);
			$this->template->render();
		}
		else
		{
			// Form validation passed
			$user = new User_model();
			$this->load->library('bcrypt', $this->config->item('bcrypt_rounds'));
			
			try{
				
				// Set the users details
				$user->username = $this->input->post('username');
				$user->email = $this->input->post('email');
				$user->password = $this->bcrypt->hash($this->input->post('password'));
				
				// Save the user
				$user->add();
				
				$this->session->set_flashdata('success', 'Your user has been created');
				redirect('/home');
				exit;
			}
			catch (Exception $e){
				$this->session->set_flashdata('error', $e->getMessage());
				redirect(current_url());
				exit;
			}
		}
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */