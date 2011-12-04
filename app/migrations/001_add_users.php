<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'BIGINT',
				'constraint' => 20,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'username' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'password' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'display_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE
			),
			'full_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE
			),
			'admin' => array(
				'type' => 'TINYINT',
				'constraint' => '4',
				'default' => 0
			),
		));
		
		// Add primary key
		$this->dbforge->add_key('id', TRUE);
		
		$this->dbforge->create_table('users');
	}

	public function down()
	{
		$this->dbforge->drop_table('users');
	}

}