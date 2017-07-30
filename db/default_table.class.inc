<?php
include 'db.inc';

class Default_Table
{
	var $tablename;        //  table name
	var $rows_per_page;     // used in pagination
	var $pageno;            // current page number
	var $lastpage;          // highest page number
	var $fieldlist;         // list of fields in this table
	var $data_array;        // data from the database
	var $numrows;
	var $errors;
	var $sql_select;
	var $sql_from;
	var $sql_where;
	var $sql_groupby;
	var $sql_having;
	var $sql_orderby;  

	function __construct()
	{
		$this->tablename       = 'default';
		//$this->dbname          = 'default';
		$this->rows_per_page   = 10;
		
		$this->fieldlist = array('column1', 'column2', 'column3');
		$this->fieldlist['column1'] = array('pkey' => 'y');

	} // constructor 



	function getfieldlist()
	{
		$dbconnect = new DbConnect("","","","",true,false);
		if (!$dbconnect->open()){
			$this->errors[]=$dbconnect->error();
			$isconnect=false;
			
		}else{
			
			
			if (empty($this->sql_from)) {
				$from_str = $this->tablename;   // the default is current table
			} else {
				$from_str = $this->sql_from;
			} // if
			
			
			
			$query = "SHOW COLUMNS FROM $from_str";
			
			$dbconnect->query($query);
			
			while ($row = $dbconnect->fetchassoc()) {
				
				$this->data_array[] = $row['Field'];
				
				if($row['Key']=='PRI'){ 
					$this->data_array[$row['Field']] = array('pkey' => 'y');
				}
				
			} // while
			
			$dbconnect->freeresult();
			$dbconnect->close();
		}
		
		return $this->data_array;
		
		
		
	}



	function getData($where)
	{
		
		$this->data_array = array();
		$this->errors = array();
		
		$pageno          = $this->pageno;
		$rows_per_page   = $this->rows_per_page;
		$this->numrows   = 0;
		$this->lastpage  = 0;
		
		//global $dbconnect, $query;
		//$dbconnect = db_connect() or trigger_error("SQL", E_USER_ERROR); 
		
		
		
		if (empty($this->sql_select)) {
			$select_str = '*';    // the default is all fields
		} else {
			$select_str = $this->sql_select;
		} // if

		if (empty($this->sql_from)) {
			$from_str = $this->tablename;   // the default is current table
		} else {
			$from_str = $this->sql_from;
		} // if
		
		if (empty($where)) {
			$where_str = NULL;
		} else {
			$where_str = "WHERE $where";
		} // if

		if (!empty($this->sql_where)) {
			if (!empty($where_str)) {
				$where_str .= " AND $this->sql_where";
			} else {
				$where_str = "WHERE $this->sql_where";
			} // if
		} // if
		
		if (!empty($this->sql_groupby)) {
			$group_str = "GROUP BY $this->sql_groupby";
		} else {
			$group_str = NULL;
		} // if

		if (!empty($this->sql_having)) {
			$having_str = "HAVING $this->sql_having";
		} else {
			$having_str = NULL;
		} // if

		if (!empty($this->sql_orderby)) {
			$sort_str = "ORDER BY $this->sql_orderby";
		} else {
			$sort_str = NULL;
		} // if


		
		
		//$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		//$query_data = mysql_fetch_row($result);
		
		$dbconnect = new DbConnect("","","","",true,false);
		if (!$dbconnect->open()){
			$this->errors[]=$dbconnect->error();
			$isconnect=false;
			
		}else{
			$isconnect=true;
			$query = "SELECT * FROM $from_str $where_str";
			//echo $query."<br>";
			$dbconnect->query($query);
			$query_data = $dbconnect->fetchrow();
			//print_r($query_data) ;
		}
		
		
		
		//$this->numrows = $query_data[0];
		$this->numrows = $dbconnect->numrows();
		
		if ($this->numrows <= 0) {
			$this->pageno = 0;
			return;
		} // if
		
		if ($rows_per_page > 0) {
			$this->lastpage = ceil($this->numrows/$rows_per_page);
		} else {
			$this->lastpage = 1;
		} // if
		
		if ($pageno == '' OR $pageno <= '1') {
			$pageno = 1;
		} elseif ($pageno > $this->lastpage) {
			$pageno = $this->lastpage;
		} // if
		$this->pageno = $pageno;
		
		if ($rows_per_page > 0) {
			$limit_str = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
		} else {
			$limit_str = NULL;
		} // if
		
		
		$query = "SELECT $select_str
			FROM $from_str 
				$where_str 
				$group_str 
				$having_str 
				$sort_str 
				$limit_str";
		
		//echo $query."<br>";
		if($isconnect){			 
			//$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
			
			$dbconnect->query($query);
			
			while ($row = $dbconnect->fetchassoc()) {
				
				$this->data_array[] = $row;
			} // while
			
			$dbconnect->freeresult();
			$dbconnect->close();
		}
		
		return $this->data_array;
		
	} // getData  

	function insertRecord ($fieldarray)
	{
		$this->errors = array();
		
		//global $dbconnect, $query;
		//$dbconnect = db_connect() or trigger_error("SQL", E_USER_ERROR);
		
		$fieldlist = $this->fieldlist;
		
		foreach ($fieldarray as $field => $fieldvalue) {
			if (!in_array($field, $fieldlist)) {
				unset ($fieldarray[$field]);
			} // if
		} // foreach
		
		$query = "INSERT INTO $this->tablename SET ";
		foreach ($fieldarray as $item => $value) {
			$query .= "$item='$value', ";
		} // foreach
		
		$query = rtrim($query, ', ');	  
		
		//echo $query;
		//$result = @mysql_query($query, $dbconnect);
		
		$dbconnect = new DbConnect("","","","",true,false);
		if (!$dbconnect->open()){
			$this->errors[]=$dbconnect->error();
		}else{
			$dbconnect->query($query);
			$this->errors[]=$dbconnect->error(); 
			$dbconnect->freeresult();
			$dbconnect->close(); 	    
		}
		
		//if (mysql_errno() <> 0) {
		//   if (mysql_errno() == 1062) {
		//      $this->errors[] = "A record already exists with this ID.";
		//   } else {
		//      trigger_error("SQL", E_USER_ERROR);
		//   } // if
		//} // if
		
		return;
		
	} // insertRecord	

	function export_record($tb,$field,$field1,$where)
	{
		$this->errors = array();
		
		if(!empty($where)){
			$query = "INSERT INTO $tb ($field) select $field1 from $this->tablename where $where";
		}else
		{
			$query = "INSERT INTO $tb ($field) select $field1 from $this->tablename";
		}
		
		
		$dbconnect = new DbConnect("","","","",true,false);
		if (!$dbconnect->open()){
			$this->errors[]=$dbconnect->error();
		}else{
			$dbconnect->query($query);
			$this->errors[]=$dbconnect->error(); 
			$dbconnect->freeresult();
			$dbconnect->close(); 	    
		}
	}


	function updateRecord ($fieldarray)
	{
		$this->errors = array();
		
		//global $dbconnect, $query;
		//$dbconnect = db_connect() or trigger_error("SQL", E_USER_ERROR);
		
		$fieldlist = $this->fieldlist;
		foreach ($fieldarray as $field => $fieldvalue) {
			if (!in_array($field, $fieldlist)) {
				unset ($fieldarray[$field]);
			} // if
		} // foreach
		
		$where  = NULL;
		$update = NULL;
		foreach ($fieldarray as $item => $value) {
			if (isset($fieldlist[$item]['pkey'])) {
				$where .= "$item='$value' AND ";
			} else {
				$update .= "$item='$value', ";
			} // if
		} // foreach
		
		$where  = rtrim($where, ' AND ');
		$update = rtrim($update, ', ');
		
		$query = "UPDATE $this->tablename SET $update WHERE $where";
		
		
		
		//$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		
		$dbconnect = new DbConnect("","","","",true,false);
		if (!$dbconnect->open()){
			$this->errors[]=$dbconnect->error();
		}else{
			$dbconnect->query($query);
			$this->errors[]=$dbconnect->error();
			$dbconnect->freeresult();
			$dbconnect->close(); 	    
		}
		
		return;
		
	} // updateRecord

	function deleteRecord ($fieldarray)
	{
		$this->errors = array(); 
		
		//global $dbconnect, $query;
		//$dbconnect = db_connect() or trigger_error("SQL", E_USER_ERROR);
		
		$fieldlist = $this->fieldlist;
		$where  = NULL;
		foreach ($fieldarray as $item => $value) {
			if (isset($fieldlist[$item]['pkey'])) {
				$where .= "$item='$value' AND ";
			} // if
		} // foreach
		
		$where  = rtrim($where, ' AND ');
		
		$query = "DELETE FROM $this->tablename WHERE $where";
		//$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		
		$dbconnect = new DbConnect("","","","",true,false);
		if (!$dbconnect->open()){
			$this->errors[]=$dbconnect->error();
		}else{
			$dbconnect->query($query);	
			$this->errors[]=$dbconnect->error();
			$dbconnect->freeresult();
			$dbconnect->close(); 		
		}
		
		return;	  
		
	} // deleteRecord

	function deleteAllRecord ($where)
	{
		$this->errors = array(); 
		
		//global $dbconnect, $query;
		//$dbconnect = db_connect() or trigger_error("SQL", E_USER_ERROR);  
		
		
		if(!empty($where)){
			$query = "DELETE FROM $this->tablename WHERE $where";
		}else{
			$query = "DELETE FROM $this->tablename";
		}
		
		//$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		
		$dbconnect = new DbConnect("","","","",true,false);
		if (!$dbconnect->open()){
			$this->errors[]=$dbconnect->error();
		}else{
			$dbconnect->query($query);	
			$this->errors[]=$dbconnect->error();
			$dbconnect->freeresult();
			$dbconnect->close(); 		
		}
		
		return;	  
		
	} // deleteRecord


} // end class


?>