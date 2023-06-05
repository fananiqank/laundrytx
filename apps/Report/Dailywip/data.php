<?php
error_reporting(1);
require( '../../../funlibs.php' );
$con=new Database;
session_start();

$cmt = $con->searchseqcmt($_GET['cm']);
$colors = $con->searchseq($_GET['co']);
$inseams = $_GET['in'];
$sizes = $_GET['si'];
$exftydate = $_GET['xty'];
if ($cmt != ''){
	$cm = "and wo_no = '".$cmt."' ";
} else {
	$cm = "";
}

if ($colors != ''){
	$co = "and garment_colors = '".$colors."' ";
} else {
	$co = "";
}
if ($exftydate != ''){
	$xty = "and DATE(ex_fty_date_asli) = '".$exftydate."'";
} else {
	$xty = "";
}


$table = 'laundry_scan_qrcode';
$primaryKey = 'scan_id';
// $joinQuery = "from (SELECT DISTINCT (a.wo_no),EXTRACT(WEEK FROM a.ex_fty_date) as week, 			
// 					TO_CHAR(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date,a.ex_fty_date as ex_fty_date_asli,
// 					a.garment_colors,a.buyer_id,c.wash_category_desc,
// 					CASE 
// 						WHEN master_type_process_id = 4 
// 						THEN 'DP'
// 						ELSE 'NON DP'
// 					END as master_type_process_id,
// 					DATE(firstdate) as firstdate,DATE(fbdate) as fbdate,a.cutting_qty,
// 					CASE 
// 						WHEN g.lot_type = 'P' THEN 'Pre Bulk'
// 						WHEN g.lot_type = 'F' THEN 'First Bulk'
// 						WHEN g.lot_type != '' and g.lot_type = 'F' and g.lot_type = 'P' THEN 'Production'
// 					END as status_wo,g.lot_type,
// 					DATE_PART('day', fbdate - firstdate) AS lead_bulk,wo_master_dtl_proc_qty_rec,
// 					(cutting_qty - wo_master_dtl_proc_qty_rec) as sewing_balance,h.lastdate,
// 					concat(COALESCE((wo_master_dtl_proc_qty_rec - j.despatch_qty),0),'_',wo_master_dtl_proc_qty_rec) as total_wip,k.factory_name, 
// 					k.totaloutsew,m.totalout,NULL as aza
// 					from laundry_wo_master_dtl_proc a 
// 				    JOIN laundry_data_wo b ON a.wo_no=b.wo_no
// 					JOIN m_wash_category c ON b.wash_category_id=c.wash_category_id
// 					JOIN (select COALESCE(master_type_process_id,0) as master_type_process_id,role_wo_master_id 
// 								from laundry_role_wo d where master_type_process_id = '4') as d
// 								ON a.role_wo_master_id=d.role_wo_master_id
// 					JOIN (SELECT factory_name,wo_no, color, DATE(ex_fty_date) as ex_fty_date,sum(gdp_goods) as totaloutsew
// 								FROM qrcode_gdp a
// 									join qrcode_ticketing_master b ON a.ticketid=b.ticketid
// 									join qrcode_factory_master c ON a.factory_id = c. factory_id
// 								WHERE step_id_detail = 3 GROUP BY wo_no, color, ex_fty_date,factory_name) k 
// 								ON a.wo_no = k.wo_no and a.garment_colors = k.color and DATE(a.ex_fty_date)=k.ex_fty_date
// 					JOIN (select min(rec_createdate) as firstdate,wo_no,garment_colors,
// 								DATE(ex_fty_date) as ex_fty_date,rec_status 
// 								FROM laundry_receive
// 								GROUP BY wo_no,garment_colors,ex_fty_date,rec_status) as e
// 								on a.wo_no = e.wo_no and a.garment_colors = e.garment_colors and DATE(a.ex_fty_date)=e.ex_fty_date
// 					LEFT JOIN (SELECT MIN( approve_bulk_createdate ) AS fbdate,	wo_no,garment_colors
// 								FROM laundry_approve_bulk WHERE lot_type = 'F'	
// 								GROUP BY wo_no,garment_colors) as f
// 								on a.wo_no = f.wo_no and a.garment_colors = f.garment_colors
// 					LEFT JOIN (SELECT wo_no,garment_colors,lot_type
// 								FROM laundry_lot_number a 
// 								JOIN (select max(lot_id) as lot_id from laundry_lot_number 
// 											where lot_type = 'P' OR lot_type = 'F' and lot_status != 0) as b 
// 											ON a.lot_id=b.lot_id) as g
// 								on a.wo_no = g.wo_no and a.garment_colors = g.garment_colors
// 					LEFT JOIN (select (rec_createdate) as lastdate,wo_no,garment_colors,
// 								DATE(ex_fty_date) as ex_fty_date
// 								from laundry_receive where rec_status = 2) as h
// 								on a.wo_no = h.wo_no and a.garment_colors = h.garment_colors and DATE(a.ex_fty_date)=h.ex_fty_date
// 					LEFT JOIN (SELECT SUM ( wo_master_dtl_desp_qty ) AS despatch_qty, wo_no,garment_colors,
// 								DATE(ex_fty_date) as ex_fty_date 
// 								FROM laundry_wo_master_dtl_despatch t JOIN laundry_wo_master_dtl_proc u 
// 								on t.wo_master_dtl_proc_id=u.wo_master_dtl_proc_id
// 								GROUP BY wo_no,garment_colors,ex_fty_date) as j 
// 								on a.wo_no = j.wo_no and a.garment_colors = j.garment_colors and DATE(a.ex_fty_date)=j.ex_fty_date
// 					LEFT JOIN (select sum(gdp_goods) totalout,wo_no,color,DATE(ex_fty_date) as ex_fty_date
// 							   from 
// 							   (SELECT A.qrcode_key,workcenter_id,gdp_datetime,b.wo_no,b.color,b.ex_fty_date,to_timestamp( FLOOR ( ( EXTRACT ( 'epoch' FROM gdp_datetime ) / 300 ) ) * 300 ) AT TIME ZONE'UTC' + INTERVAL '5 minute' AS interval_alias,
// 							COALESCE ( smv_minute :: NUMERIC, '0' :: NUMERIC ) AS smv,gdp_goods 
// 								 FROM qrcode_gdp A 
// 									  JOIN qrcode_ticketing_master b ON A.qrcode_key = b.qrcode_key
// 									  LEFT JOIN qrcode_master_smv C ON C.smv_wono = b.wo_no 
// 								 WHERE workcenter_id = 'WAS1'
// 								) A 
// 								LEFT JOIN qrcode_schedulesshift b ON A.interval_alias BETWEEN (to_timestamp( FLOOR ( ( EXTRACT ( 'epoch' FROM b.schedulemasuk1 ) / 300 ) ) * 300 ) AT TIME ZONE'UTC' + INTERVAL '1 second' ) AND b.schedulemasuk2 AND SECTION = 'WASHING' 
// 								WHERE b.scheduledate = date(now()) 
// 								GROUP BY wo_no,color,ex_fty_date
// 					) as m ON a.wo_no = m.wo_no and a.garment_colors = m.color and DATE(a.ex_fty_date)=m.ex_fty_date
// 				) as asi 
// 				WHERE wo_no ilike '%' $cm $co $xty";  

