<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration extends CI_Controller {

	public function current()
	{
		$this->load->library('migration');
		
		if ( ! $this->migration->current())
		{
			show_error($this->migration->error_string());
		}
		else
		{
			$this->session->set_flashdata('success', 'Migration successful');
			redirect('/');
		}
	}

}

/* End of file migration.php */
/* Location: ./app/controllers/migration.php */