<?php

   
   require_once 'Classes/PHPExcel/IOFactory.php';
   require_once 'db/tb_gen.class.inc';

   set_time_limit(240);

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

$txt_tabel = '';

$txt_tabel .='<table id="example" class="display" width="100%" cellspacing="0" >';

$txt_tabel .= '<thead>';
$txt_tabel .= '<tr>';
   //echo '<th>No</th>';
   $txt_tabel .= '<th>Kode PT</th>';
   $txt_tabel .= '<th>Nama PT</th>';
   $txt_tabel .= '<th>Kualitas SDM</th>';
   $txt_tabel .= '<th>Kualitas Manajemen</th>';
   $txt_tabel .= '<th>Kualitas Keg. Mahasiswa</th>';
   $txt_tabel .= '<th>Kualiats Penelitian & Publikasi</th>';
   $txt_tabel .= '<th>Skor Total</th>';
   $txt_tabel .= '<th>Peringkat</th>';
   $txt_tabel .= '<th>Cluster</th>';
$txt_tabel .= '</tr>';
$txt_tabel .= '</thead>';

$txt_tabel .= '<tfoot>';
$txt_tabel .= '<tr>';
   //echo '<th>No</th>';
   $txt_tabel .= '<th>Kode</th>';
   $txt_tabel .= '<th>Nama</th>';
   $txt_tabel .= '<th>SDM</th>';
   $txt_tabel .= '<th>Manajemen</th>';
   $txt_tabel .= '<th>Mahasiswa</th>';
   $txt_tabel .= '<th>Pen. & Pub.</th>';
   $txt_tabel .= '<th>Skor</th>';
   $txt_tabel .= '<th>Peringkat</th>';
   $txt_tabel .= '<th>Cluster</th>';
$txt_tabel .= '</tr>';
$txt_tabel .= '</tfoot>';

$txt_tabel .= '<tbody>';
$kdpt = array();
$tb_pt = new tb_gen('tb_pt');
$tb_nilai = new tb_gen('tb_nilai');

$f_tbpt = array(1=>'kd_pt',2=>'nm_pt');
$f_tbnilai = array(1=>'kd_pt',3=>'n_sdm',4=>'n_manajemen',5=>'n_mhs',6=>'n_pen_pub',7=>'skor',8=>'peringkat',9=>'cluster');
$entry_tbpt = array();
$entry_tbnilai=array();
for($sheetno=0;$sheetno<33;$sheetno++){
		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet($sheetno);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		//  Loop through each row of the worksheet in turn
		for ($row = 5; $row <= $highestRow; $row++) {
		    //  Read a row of data into an array
		    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		    //($rowData[0][9]==4) and (substr($rowData[0][2],0,11)=='Universitas') and
           //if((substr($rowData[0][1],0,1)=='4') ){
			    $txt_tabel .= "<tr>";
			          $j=0;
			        foreach($rowData[0] as $k=>$v){
			              if($j>0){  
			                                            
                            if($j==1){
                               $tmp = (strlen($v)==4) ? '00'.$v : ((strlen($v)==5) ? '0'.$v : $v );
                               $kdpt[]=$tmp;
                               $txt_tabel .= '<td>'.$tmp.'</td>';
                               $entry_tbpt[$f_tbpt[$j]]=$tmp;
                               $entry_tbnilai[$f_tbnilai[$j]]=$tmp;                               
                            }else{
                              if($j<10){	
                               $txt_tabel .= '<td>'.$v.'</td>';
                               if(isset($f_tbpt[$j]))
                                 $entry_tbpt[$f_tbpt[$j]]=addslashes($v);
                               if(isset($f_tbnilai[$j]))
                                 $entry_tbnilai[$f_tbnilai[$j]]=$v;                               
                              } 
                            }
                          }
                               $j++;
			              } 
			    $txt_tabel .= "</tr>";
                          
                          $entry_tbpt['t_entry']=date('Y-m-d H:i:s');
                          $entry_tbnilai['t_entry']=date('Y-m-d H:i:s');
                          $entry_tbnilai['thn']=2015;

			              //echo '<pre>'; 
                            //print_r($entry_tbpt);
                            //print_r($entry_tbnilai);
                          //echo '</pre>';
                        if($entry_tbpt['kd_pt']!=''){
                          //$tb_nilai->insertRecord($entry_tbnilai);
                          //$tb_pt->insertRecord($entry_tbpt);
                        }  

		   //}	    
		}		
 }
 $txt_tabel .= '</tbody>';
 $txt_tabel .= '</table>';

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title>Baca Excel</title>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   
   <link type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" />
   <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
   <script type="text/javascript">
      $(document).ready(function() {

        $('#example tfoot th').each( function () {
	        var title = $(this).text();
	        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        } ); 

        var table = $('#example').DataTable({
                       "order": [[ 7, "asc" ]]
                    });

        table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
         } );
        } );  

      } );
   </script>   

</head>
<body>
    <center><H1>PERINGKAT PERGURUAN TINGGI 2015</H1></center>
    <center><H2>Sumber Data : publikasi ristekdikti </H2></center>
    <?php echo $txt_tabel; ?>

</body>

</html>


