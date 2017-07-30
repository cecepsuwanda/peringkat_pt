 <?php 
  require_once 'db/tb_gen.class.inc'; 


   $tb = new tb_gen('tbgen');
   $tb->sql_select = 'b.kd_pt,
                       b.nm_pt,
                       SUM(IF(thn=2015,n_sdm,0))AS n_sdm2015,
                       SUM(IF(thn=2016,n_sdm,0))AS n_sdm2016,
                       SUM(IF(thn=2015,n_mhs,0))AS n_mhs2015,
                       SUM(IF(thn=2016,n_mhs,0))AS n_mhs2016,
                       SUM(IF(thn=2015,n_manajemen,0))AS n_manajemen2015,
                       SUM(IF(thn=2016,n_manajemen,0))AS n_manajemen2016,                       
                       SUM(IF(thn=2015,n_pen_pub,0))AS n_pen_pub2015,
                       SUM(IF(thn=2016,n_pen_pub,0))AS n_pen_pub2016,
                       SUM(IF(thn=2015,skor,0))AS skor2015,
                       SUM(IF(thn=2016,skor,0))AS skor2016,       
                       GROUP_CONCAT(CONCAT(thn,":  ",peringkat))AS peringkat,
                       SUM(IF(thn=2015,cluster,0))AS cluster2015,
                       SUM(IF(thn=2016,cluster,0))AS cluster2016';
   $tb->sql_from = 'tb_nilai a RIGHT JOIN tb_pt b ON a.kd_pt=b.kd_pt';
   $tb->sql_groupby = 'a.kd_pt';
   $tb->sql_orderby = 'a.thn,a.kd_pt';

   $data = $tb->getData('');


$txt_tabel = '';

$txt_tabel .='<table id="example" class="display" width="100%" cellspacing="0" >';

$txt_tabel .= '<thead>';
$txt_tabel .='<tr>';
   //echo '<th>No</th>';
   $txt_tabel .= '<th rowspan="2" >Kode PT</th>';
   $txt_tabel .= '<th rowspan="2" >Nama PT</th>';
   $txt_tabel .= '<th colspan="2" >Kualitas SDM</th>';
   $txt_tabel .= '<th colspan="2" >Kualitas Keg. Mahasiswa</th>';
   $txt_tabel .= '<th colspan="2" >Kualitas Manajemen</th>';
   $txt_tabel .= '<th colspan="2" >Kualiats Penelitian & Publikasi</th>';
   $txt_tabel .= '<th colspan="2" >Skor Total</th>';
   $txt_tabel .= '<th rowspan="2" >Peringkat</th>';
   $txt_tabel .= '<th colspan="2" >Cluster</th>';
$txt_tabel .= '</tr>';

$txt_tabel .='<tr>';
   //echo '<th>No</th>';
   $txt_tabel .= '<th>2015</th>';
   $txt_tabel .= '<th>2016</th>';
   $txt_tabel .= '<th>2015</th>';
   $txt_tabel .= '<th>2016</th>';
   $txt_tabel .= '<th>2015</th>';
   $txt_tabel .= '<th>2016</th>';
   $txt_tabel .= '<th>2015</th>';
   $txt_tabel .= '<th>2016</th>';
   $txt_tabel .= '<th>2015</th>';
   $txt_tabel .= '<th>2016</th>';
   $txt_tabel .= '<th>2015</th>';
   $txt_tabel .= '<th>2016</th>';
$txt_tabel .= '</tr>';

$txt_tabel .= '</thead>';

$txt_tabel .= '<tfoot>';
$txt_tabel .='<tr>';
   //echo '<th>No</th>';
   $txt_tabel .= '<th>Kode PT</th>';
   $txt_tabel .= '<th>Nama PT</th>';
   $txt_tabel .= '<th>SDM 2015</th>';
   $txt_tabel .= '<th>SDM 2016</th>';
   $txt_tabel .= '<th>Mhs 2015</th>';
   $txt_tabel .= '<th>Mhs 2016</th>';
   $txt_tabel .= '<th>Manajemen 2015</th>';
   $txt_tabel .= '<th>Manajemen 2016</th>';
   $txt_tabel .= '<th>Pen. & Pub. 2015</th>';
   $txt_tabel .= '<th>Pen. & Pub. </th>';
   $txt_tabel .= '<th>skor 2015</th>';
   $txt_tabel .= '<th>skor 2016</th>';
   $txt_tabel .= '<th>Peringkat</th>';
   $txt_tabel .= '<th>cluster 2015</th>';
   $txt_tabel .= '<th>cluster 2016</th>';
$txt_tabel .= '</tr>';
$txt_tabel .= '</tfoot>';


$txt_tabel .= '<tbody>';

  if(!empty($data))
  {
     foreach ($data as $row) {
      $txt_tabel .='<tr>';
        $j=1;
        foreach ($row as $value) {
            if(($j>2) and ($j<13)){
              $txt_tabel .= "<td>".number_format(floatval($value),2)."</td>"; 
            }else{
              $txt_tabel .= "<td>$value</td>"; 
            }
            
            $j++;
        }
      $txt_tabel .='</tr>';  
     }
  }

$txt_tabel .= '</tbody>';
$txt_tabel .= '</table>';


?>









<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title>Peringkat PT</title>
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

        var table = $('#example').DataTable({"columnDefs": [
                { "type": "numeric-comma", targets: [3,4,5,6,7,8,9,10,11,12] }
            ]});

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
    <center><H1>PERINGKAT PERGURUAN TINGGI</H1></center>
    <center><H2>Sumber Data : http://pemeringkatan.ristekdikti.go.id </H2></center>
    <?php echo $txt_tabel; ?>

</body>

</html>