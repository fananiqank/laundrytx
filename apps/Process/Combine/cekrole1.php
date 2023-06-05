<?php 
if ($_GET['cekdata'] == 1){
	$rolemaster = $wocolor['role_wo_master_id']; 
	$lotnumber = $wocolor['lot_no'];
	
} else {
	$rolemaster = $lotnum['role_wo_master_id'];
	$lotnumber = $lotnum['lot_no'];
}
$tablerole1 = "laundry_role_child H 
				JOIN laundry_role_wo A ON H.role_wo_id=A.role_wo_id 
   			    LEFT JOIN laundry_role_dtl_wo B ON H.role_dtl_wo_id = B.role_dtl_wo_id
				LEFT JOIN laundry_master_process C ON B.master_process_id = C.master_process_id
				LEFT JOIN (
						SELECT
							role_wo_id,
							master_process_id,
							role_dtl_wo_id,
							process_type,
							lot_no
						FROM
							laundry_process 
						WHERE
							role_wo_master_id = '".$rolemaster."'  
							and lot_no = '".$lotnumber."'
							AND master_type_process_id between 4 and 5
						GROUP BY
							role_dtl_wo_id,
							role_wo_id,
							master_process_id,
							process_type,
							lot_no 
						ORDER BY
							process_type DESC 
				) AS E ON E.role_dtl_wo_id = B.role_dtl_wo_id";
$fieldrole1 = "DISTINCT b.master_process_id,
				A.role_wo_name,
				master_process_name,
				A.role_wo_id,
				B.role_dtl_wo_id,
				E.lot_no AS dtl_lot_number,
				A.role_wo_seq,
				B.role_dtl_wo_seq,
				A.master_type_process_id,
				C.master_process_usemachine,
				C.master_process_usemultimachine,
				C.master_process_split_lot,
				c.master_process_combine_lot,
				B.role_dtl_wo_time,
				A.role_wo_time,
				H.role_wo_seq,
				H.role_dtl_wo_seq,
				E.process_type";

	$whererole1 =  "A.role_wo_master_id = '".$rolemaster."' AND H.lot_no = '".$lotnumber."' AND
					concat(E.lot_no,'_',E.role_wo_id,'_',B.master_process_id) NOT IN 
					(select concat(lot_no,'_',role_wo_id,'_',master_process_id) from laundry_process where process_type = 4) 
					AND 
					CONCAT(b.master_process_id,'_',H.role_wo_id,'_',E.lot_no) NOT IN  
						(
							select CONCAT(COALESCE(master_process_id,0),'_',role_wo_id,'_',lot_no) as master_process_id
							from laundry_process 
							where role_wo_master_id = '".$rolemaster."' and process_type = 4
							GROUP BY master_process_id,role_wo_id,lot_no
						) 
					AND 
					CONCAT(A.master_type_process_id,'_',H.role_wo_id,'_',H.lot_no) NOT IN  
						(
							select CONCAT(COALESCE(master_type_process_id,0),'_',role_wo_id,'_',lot_no) as master_type_process_id
							from laundry_process 
							where role_wo_master_id = '".$rolemaster."' and master_type_process_id NOT between 4 and 5 and process_type = 4
						)";
	foreach ($con->select($tablerole1,$fieldrole1,$whererole1,"a.role_wo_seq,b.role_dtl_wo_seq","1") as $nextstep) {}

//	echo "select $fieldrole1 from $tablerole1 where $whererole1 order by a.role_wo_seq,b.role_dtl_wo_seq limit 1";

?>