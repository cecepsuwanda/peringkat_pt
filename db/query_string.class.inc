<?php

class query_string
{
	function stat($op1,$op2,$op3)
	{
		return "$op1 $op2 $op3";	
	}
	
	function f_and($op)
	{
		return $this->walktrans($op,' and ');
	}
	
	function f_or($op)
	{
		return $this->walktrans($op,' or ');
	}
		
	function walktrans($arr,$separator=',')
	{
		$tmp_str='';
		array_walk($arr,function($fields)use(&$tmp_str,$separator)
		{
			if(!empty($tmp_str)){
				$tmp_str.=$separator;   
			}		   
			$tmp_str.=$fields;		      
		});
		return $tmp_str;
	}
	
	function select($from,$param=null)
	{
		$tmp='select ';
		if(empty($param)){
			$param=array('field'=>'*');
		}	   
		$tmp.= isset($param['field']) ?  (is_array($param['field']) ? $this->walktrans($param['field']) : $param['field'])  : '*' ;  
		
		$tmp.=" from ";
		$tmp.=is_array($from) ? $this->walktrans($from) : $from;
		
		if(!empty($param)){
			
			if(isset($param['where'])){
				$tmp.=" where ";
				$tmp.= $param['where'];   	
			}
			
			if(isset($param['groupby'])){
				$tmp.=" group by ";
				$tmp.=is_array($param['groupby']) ? $this->walktrans($param['groupby']) : $param['groupby'];   	
				
				if(isset($param['having'])){
					$tmp.=" having ";
					$tmp.= $param['having'];   	
				}
				
			}
			
			if(isset($param['orderby'])){
				$tmp.=" order by ";
				$tmp.=is_array($param['orderby']) ? $this->walktrans($param['orderby']) : $param['orderby']; 
			} 			
		}		
		return $tmp;	
	}
	
	function join($jointb,$jointype,$onquery)
	{
	   
	   $str_join = '';
	   
       if(count($jointb)>1){	   
	     $str_join = "$jointb[0] $jointype[0] JOIN $jointb[1] ON $onquery[0]"; 
	     for($i=1;$i<(count($jointb)-1);$i++){
	      $str_join .= ' '.$jointype[$i].' JOIN '.$jointb[$i+1].' ON '.$onquery[$i];
         }		  
	   }
	   return $str_join;
	}
}


?>