// $joinQuery = "from (SELECT DISTINCT (a.wo_no),EXTRACT(WEEK FROM a.ex_fty_date) as week, 			
// 					TO_CHAR(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date,a.ex_fty_date as ex_fty_date_asli,
// 					a.garment_colors,a.buyer_id,c.wash_category_desc,
// 					CASE 
// 						WHEN master_type_process_id = 4 
// 						THEN 'DP'
// 						ELSE 'NON DP'
// 					END as master_type_process_id,
// 					DATE(firstdate) as firstdate,DATE(fbdate) as fbdate,a.cutting_qty,
// 					CASE 
// 						WHEN g.lot_type = 'P' THEN 'Pre Bulk'
// 						WHEN g.lot_type = 'F' THEN 'First Bulk'
// 						WHEN g.lot_type != '' and g.lot_type = 'F' and g.lot_type = 'P' THEN 'Production'
// 					END as status_wo,g.lot_type,
// 					DATE_PART('day', fbdate - firstdate) AS lead_bulk,wo_master_dtl_proc_qty_rec,
// 					(cutting_qty - wo_master_dtl_proc_qty_rec) as sewing_balance,h.lastdate,
// 					concat(COALESCE(((wo_master_dtl_proc_qty_rec - j.despatch_qty) - p.qtyrjk),0),'_',wo_master_dtl_proc_qty_rec) as total_wip,k.factory_name, j.despatch_qty, p.qtyrjk,
// 					k.totaloutsew,m.totalout,n.totalscan,NULL as aza
// 					from laundry_wo_master_dtl_proc a 
// 				    JOIN (select wo_no, wash_category_id from laundry_data_wo group by wo_no, wash_category_id ) b on a.wo_no=b.wo_no 
// 					JOIN m_wash_category c ON b.wash_category_id=c.wash_category_id
// 					JOIN (select COALESCE(master_type_process_id,0) as master_type_process_id,role_wo_master_id 
// 								from laundry_role_wo d where master_type_process_id = '4') as d
// 								ON a.role_wo_master_id=d.role_wo_master_id
// 					JOIN (SELECT factory_name,wo_no, color, DATE(ex_fty_date) as ex_fty_date,sum(gdp_goods) as totaloutsew
// 								FROM qrcode_gdp a
// 									join qrcode_ticketing_master b ON a.ticketid=b.ticketid
// 									join qrcode_factory_master c ON a.factory_id = c. factory_id
// 								WHERE step_id_detail = 3 GROUP BY wo_no, color, ex_fty_date,factory_name) k 
// 								ON a.wo_no = k.wo_no and a.garment_colors = k.color and DATE(a.ex_fty_date)=k.ex_fty_date
// 					JOIN (SELECT MIN ( rec_createdate ) AS firstdate,rec_status,wo_master_dtl_proc_id FROM laundry_receive 
// 								GROUP BY wo_master_dtl_proc_id,	rec_status) as e
// 								on a.wo_master_dtl_proc_id = e.wo_master_dtl_proc_id
// 					LEFT JOIN (select sum(process_qty_reject) as qtyrjk, wo_master_dtl_proc_id from laundry_process GROUP BY wo_master_dtl_proc_id) as p ON a.wo_master_dtl_proc_id=p.wo_master_dtl_proc_id
// 					LEFT JOIN (SELECT MIN( approve_bulk_createdate ) AS fbdate,	wo_no,garment_colors
// 								FROM laundry_approve_bulk WHERE lot_type = 'F'	
// 								GROUP BY wo_no,garment_colors) as f
// 								on a.wo_no = f.wo_no and a.garment_colors = f.garment_colors
// 					LEFT JOIN (SELECT wo_master_dtl_proc_id,lot_type
// 								FROM laundry_lot_number a 
// 								JOIN (select max(lot_id) as lot_id from laundry_lot_number 
// 											where lot_type = 'P' OR lot_type = 'F' and lot_status != 0) as b 
// 											ON a.lot_id=b.lot_id) as g
// 								on a.wo_master_dtl_proc_id = g.wo_master_dtl_proc_id
// 					LEFT JOIN (select (rec_createdate) as lastdate,wo_master_dtl_proc_id
// 								from laundry_receive where rec_status = 2) as h
// 								on a.wo_master_dtl_proc_id = h.wo_master_dtl_proc_id
// 					LEFT JOIN (SELECT SUM ( wo_master_dtl_desp_qty ) AS despatch_qty, u.wo_master_dtl_proc_id
// 								FROM laundry_wo_master_dtl_despatch t JOIN laundry_wo_master_dtl_proc u 
// 								on t.wo_master_dtl_proc_id=u.wo_master_dtl_proc_id
// 								GROUP BY u.wo_master_dtl_proc_id) as j 
// 								on a.wo_master_dtl_proc_id = j.wo_master_dtl_proc_id
// 					LEFT JOIN (select count(scan_id) as totalscan,wo_no,garment_colors,ex_fty_date
// 								from laundry_scan_qrcode GROUP BY wo_no,garment_colors,ex_fty_date) as n
// 								on a.wo_no = n.wo_no and a.garment_colors = n.garment_colors and DATE(a.ex_fty_date)=DATE(n.ex_fty_date)
// 					LEFT JOIN (select sum(gdp_goods) totalout,wo_no,color,DATE(ex_fty_date) as ex_fty_date
// 							   from 
// 							   (SELECT A.qrcode_key,workcenter_id,gdp_datetime,b.wo_no,b.color,b.ex_fty_date,to_timestamp( FLOOR ( ( EXTRACT ( 'epoch' FROM gdp_datetime ) / 300 ) ) * 300 ) AT TIME ZONE'UTC' + INTERVAL '5 minute' AS interval_alias,
// 							COALESCE ( smv_minute :: NUMERIC, '0' :: NUMERIC ) AS smv,gdp_goods 
// 								 FROM qrcode_gdp A 
// 									  JOIN qrcode_ticketing_master b ON A.qrcode_key = b.qrcode_key
// 									  LEFT JOIN qrcode_master_smv C ON C.smv_wono = b.wo_no 
// 								 WHERE workcenter_id = 'WAS1'
// 								) A 
// 								LEFT JOIN qrcode_schedulesshift b ON A.interval_alias BETWEEN (to_timestamp( FLOOR ( ( EXTRACT ( 'epoch' FROM b.schedulemasuk1 ) / 300 ) ) * 300 ) AT TIME ZONE'UTC' + INTERVAL '1 second' ) AND b.schedulemasuk2 AND SECTION = 'WASHING' 
// 								WHERE b.scheduledate = date(now()) 
// 								GROUP BY wo_no,color,ex_fty_date
// 					) as m ON a.wo_no = m.wo_no and a.garment_colors = m.color and DATE(a.ex_fty_date)=m.ex_fty_date
// 				) as asi 
// 				WHERE wo_no ilike '%' $cm $co $xty"; 

