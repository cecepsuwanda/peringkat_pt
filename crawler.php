<?php

   require_once 'simple_html_dom.php';
   require_once 'db/tb_gen.class.inc'; 


   set_time_limit(600);

   $tb_pt = new tb_gen('tb_pt');
   $tb_nilai = new tb_gen('tb_nilai');     
   
   $tb_pt->sql_select = 'kd_pt';
   $data = $tb_pt->getData(''); 

   $kdpt=array();
   if(!empty($data))
   {
      foreach ($data as $row) {
      	$kdpt[]=$row['kd_pt'];
      }
   }

$f_tbnilai = array(1=>'n_sdm',3=>'n_mhs',5=>'n_manajemen',7=>'n_pen_pub',9=>'skor',10=>'peringkat',11=>'cluster');
$entry_tbnilai=array();

$txt_tabel = '';

$txt_tabel .='<table id="example" class="display" width="100%" cellspacing="0" >';

$txt_tabel .= '<thead>';
$txt_tabel .='<tr>';
   //echo '<th>No</th>';
   $txt_tabel .= '<th>Kode PT</th>';
   $txt_tabel .= '<th>Nama PT</th>';
   $txt_tabel .= '<th>Kualitas SDM</th>';
   $txt_tabel .= '<th>Kualitas Keg. Mahasiswa</th>';
   $txt_tabel .= '<th>Kualitas Manajemen</th>';
   $txt_tabel .= '<th>Kualiats Penelitian & Publikasi</th>';
   $txt_tabel .= '<th>Skor Total</th>';
   $txt_tabel .= '<th>Peringkat</th>';
   $txt_tabel .= '<th>Cluster</th>';
$txt_tabel .= '</tr>';
$txt_tabel .= '</thead>';

$txt_tabel .= '<tfoot>';
$txt_tabel .='<tr>';
   //echo '<th>No</th>';
   $txt_tabel .= '<th>Kode PT</th>';
   $txt_tabel .= '<th>Nama PT</th>';
   $txt_tabel .= '<th>SDM</th>';
   $txt_tabel .= '<th>Mahasiswa</th>';
   $txt_tabel .= '<th>Manajemen</th>';
   $txt_tabel .= '<th>Pen. & Pub.</th>';
   $txt_tabel .= '<th>Skor</th>';
   $txt_tabel .= '<th>Peringkat</th>';
   $txt_tabel .= '<th>Cluster</th>';
$txt_tabel .= '</tr>';
$txt_tabel .= '</tfoot>';



 if(!empty($kdpt))
 {
   $txt_tabel .= '<tbody>';
   foreach ($kdpt as $dt) {
       
     $page = file_get_html("http://pemeringkatan.ristekdikti.go.id/index.php/pemeringkatan/hasil?cari=$dt");
     
     //echo $page;
	 $fail = $page->find('b[class="fail"]');

	 if(!empty($fail)){
       $txt_tabel .= "<tr><td>$dt</td>
                          <td>Tidak Ada Data</td>
                          <td>Tidak Ada Data</td>
                          <td>Tidak Ada Data</td>
                          <td>Tidak Ada Data</td>
                          <td>Tidak Ada Data</td>
                          <td>Tidak Ada Data</td>
                          <td>Tidak Ada Data</td>
                          <td>Tidak Ada Data</td>
                       </tr>";
	 }else{
		     $txt_tabel .= "<tr><td>$dt</td>";
		     $entry_tbnilai['thn']=2016;
		     $entry_tbnilai['kd_pt']=$dt;

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
									    
									                   $txt_tabel .= '<td>'.$td->innertext().'</td>';
									                   if(isset($f_tbnilai[$m]))
                                                         $entry_tbnilai[$f_tbnilai[$m]]=$td->innertext();
									              }
									  
									              $m++;
									            }
								}
								$l++;
							}
					   }	

			   $k++;
			   	        		
			 }
		     $txt_tabel .= "</tr>";

		     $entry_tbnilai['t_entry']=date('Y-m-d H:i:s');
		     //$tb_nilai->insertRecord($entry_tbnilai);
		  }   
   }

   $txt_tabel .= '</tbody>';

}

$txt_tabel .= '</table>';


?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title>Crawler</title>
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
    <center><H1>PERINGKAT PERGURUAN TINGGI 2016</H1></center>
    <center><H2>Sumber Data : http://pemeringkatan.ristekdikti.go.id </H2></center>
    <?php echo $txt_tabel; ?>

</body>

</html>