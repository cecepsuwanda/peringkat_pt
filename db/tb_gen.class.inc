<?php
require_once 'default_table.class.inc';
require_once 'query_string.class.inc';
class tb_gen extends Default_Table
{
	// additional class variables go here
	function __construct($tb_name)
	{
		if($tb_name!='tbgen'){
		 $this->tablename       = $tb_name;  
         $this->fieldlist       = $this->getfieldlist();
        }
        
	} // end class constructor

} // end class


?>