$joinQuery = "from (SELECT DISTINCT (a.wo_no),EXTRACT(WEEK FROM a.ex_fty_date) as week, 			
					TO_CHAR(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date,a.ex_fty_date as ex_fty_date_asli,
					a.garment_colors,a.buyer_id,c.wash_category_desc,
					CASE 
						WHEN master_type_process_id = 4 
						THEN 'DP'
						ELSE 'NON DP'
					END as master_type_process_id,
					DATE(firstdate) as firstdate,DATE(fbdate) as fbdate,t.cut_qty as cutting_qty,
					CASE 
						WHEN g.lot_type = 'P' THEN 'Pre Bulk'
						WHEN g.lot_type = 'F' THEN 'First Bulk'
						WHEN g.lot_type != '' and g.lot_type = 'F' and g.lot_type = 'P' THEN 'Production'
					END as status_wo,g.lot_type,
					DATE_PART('day', fbdate - firstdate) AS lead_bulk,wo_master_dtl_proc_qty_rec,
					(t.cut_qty - wo_master_dtl_proc_qty_rec) as sewing_balance,h.lastdate,
					concat(COALESCE(((wo_master_dtl_proc_qty_rec - j.despatch_qty) - p.qtyrjk),0),'_',wo_master_dtl_proc_qty_rec) as total_wip,k.factory_name, j.despatch_qty, p.qtyrjk,
					k.totaloutsew,m.totalout,n.totalscan,NULL as aza
					from laundry_wo_master_dtl_proc a 
				    JOIN (select wo_no, wash_category_id from laundry_data_wo group by wo_no, wash_category_id ) b on a.wo_no=b.wo_no 
					JOIN m_wash_category c ON b.wash_category_id=c.wash_category_id
					JOIN (select COALESCE(master_type_process_id,0) as master_type_process_id,role_wo_master_id 
								from laundry_role_wo d where master_type_process_id = '4') as d
								ON a.role_wo_master_id=d.role_wo_master_id
					JOIN (SELECT factory_name,wo_no, color, DATE(ex_fty_date) as ex_fty_date,sum(gdp_goods) as totaloutsew
								FROM qrcode_gdp a
									join qrcode_ticketing_master b ON a.ticketid=b.ticketid
									join qrcode_factory_master c ON a.factory_id = c. factory_id
								WHERE step_id_detail = 3 GROUP BY wo_no, color, ex_fty_date,factory_name) k 
								ON a.wo_no = k.wo_no and a.garment_colors = k.color and DATE(a.ex_fty_date)=k.ex_fty_date
					JOIN (SELECT MIN ( rec_createdate ) AS firstdate,rec_status,wo_master_dtl_proc_id FROM laundry_receive 
								GROUP BY wo_master_dtl_proc_id,	rec_status) as e
								on a.wo_master_dtl_proc_id = e.wo_master_dtl_proc_id
					LEFT JOIN (select sum(process_qty_reject) as qtyrjk, wo_master_dtl_proc_id from laundry_process GROUP BY wo_master_dtl_proc_id) as p ON a.wo_master_dtl_proc_id=p.wo_master_dtl_proc_id
					LEFT JOIN (SELECT MIN( approve_bulk_createdate ) AS fbdate,	wo_no,garment_colors
								FROM laundry_approve_bulk WHERE lot_type = 'F'	
								GROUP BY wo_no,garment_colors) as f
								on a.wo_no = f.wo_no and a.garment_colors = f.garment_colors
					LEFT JOIN (SELECT wo_master_dtl_proc_id,lot_type
								FROM laundry_lot_number a 
								JOIN (select max(lot_id) as lot_id from laundry_lot_number 
											where lot_type = 'P' OR lot_type = 'F' and lot_status != 0) as b 
											ON a.lot_id=b.lot_id) as g
								on a.wo_master_dtl_proc_id = g.wo_master_dtl_proc_id
					LEFT JOIN (select (rec_createdate) as lastdate,wo_master_dtl_proc_id
								from laundry_receive where rec_status = 2) as h
								on a.wo_master_dtl_proc_id = h.wo_master_dtl_proc_id
					LEFT JOIN (SELECT SUM ( wo_master_dtl_desp_qty ) AS despatch_qty, u.wo_master_dtl_proc_id
								FROM laundry_wo_master_dtl_despatch t JOIN laundry_wo_master_dtl_proc u 
								on t.wo_master_dtl_proc_id=u.wo_master_dtl_proc_id
								GROUP BY u.wo_master_dtl_proc_id) as j 
								on a.wo_master_dtl_proc_id = j.wo_master_dtl_proc_id
					LEFT JOIN (SELECT COUNT( scan_id ) AS totalscan,A.wo_no,garment_colors,	A.ex_fty_date FROM laundry_scan_qrcode a 
							   JOIN qrcode_ticketing_master B ON A.ticketid=ticketid and b.washtype='Wash' GROUP BY A.wo_no,
							   garment_colors,A.ex_fty_date) as n
								on a.wo_no = n.wo_no and a.garment_colors = n.garment_colors and DATE(a.ex_fty_date)=DATE(n.ex_fty_date)
					LEFT JOIN (select sum(gdp_goods) totalout,wo_no,color,DATE(ex_fty_date) as ex_fty_date
							   from 
							   (SELECT A.qrcode_key,workcenter_id,gdp_datetime,b.wo_no,b.color,b.ex_fty_date,to_timestamp( FLOOR ( ( EXTRACT ( 'epoch' FROM gdp_datetime ) / 300 ) ) * 300 ) AT TIME ZONE'UTC' + INTERVAL '5 minute' AS interval_alias,
							COALESCE ( smv_minute :: NUMERIC, '0' :: NUMERIC ) AS smv,gdp_goods 
								 FROM qrcode_gdp A 
									  JOIN qrcode_ticketing_master b ON A.ticketid = b.ticketid
									  LEFT JOIN qrcode_master_smv C ON C.smv_wono = b.wo_no 
								 WHERE workcenter_id = 'WAS1'
								) A 
								LEFT JOIN qrcode_schedulesshift b ON A.interval_alias BETWEEN (to_timestamp( FLOOR ( ( EXTRACT ( 'epoch' FROM b.schedulemasuk1 ) / 300 ) ) * 300 ) AT TIME ZONE'UTC' + INTERVAL '1 second' ) AND b.schedulemasuk2 AND SECTION = 'WASHING' 
								WHERE b.scheduledate = date(now()) 
								GROUP BY wo_no,color,ex_fty_date
					) as m ON a.wo_no = m.wo_no and a.garment_colors = m.color and DATE(a.ex_fty_date)=m.ex_fty_date
					LEFT JOIN (select COUNT(qrcode_key) as cut_qty,wo_no, color,ex_fty_date from qrcode_ticketing_master where washtype = 'Wash' GROUP BY wo_no, color,ex_fty_date) as t on a.wo_no = t.wo_no and a.garment_colors = t.color and a.ex_fty_date=t.ex_fty_date
				) as asi 
				WHERE wo_no ilike '%' $cm $co $xty"; 
