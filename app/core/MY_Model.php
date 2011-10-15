<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	protected 	$db_table = NULL;     // the name of the database table the model uses
	protected 	$primary_key = NULL;  // the column name of the primary key
	protected 	$fields = array();	  // an array to store the single value variables for the model
	public 	$id = NULL;               // the ID for the object
	
	public function __construct()
	{
		parent::__construct();
	}



	protected function set_params($db_table, $fields, $primary_key = 'id')
	{
		if( ! $db_table || ! $fields )
			throw new Exception('Missing model setup parameters');
		
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
			throw new Exception('You cannot set a property that does not exist in the defined fields for this model.', 103); }
	}



	/**
	*	__get
	*
	*	Check if a property exists in the fields and return default value
	*
	*	@access public
	*	@return BOOL
	*/
	public function __get($name)
	{
		if (array_key_exists($name, $this->fields)) {
			return $this->fields[$name]; }
		else {
			throw new Exception('You cannot get a property that does not exist in the defined fields for this model', 104); }
	}



	/**
    *	populate
    *
    *	Populates an object with the results from the database
    *
    *	@access protected
    *	@return none
    */
	protected function populate( $row )
	{
		$pri_key = $this->primary_key;
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
		
		$CI = get_instance();
		
		// insert array to db
    	if ($CI->db->insert($this->db_table, $insert_array))
    	{
			// data inserted, lets return the new id
    		$insert_id = $CI->db->insert_id();

    		$this->id = $insert_id;

    		return $insert_id;	// return the insert id
    	}
    	else
    	{
    		throw new Exception('Record could not be added.');
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
		
		$CI = get_instance();
		$CI->db->where( $this->primary_key, $this->id );
    	return $CI->db->update($this->db_table, $update_array);
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
			$CI = get_instance();
			$CI->db->delete($this->db_table, array($this->primary_key => $this->id));
			
			// Check the delete worked and return bool
			return ( $this->db->affected_rows() > 0 );
		}
    	else
    	{
			throw new Exception('This object has no ID');
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
	public function count( $params = NULL ) {
		$CI = get_instance();
		if (is_array($params)) {
			foreach ($params AS $key => $value) {
				$CI->db->where($key,$value); } }
		
		return $CI->db->count_all_results($this->db_table);
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
		$CI = get_instance();
		$CI->db->select($this->fields);
		$CI->db->where($this->primary_key, $uid);
		$CI->db->from($this->db_table);

		$query = $CI->db->get();

		if ($query->num_rows() === 1)
		{
			$this->populate($query->row());
			return true;
		}
		elseif ($query->num_rows() > 1)
		{
			throw new Exception('multiple_matches', 102);
		}
		else
		{
			// No results found
			throw new exception('not_found', 101);
		}
	}



	/**
	*	find_where
	*
	*	Assuming you don't need to join any other tables
	*	return array of objects matching "params"
	*	Override in inheriting class if you need to do more
	*	clever things that just get from a single table
	*/
	public function find_where( $params = array() )
    {
	    $exceptions	= array( 'limit', 'offset', 'order' );
		$limit 		= array_key_exists( 'limit', $params) ? $params['limit'] : NULL;
		$offset		= array_key_exists( 'offset', $params) ? $params['offset'] : NULL;
		$order 		= array_key_exists( 'order', $params) ? $params['order'] : 'x.'.$this->primary_key.' ASC';
		$CI = get_instance();

		if ( $params ) {
			foreach($params as $key => $value)
			{
				if ( ! in_array($key, $exceptions))
				{
					if (is_array($value)) {
						$CI->db->where_in('x.'.$key, $value); }
					else {
						$CI->db->where('x.'.$key, $value); }
				}
			}
		}

    	$CI->db->order_by($order);

    	$CI->db->select(	'x.'.$this->primary_key.' as id,
							x.*');

    	$CI->db->from($this->db_table.' x');
		$CI->db->limit($limit, $offset);

    	$query = $CI->db->get();

        $list = array();	// Create array to hold objects

        // Assign details from each row to a User_model object
        foreach ($query->result() as $row)
		{
			$class = get_class($this);
			$object = new $class;
			
			$object->populate($row);
			$list[$object->id] = $object;
		}

		// Return the array of populated objects
		return $list;
    }

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */