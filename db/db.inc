<?php

Class DbConnect { 
	
	var $host = 'localhost'; 
	var $user = 'root'; 
	var $password = '123456'; 
	var $database = 'peringkat_pt'; 
	var $persistent = false; 
	
	var $conn = NULL; 
	
	var $result= false; 
	var $error_reporting = false; 
	
	/*constructor function this will run when we call the class */ 
	
	function __construct($host, $user, $password, $database, $error_reporting=true, $persistent=false) { 
		
		if( !empty($host) and !empty($user) and !empty($password) and !empty($database)){
			$this->host = $host; 
			$this->user = $user; 
			$this->password = $password; 
			$this->database = $database; 
		}
		//else{
		//	$server = dirname(dirname(__FILE__));
			
		//	if(strpos($server,'siamipa3')==0){
		//		$this->host = 'localhost'; 
		//		$this->user = 'fmipaun_cecep'; 
		//		$this->password = 'cecep1982'; 
		//		$this->database = 'fmipaun_siafmipa';					    
		//	}
		//}
		
		$this->persistent = $persistent; 
		$this->error_reporting = $error_reporting;
		
		
	} 
	
	function open() { 
		
		$tmparr=array();
		if ($this->persistent) { 
			
			//    $func = 'mysql_pconnect'; 
			$tmparr = array(PDO::ATTR_PERSISTENT => true);
		} else { 
			
			//   $func = 'mysql_connect'; 
			
		}             
		
		/* Connect to the MySQl Server */ 
		
		
		try {
			$this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password,$tmparr);
			// set the PDO error mode to exception
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return true;
		}
		catch(PDOException $e)
		{
			//echo $e;
			return false;
		}
		
		
		//$this->conn = $func($this->host, $this->user, $this->password); 
		
		//if (!$this->conn) { 

		//   return false; 
		
		// } 
		
		/* Select the requested DB */ 
		
		//if (@!mysql_select_db($this->database, $this->conn)) { 
		
		//  return false; 
		//} 
		// return true;			 
		
		
	} 
	
	/*close the connection */ 
	
	function close() { 
		
		$this->conn = null;
		//return (@mysql_close($this->conn)); 
		return true;
	} 
	
	/* report error if error_reporting set to true */ 
	
	function error() { 
		
		if ($this->error_reporting) { 
			
			//return (mysql_error()) ;
			//return $this->conn->errorInfo();  			  
		} 
		
	} 
	
	function query($sql) { 
		
		//$this->result = @mysql_query($sql, $this->conn);               
		$this->result = $this->conn->query($sql);
		return($this->result != false);
		
	} 

    

	function affectedrows() { 

		//return(@mysql_affected_rows($this->conn)); 
		return($this->result->rowCount());
	} 
	
	function numrows() { 
		
		//return(@mysql_num_rows($this->result)); 
		return($this->result->rowCount()); 
	} 
	function fetchobject() { 
		
		//return(@mysql_fetch_object($this->result, MYSQL_ASSOC)); 
		return($this->result->fetchObject('sqlresult'));
	} 
	function fetcharray() { 
		
		return(mysql_fetch_array($this->result)); 
		
	} 
	
	function fetchrow() { 
		
		//return(mysql_fetch_row($this->result)); 
		return($this->result->fetch());
	}  
	
	function fetchassoc() { 
		
		//return(@mysql_fetch_assoc($this->result));
		return($this->result->fetch(PDO::FETCH_ASSOC)); 		 
	} 
	
	function freeresult() { 
		
		//return(@mysql_free_result($this->result)); 
		return($this->result->closeCursor());
	} 
	
} 
?>