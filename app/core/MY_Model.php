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
    		return FALSE;
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
		$this->db->select($this->fields);
		$this->db->where($this->primary_key, $uid);
		$this->db->from($this->db_table);

		$query = $this->db->get();

		if ($query->num_rows() === 1)
		{
			$this->populate($query->row());
			return true;
		}
		elseif ($query->num_rows() > 1)
		{
			throw new Exception('Multiple rows found, this is not a primary key');
		}
		else
		{
			// No results found
			return false;
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

		if ( $params ) {
			foreach($params as $key => $value)
			{
				if ( ! in_array($key, $exceptions))
				{
					if (is_array($value))
					{
						$this->db->where_in('x.'.$key, $value);
					}
					else
					{
						$this->db->where('x.'.$key, $value);
					}
				}
			}
		}

    	$this->db->order_by($order);

    	$this->db->select(	'x.'.$this->primary_key.' as id,
							x.*');

    	$this->db->from($this->db_table.' x');
		$this->db->limit($limit, $offset);

    	$query = $this->db->get();

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