$extraWhere="";

$columns = array(
	array( "db" => "wo_no", 	"dt" => 0, "field" => "wo_no"),
	array( "db" => "week",     "dt" => 1, "field" => "week" ),
	array( "db" => "factory_name",     "dt" => 2, "field" => "factory_name" ),
	array( "db" => "ex_fty_date", 	"dt" => 3, "field" => "ex_fty_date" ),
	array( "db" => "wo_no", 	"dt" => 4, "field" => "wo_no" ),
	array( "db" => "garment_colors", 	"dt" => 5, "field" => "garment_colors" ),
	array( "db" => "buyer_id", 	"dt" => 6, "field" => "buyer_id" ),
	array( "db" => "wash_category_desc", 	"dt" => 7, "field" => "wash_category_desc" ),
	array( "db" => "master_type_process_id", 	"dt" => 8, "field" => "master_type_process_id" ),
	array( "db" => "firstdate", 	"dt" => 9, "field" => "firstdate" ),
	array( "db" => "status_wo", 	"dt" => 10, "field" => "status_wo" ),
	array( "db" => "fbdate", 	"dt" => 11, "field" => "fbdate" ),
	array( "db" => "lead_bulk", 	"dt" => 12, "field" => "lead_bulk" ),
	array( "db" => "cutting_qty", 	"dt" => 13, "field" => "cutting_qty" ),
	array( "db" => "totalscan", 	"dt" => 14, "field" => "totalscan" ),
	array( "db" => "totalout", 	"dt" => 15, "field" => "totalout" ),
	array( "db" => "wo_master_dtl_proc_qty_rec", 	"dt" => 16, "field" => "wo_master_dtl_proc_qty_rec" ),
	array( "db" => "sewing_balance", 	"dt" => 17, "field" => "sewing_balance" ),
	array( "db" => "totaloutsew", 	"dt" => 18, "field" => "totaloutsew" ),
	array( "db" => "lastdate", 	"dt" => 19, "field" => "lastdate" ),
	array( "db" => "despatch_qty", 	"dt" => 20, "field" => "despatch_qty" ),
	array( "db" => "qtyrjk", 	"dt" => 21, "field" => "qtyrjk" ),
	array( "db" => "total_wip", 	"dt" => 22, "field" => "total_wip",
			'formatter' => function( $d, $row ) {
			$expwip = explode('_', $d);
			if($expwip[0] == 0){
				$wip = $expwip[1];
			} else {
				$wip = $expwip[0];
			}
			
			return "$wip";

	}),
	
	
);
//var_dump($columns);
$sql_details = array(	
); 
 
//echo "select * from $joinQuery";
echo json_encode(
	Database::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
