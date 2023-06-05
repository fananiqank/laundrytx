<?php
session_start();
error_reporting(0);
require_once "../../funlibs.php";
$db=new Database();


//require( 'ssp.class.php' );
//$db=new SSP;
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use 
 
$table = "dpt";

// Table's primary key
$primaryKey = 'iddpt';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db'      => 'nik','dt'   => 0, 'field' => 'nik', 
		   'formatter' => function( $d, $row ) {
			return"$d";
			}
		  ),
	array('db'      => 'pemilih as fv_nama','dt'   => 1, 'field' => 'fv_nama',),
	array('db'      => "concat(tmplahir,' - ',tgllahir) "." as poli",'dt'       => 2, 'field' => 'poli',),
	array('db'      => 'jk as fd_tglmasuk', 'dt'        => 3,'field' => 'fd_tglmasuk',),
	array('db'      => "alamat"." as alamat",'dt'       => 4, 'field' => 'alamat',),
	array('db'      => "iddpt"." as iddpt",'dt'       => 5, 'field' => 'iddpt',),
	
		
);

// data concat harus berada dipaling akhir
// SQL server connection information
//require('config.php');
 
$sql_details = array(
	'user' => $db->user(),
	'pass' => $db->pass(), 
	'db'   => $db->db(),
	'host' => $db->host()
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */ 

// require( 'ssp.class.php' );
//echo "../../lib/ssp.customized.class.php"; 
require('../../lib/ssp.customized.class.php' );


/*$joinQuery = "FROM td_tracer a
JOIN tm_pasien b ON a.fc_idrm = b.fc_idrm
JOIN td_antrianpoli c ON a.fc_nomor = c.Id
JOIN t_poli d ON c.fc_idpoli = d.fc_idpoli";
$extraWhere = "IFnull(a.fd_tglkeluar, 0) = '0'
			   AND date(c.fd_tglmasuk) = '$_GET[tgl]'
			   GROUP BY a.fc_idrm having count(*)>0";*/
$joinQuery = "FROM dpt";
$extraWhere = "";
/*$extraWhere = "IFnull(a.fd_tglkeluar, 0) = '0'
			   AND date(c.fd_tglmasuk) = '2017-08-01'
			   GROUP BY a.fc_idrm";	*/		           

//echo "select * $joinQuery where $extraWhere";
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);