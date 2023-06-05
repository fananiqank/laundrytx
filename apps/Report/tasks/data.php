<?php
//error_reporting(0);
require( '../../../funlibs.php' );
$con=new Database;
session_start();
if($_GET['c']==''){
	$cat="";
}else{
	$cat="and a.id_kategori = $_GET[c]";
}

if($_GET['y']==''){
	$pri="";
}else{
	$pri="and a.priority_tasks='$_GET[y]'";
}

if($_GET['t']==''){
	$sta="";
}else{
	$sta="and b.status_tasks='$_GET[t]'";
}

if($_GET['g']==''){
	$asi="";
}else{
	$asi="and a.id_agent='$_GET[g]'";
}

if($_GET['tg']== 'A' && $_GET['tg2']== 'A'){
	$tgl="";
}else{
	$tgl="and DATE(a.created_date_tasks) BETWEEN '$_GET[tg]' AND '$_GET[tg2]'";
}
					
$table = 'trrequest';
$primaryKey = 'id_req';
$joinQuery = "from trtasks a
left join mtkategori d on a.id_kategori = d.id_kategori
left join mtagent e on a.id_agent = e.id_agent
left join mtpegawai f on e.id_pegawai = f.id_pegawai";       
$extraWhere="a.status_tasks != '' $cat $sub $pri $sta $jen $asi $ase $tgl";
//echo $extraWhere;
$columns = array(
	array( "db" => "a.id_tasks",     "dt" => 0, "field" => "id_tasks" ),
	array( "db" => "a.no_tasks",     "dt" => 1, "field" => "no_tasks" ),
	array( "db" => "a.nama_tasks", 	"dt" => 2, "field" => "nama_tasks"),
	array( "db" => "f.nama_pegawai", 	"dt" => 3, "field" => "nama_pegawai" ),
	array( "db" => "d.nama_kategori",     "dt" => 4, "field" => "nama_kategori" ),
	array( "db" => "a.created_date_tasks", 	"dt" => 5, "field" => "created_date_tasks" ),
	array( "db" => "a.start_date_tasks", 	"dt" => 6, "field" => "start_date_tasks" ),
	array( "db" => "a.end_date_tasks", 	"dt" => 7, "field" => "end_date_tasks" ),
	array( "db" => "a.priority_tasks", 	"dt" => 8, "field" => "priority_tasks" ,
			'formatter' => function( $d, $row ) {
					if($d == '1'){
						$isijum = "Low";
					} else if ($d == '2') {
						$isijum = "Medium";
					} else if ($d == '3') {
						$isijum = "High";
					}
					
				return "$isijum";

	}),
	array( "db" => "a.status_tasks", 	"dt" => 9, "field" => "status_data" ,
			'formatter' => function( $d, $row ) {
					
					if($d == '1'){
						$isijam = "Open";
					} else if ($d == '2') {
						$isijam = "On Progress";
					} else if ($d == '3') {
						$isijam = "Hold WO";
					} else if ($d == '4') {
						$isijam = "Closed";
					} else if ($d == '0') {
						$isijam = "Reject";
					} else if ($d == '5') {
						$isijam = "Hold Request";
					} else if ($d == '6') {
						$isijam = "Assigned";
					} 
					// $isijam = "<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick='mode($d)' class='label label-warning' style='cursor:pointer'>details</a>";
				/*return "
				<a href='javascript:void(0)' data-toggle='modal' id='mod' data-target='#datajaminan' onClick=detiljams('$d') class='label label-success' style='cursor:pointer'>Lihat Jaminan</a>
				";*/
				return "$isijam";

	}),
	array( "db" => "concat((DATEDIFF(CURDATE(),a.end_date_tasks)),'_',a.status_tasks) as stla", 	"dt" => 10, "field" => "stla", 
	'formatter' => function( $d, $row ) {
				   $det = explode('_', $d);
				   $dif = $det[0];
				   $stat = $det[1];

					if ($stat == '2' || $stat == '3'){
						if($dif > 0){
							$isijum = "<b>+ $dif</b>";
						} else if ($dif == 0){
							$isijum = "<b>$dif</b>";
						} else if ($dif < 0){
							$isijum = "<b>$dif</b>";
						}
					}
				return "$isijum";

	}),
	array( "db" => "a.remark_tasks", 	"dt" => 11, "field" => "remark_tasks" ),
);
//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * from $joinQuery where $extraWhere";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
