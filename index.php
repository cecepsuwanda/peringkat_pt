
<html>

 <body>

<?php
    
   require_once 'simple_html_dom.php';
   require_once 'Classes/PHPExcel/IOFactory.php';

   set_time_limit(180);

$inputFileName = 'klasifikasi20151.xlsx';

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch (Exception $e) {
    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) 
    . '": ' . $e->getMessage());
}

echo '<table>';

echo '<tr>';
   //echo '<th>No</th>';
   echo '<th>Kode PT</th>';
   echo '<th>Nama PT</th>';
   echo '<th>Kualitas SDM</th>';
   echo '<th>Kualitas Manajemen</th>';
   echo '<th>Kualitas Keg. Mahasiswa</th>';
   echo '<th>Kualiats Penelitian & Publikasi</th>';
   echo '<th>Skor Total</th>';
   echo '<th>Peringkat</th>';
   echo '<th>Cluster</th>';
echo '</tr>';

$kdpt = array();
for($sheetno=7;$sheetno<31;$sheetno++){
		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet($sheetno);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		//  Loop through each row of the worksheet in turn
		for ($row = 5; $row <= $highestRow; $row++) {
		    //  Read a row of data into an array
		    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, 
		    NULL, TRUE, FALSE);
		    
           if(($rowData[0][9]==4) and (substr($rowData[0][2],0,11)=='Universitas') and (substr($rowData[0][1],0,1)=='4') ){
			    echo "<tr>";
			          $j=0;
			        foreach($rowData[0] as $k=>$v){
			              if($j>0){  
			                echo '<td>'.$v.'</td>';
                            
                            if($j==1){
                               $kdpt[]='0'.$v;                               
                            }
                          }
			               $j++;
			              } 
			    echo "</tr>";
		   }	    
		}		
 }
 echo '</table>';

 echo '<table>';

echo '<tr>';
   //echo '<th>No</th>';
   echo '<th>Kode PT</th>';
   echo '<th>Nama PT</th>';
   echo '<th>Kualitas SDM</th>';
   echo '<th>Kualitas Keg. Mahasiswa</th>';
   echo '<th>Kualitas Manajemen</th>';
   echo '<th>Kualiats Penelitian & Publikasi</th>';
   echo '<th>Skor Total</th>';
   echo '<th>Peringkat</th>';
   echo '<th>Cluster</th>';
echo '</tr>';

 if(!empty($kdpt))
 {
   foreach ($kdpt as $dt) {
       
     $page = file_get_html("http://pemeringkatan.ristekdikti.go.id/index.php/pemeringkatan/hasil?cari=$dt");
     
     //echo $page;
     echo "<tr><td>$dt</td>";
     $k=0;
     foreach($page->find('table[class="model1"]') as $table)
	 { 	 
	   if($k==0){
                  $l=0;
                  foreach($table->find('tr') as $tr)
					{ 	
						if($l==3)
						{
                          $m=0;
                          //echo $tr;
                          foreach($tr->find('td') as $td)
							            {				  
							              if(!in_array($m, array(2,4,6,8))){
							    
							                   echo "$td";
							              }
							  
							              $m++;
							            }
						}
						$l++;
					}
			   }	

	   $k++;
	   	        		
	 }
     echo "</tr>";
   }
}

echo '</table>';

?>
</body>
</html>