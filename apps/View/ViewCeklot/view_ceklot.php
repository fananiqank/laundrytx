<?php 
require( '../../../funlibs.php' );
$con=new Database;
session_start();

  	$select = $con->select("laundry_role_child a JOIN laundry_wo_master_dtl_proc b USING(role_wo_master_id) JOIN laundry_role_wo c using(role_wo_id) LEFT JOIN laundry_role_dtl_wo d using(role_dtl_wo_id) LEFT JOIN laundry_master_process e using(master_process_id)
  		LEFT JOIN (select wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,max(process_createdate) as process_createdate,role_wo_id,role_dtl_wo_id,COALESCE(process_qty_good,0) as process_qty_good,COALESCE(process_qty_reject,0) as process_qty_reject,COALESCE(process_qty_repair,0) as process_qty_repair,COALESCE(process_qty_std,0) as process_qty_std,sender,receiver,username
      		from laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id where lot_no = '$_GET[lot]' and process_type = 1 GROUP BY lot_no, wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,role_wo_id,role_dtl_wo_id,COALESCE ( process_qty_good, 0 ) ,COALESCE ( process_qty_reject, 0 ) ,COALESCE ( process_qty_repair, 0 ) ,COALESCE ( process_qty_std, 0 ),sender,receiver,username ) as f on CONCAT(a.role_wo_id,'_',COALESCE(a.role_dtl_wo_id,0))=CONCAT(f.role_wo_id,'_',COALESCE(f.role_dtl_wo_id,0))
      	LEFT JOIN (select wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,max(process_createdate) as process_createdate,role_wo_id,role_dtl_wo_id,COALESCE(process_qty_good,0) as process_qty_good,COALESCE(process_qty_reject,0) as process_qty_reject,COALESCE(process_qty_repair,0) as process_qty_repair,COALESCE(process_qty_std,0) as process_qty_std,foreman as foreman_start,operator as operator_start, machine_name as machine_start,username
      		from laundry_process A LEFT JOIN laundry_master_machine b using (machine_id) LEFT JOIN m_users c ON a.process_createdby=c.user_id where lot_no = '$_GET[lot]' and process_type = 2 GROUP BY lot_no, wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,role_wo_id,role_dtl_wo_id,COALESCE ( process_qty_good, 0 ) ,COALESCE ( process_qty_reject, 0 ) ,COALESCE ( process_qty_repair, 0 ) ,COALESCE ( process_qty_std, 0 ) ,foreman,OPERATOR, machine_name,username) as g on CONCAT(a.role_wo_id,'_',COALESCE(a.role_dtl_wo_id,0))=CONCAT(g.role_wo_id,'_',COALESCE(g.role_dtl_wo_id,0))
      	LEFT JOIN (select wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,max(process_createdate) as process_createdate,role_wo_id,role_dtl_wo_id,COALESCE(process_qty_good,0) as process_qty_good,COALESCE(process_qty_reject,0) as process_qty_reject,COALESCE(process_qty_repair,0) as process_qty_repair,COALESCE(process_qty_std,0) as process_qty_std,foreman as foreman_end,operator as operator_end, machine_name as machine_end,username
      		from laundry_process A LEFT JOIN laundry_master_machine USING ( machine_id ) LEFT JOIN m_users c ON a.process_createdby=c.user_id where lot_no = '$_GET[lot]' and process_type = 4 GROUP BY lot_no, wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,role_wo_id,role_dtl_wo_id,COALESCE(process_qty_good,0),COALESCE(process_qty_reject,0),COALESCE(process_qty_repair,0),COALESCE(process_qty_std,0),foreman,operator, machine_name,username) as h on CONCAT(a.role_wo_id,'_',COALESCE(a.role_dtl_wo_id,0))=CONCAT(h.role_wo_id,'_',COALESCE(h.role_dtl_wo_id,0))","c.role_wo_name,b.wo_no,	b.garment_colors,
	b.color_wash,DATE(ex_fty_date) as ex_fty_date_show,e.master_process_name,e.master_process_id,a.role_wo_id,a.role_dtl_wo_id,case when h.process_type = 4 then 'END' when g.process_type = 2 then 'START' when f.process_type = 1 then 'IN' else '' end process_type,f.process_createdate as datein,sender,receiver,g.process_createdate as datestart,g.foreman_start,g.operator_start,g.machine_start,h.process_createdate as dateend,h.foreman_end,h.operator_end,h.machine_end,
		case when h.process_qty_good <> 0 then h.process_qty_good when g.process_qty_good <> 0 then g.process_qty_good when f.process_qty_good <> 0 then f.process_qty_good else 0 end process_qty_good,
		case when h.process_qty_reject <> 0 then h.process_qty_reject when g.process_qty_reject <> 0 then g.process_qty_reject when f.process_qty_reject <> 0 then f.process_qty_reject else 0 end process_qty_reject,
		case when h.process_qty_repair <> 0 then h.process_qty_repair when g.process_qty_repair <> 0 then g.process_qty_repair when f.process_qty_repair <> 0 then f.process_qty_repair else 0 end process_qty_repair,
		case when h.process_qty_std <> 0 then h.process_qty_std when g.process_qty_std <> 0 then g.process_qty_std when f.process_qty_std <> 0 then f.process_qty_std else 0 end process_qty_std,f.username as username_in,g.username as username_start,h.username as username_end","lot_no = '$_GET[lot]'","a.role_wo_seq,a.role_dtl_wo_seq");
 //  	echo "select c.role_wo_name,b.wo_no,	b.garment_colors,
	// b.color_wash,DATE(ex_fty_date) as ex_fty_date_show,e.master_process_name,e.master_process_id,a.role_wo_id,a.role_dtl_wo_id,case when h.process_type = 4 then 'END' when g.process_type = 2 then 'START' when f.process_type = 1 then 'IN' else '' end process_type,f.process_createdate as datein,sender,receiver,g.process_createdate as datestart,g.foreman_start,g.operator_start,g.machine_start,h.process_createdate as dateend,h.foreman_end,h.operator_end,h.machine_end,
	// 	case when h.process_qty_good != '' then h.process_qty_good when g.process_qty_good != '' then g.process_qty_good when f.process_qty_good != '' then f.process_qty_good else 0 end process_qty_good,
	// 	case when h.process_qty_reject != '' then h.process_qty_reject when g.process_qty_reject != '' then g.process_qty_reject when f.process_qty_reject != '' then f.process_qty_reject else 0 end process_qty_reject,
	// 	case when h.process_qty_repair != '' then h.process_qty_repair when g.process_qty_repair != '' then g.process_qty_repair when f.process_qty_repair != '' then f.process_qty_repair else 0 end process_qty_repair,
	// 	case when h.process_qty_std != '' then h.process_qty_std when g.process_qty_std != '' then g.process_qty_std when f.process_qty_std != '' then f.process_qty_std else 0 end process_qty_std
 //  	from laundry_role_child a JOIN laundry_wo_master_dtl_proc b USING(role_wo_master_id) JOIN laundry_role_wo c using(role_wo_id) LEFT JOIN laundry_role_dtl_wo d using(role_dtl_wo_id) LEFT JOIN laundry_master_process e using(master_process_id)
 //  		LEFT JOIN (select wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,max(process_createdate) as process_createdate,role_wo_id,role_dtl_wo_id,COALESCE(process_qty_good,0) as process_qty_good,COALESCE(process_qty_reject,0) as process_qty_reject,COALESCE(process_qty_repair,0) as process_qty_repair,COALESCE(process_qty_std,0) as process_qty_std,sender,receiver
 //      		from laundry_process where lot_no = '$_GET[lot]' and process_type = 1 GROUP BY lot_no, wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,role_wo_id,role_dtl_wo_id,COALESCE ( process_qty_good, 0 ) ,COALESCE ( process_qty_reject, 0 ) ,COALESCE ( process_qty_repair, 0 ) ,COALESCE ( process_qty_std, 0 ),sender,receiver ) as f on CONCAT(a.role_wo_id,'_',a.role_dtl_wo_id)=CONCAT(f.role_wo_id,'_',f.role_dtl_wo_id)
 //      	LEFT JOIN (select wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,max(process_createdate) as process_createdate,role_wo_id,role_dtl_wo_id,COALESCE(process_qty_good,0) as process_qty_good,COALESCE(process_qty_reject,0) as process_qty_reject,COALESCE(process_qty_repair,0) as process_qty_repair,COALESCE(process_qty_std,0) as process_qty_std,foreman as foreman_start,operator as operator_start, machine_name as machine_start
 //      		from laundry_process A LEFT JOIN laundry_master_machine using (machine_id) where lot_no = '$_GET[lot]' and process_type = 2 GROUP BY lot_no, wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,role_wo_id,role_dtl_wo_id,COALESCE ( process_qty_good, 0 ) ,COALESCE ( process_qty_reject, 0 ) ,COALESCE ( process_qty_repair, 0 ) ,COALESCE ( process_qty_std, 0 ) ,foreman,OPERATOR, machine_name) as g on CONCAT(a.role_wo_id,'_',a.role_dtl_wo_id)=CONCAT(g.role_wo_id,'_',g.role_dtl_wo_id)
 //      	LEFT JOIN (select wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,max(process_createdate) as process_createdate,role_wo_id,role_dtl_wo_id,COALESCE(process_qty_good,0) as process_qty_good,COALESCE(process_qty_reject,0) as process_qty_reject,COALESCE(process_qty_repair,0) as process_qty_repair,COALESCE(process_qty_std,0) as process_qty_std,foreman as foreman_end,operator as operator_end, machine_name as machine_end
 //      		from laundry_process A LEFT JOIN laundry_master_machine USING ( machine_id ) where lot_no = '$_GET[lot]' and process_type = 4 GROUP BY lot_no, wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,role_wo_id,role_dtl_wo_id,COALESCE(process_qty_good,0),COALESCE(process_qty_reject,0),COALESCE(process_qty_repair,0),COALESCE(process_qty_std,0),foreman,operator, machine_name) as h on CONCAT(a.role_wo_id,'_',a.role_dtl_wo_id)=CONCAT(h.role_wo_id,'_',h.role_dtl_wo_id) where lot_no = '$_GET[lot]'";
  	foreach($select as $hd) {}
?>
<table class="table">
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>Lot No</td>
		<td><?=$_GET['lot']?></td>
	</tr>
	<tr>
		<td>Wo No</td>
		<td><?=$hd['wo_no']?></td>
		<td>Color EIS</td>
		<td><?=$hd['garment_colors']?></td>
	</tr>
	<tr>
		<td>Ex Fty Date</td>
		<td><?=$hd['ex_fty_date_show']?></td>
		<td>Color Wash</td>
		<td><?=$hd['color_wash']?></td>
	</tr>

</table><hr>
<form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
				<div class="form-group pre-scrollable">
					<table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" id="datatable-ajax" width="100%">
						<thead style=" position: sticky; top: 0; z-index: 1; background: #ffffff">
							<tr>
								<th width="5%">Seq</th>
								<th width="15%">Process</th>
								<th width="5%">Status</th>
								<th width="10%">IN</th>
								<th width="10%">Start</th>
								<th width="10%">End</th>
								<th width="5%">Good</th>
								<th width="5%">Rjk</th>
								<th width="5%">Rwk</th>
								<th width="5%">Std</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$no = 1;
							foreach ($select as $sl) { 
						// 		echo "(select wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,max(process_createdate) as process_createdate 
      // from laundry_process where lot_no = '$_GET[lot]' and role_wo_id = $sl[role_wo_id] and role_dtl_wo_id = $sl[role_dtl_wo_id] GROUP BY lot_no, wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type ) as asoe";
						// 		foreach($con->select("(select wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type,max(process_createdate) as process_createdate 
      // from laundry_process where lot_no = '$_GET[lot]' and role_wo_id = $sl[role_wo_id] and role_dtl_wo_id = $sl[role_dtl_wo_id] GROUP BY lot_no, wo_master_dtl_proc_id,process_qty_total,master_type_process_id,master_process_id,process_type ) as asoe","*") as $sld){}
								
							?>
							<tr>
								<td><?=$no?></td>
								<td><?=$sl['role_wo_name'].'-'.$sl['master_process_name']?></td>
								<td><?=$sl['process_type']?></td>
								<td><?=$sl['datein']."<br><br>Send: ".$sl['sender']."<br>Rec : ".$sl['receiver']."<br><br>".$sl['username_in']?></td>
								<td><?=$sl['datestart']."<br><br>FM: ".$sl['foreman_start']."<br>Op: ".$sl['operator_start']."<br>MC: ".$sl['machine_start']."<br><br>".$sl['username_start']?></td>
								<td><?=$sl['dateend']."<br><br>FM: ".$sl['foreman_end']."<br>Op: ".$sl['operator_end']."<br>MC: ".$sl['machine_end']."<br><br>".$sl['username_end']?></td>
								<td><?=$sl['process_qty_good']?></td>
								<td><?=$sl['process_qty_reject']?></td>
								<td><?=$sl['process_qty_repair']?></td>
								<td><?=$sl['process_qty_std']?></td>
							</tr>
							<?php $no ++; } ?>
						</tbody>
					</table>
				</div>
		<input type="hidden" id="viewapp" name="viewapp" value="">
		<input type="hidden" name="modsequenceeditid" id="modsequenceeditid" value="">
</form>