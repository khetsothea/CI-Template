<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	protected 	$db_table = NULL;     // the name of the database table the model uses
	protected 	$primary_key = NULL;  // the column name of the primary key
	protected 	$fields = array();	  // an array to store the single value variables for the model
	public 	    $id = NULL;           // the ID for the object
	
	
	
	public function __construct()
	{
		parent::__construct();
	}



	/**
	*	set_params
	*
	*	Sets up MY_Model with table name, field list and primary key column
	*
	*	@access public
	*	@return none
	*/
	protected function set_params($db_table, $fields, $primary_key = 'id')
	{
		if( ! $db_table || ! $fields )
			throw new Exception('Missing model setup parameters', 500);
		
		// Set name of database table
		$this->db_table = $db_table;

		// Set the primary key column of the table
		$this->primary_key = $primary_key;
		
		// set fields array
		$this->fields = $fields;
	}



	/**
	*	__set
	*
	*	Check if you can set a property
	*
	*	@access public
	*	@return BOOL
	*/
	public function __set($name, $value)
	{
		if (array_key_exists($name, $this->fields)) {
			$this->$name = $value; }
		else {
			throw new Exception('You cannot set a property that does not exist in the defined fields for this model. '.$name, 500); }
	}



	/**
	*   We cant this anymore because of how codeignter loads $this->db. :(
	*	__get
	*
	*	Check if a property exists in the fields and return default value
	*
	*	@access public
	*	@return BOOL
	*
	public function __get($name)
	{
		if (array_key_exists($name, $this->fields)) {
			return $this->fields[$name]; }
		else {
			throw new Exception('You cannot get a property that does not exist in the defined fields for this model. '.$name, 104); }
	}*/



	/**
    *	populate
    *
    *	Populates an object with the results from the database
    *
    *	@access protected
    *	@return none
    */
	protected function populate($row)
	{
		$pri_key = $this->primary_key;
		
		// Set the id of the object
		$this->id= $row->$pri_key;
		
		foreach ($this->fields as $key => $value)
		{
			if (isset($row->$key)) {
				$this->$key = $row->$key; }
			else {
				$this->$key = $value; }
		}
	}



	/**
    *	add
    *
    *	Adds an object to the database
    *
    *	@access public
    *	@return BOOL
    */
	public function add()
	{
		$insert_array = array();
		
		// Check any empty fields are set as NULL
		foreach ( $this->fields as $key => $value )
		{
			if (isset($this->$key)) { $value = $this->$key; }
			if (trim($value) == '') { $value = NULL; }
			
			$insert_array[$key] = $value;
		}
		
		// insert array to db
    	if ($this->db->insert($this->db_table, $insert_array))
    	{
			// data inserted, lets return the new id
    		$insert_id = $this->db->insert_id();

    		$this->id = $insert_id;

    		return $insert_id;	// return the insert id
    	}
    	else
    	{
    		throw new Exception('Record could not be added.', 500);
    	}
	}

	/**
    *	update
    *
    *	Updates object properties
    *
    *	@access public
    *	@return BOOL
    */
	public function update()
	{
    	if ( ! $this->id ) {	// Check the calling object has an id (is not empty)
    		return FALSE; }			// If no id, fail
		
		$update_array = array();
		
		// Check any empty fields are set as NULL
		foreach ( $this->fields as $key => $value )
		{
			if (isset($this->$key)) { $value = $this->$key; }
			if (trim($value) == '') { $value = NULL; }
			
			$update_array[$key] = $value;
		}
		
		$this->db->where( $this->primary_key, $this->id );
    	return $this->db->update($this->db_table, $update_array);
	}

	/**
    *	delete
    *
    *	Deletes an object from the database
    *
    *	@access public
    *	@return BOOL
    */
	public function delete()
	{
		if ($this->id)
		{
			$this->db->delete($this->db_table, array($this->primary_key => $this->id));
			
			// Check the delete worked and return bool
			return ( $this->db->affected_rows() > 0 );
		}
    	else
    	{
			throw new Exception('This object has no ID', 400);
	    }
	}

	/**
    *	count
    *
    *	Counts number of rows in DB for this object
    *
    *	@access protected
    *	@return int
    */
	public function count($params = NULL)
	{
		if (is_array($params)) {
			foreach ($params AS $key => $value) {
				$this->db->where($key,$value); } }
		
		return $this->db->count_all_results($this->db_table);
	}



	/*
	 *   find
	 *
	 *   If found, populates a single record into the object.
	 *
	 *   @access    public
	 *   @return    BOOL
	 */
	public function find($uid)
	{
		$this->db->select('x.*');
		$this->db->where($this->primary_key, $uid);
		$this->db->from($this->db_table.' x');

		$query = $this->db->get();

		if ($query->num_rows() === 1)
		{
			$this->populate($query->row());
			return true;
		}
		elseif ($query->num_rows() > 1)
		{
			throw new Exception('Multiple matches exist for this ID, it is not unique.', 500);
		}
		else
		{
			// No results found
			throw new exception('No record found with this ID', 404);
		}
	}



	/*
	 *   find_where
	 *
	 *   Returns an array of populate objects
	 *   You either pass an array of field_name => value OR two separate args for value, field
	 *
	 *   @author    Pete Hawkins <pete@craftydevil.co.uk>
	 *   @created   2011-06-09
	 *   @access    public
	 *   @return    Array of objects OR false
	 */
	public function find_where($field_name, $value  = null)
	{
		$result_array = array();
		
		$this->db->select('x.*');
		
		if (is_array($field_name))
		{
			$loop = $field_name;
		}
		elseif ( ! is_null($value))
		{
			$loop[$field_name] = $value;
		}
		else
		{
			throw new Exception('Error in method: find_where. Either pass an array of fields => values OR pass two args[0] = field_name, args[1] = value', 500);
		}
		
		foreach ($loop as $f => $v)
		{
			if (array_key_exists($f, $this->fields)) {
				if (is_array($v)) {
					$this->db->where_in($f, $v); }
				else {
					$this->db->where($f, $v); } }
		}
		
		$this->db->from($this->db_table.' x');
		
		$query = $this->db->get();
		
		if ($query->num_rows() === 0) { return $result_array; }
		
		foreach ($query->result() as $row)
		{
			$class = get_called_class();
			$object = new $class;
			$object->populate($row);
			$result_array[] = $object;
		}
		
		return $result_array;
	}
	
}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */