<option value=""></option>
<?php
require( '../../../funlibs.php' );
$con=new Database;
session_start();

if ($_GET['type'] == '1') {
	$where = "wo_master_dtl_proc_status = 1 and DATE_PART('Y',ex_fty_date) = DATE_PART('Y',now())";
} else {
	$where = "wo_master_dtl_proc_status = 2 and DATE_PART('Y',ex_fty_date) = DATE_PART('Y',now())";
}

        $selcmt = $con->select("laundry_wo_master_dtl_proc a 
        JOIN qrcode_workorders d on trim(a.wo_no)=trim(d.wo_no) and closed_sts <> 3
        LEFT JOIN (SELECT wo_no,garment_colors FROM laundry_receive WHERE rec_status != 0 GROUP BY wo_no,garment_colors) as c on a.wo_no=c.wo_no and a.garment_colors=c.garment_colors 
        LEFT JOIN (select role_wo_master_id,role_wo_status from laundry_role_wo where role_wo_status = 0 
        GROUP BY role_wo_master_id,role_wo_status) as b
        on a.role_wo_master_id=b.role_wo_master_id",
        "a.wo_master_dtl_proc_id,a.wo_no,COALESCE(1,role_wo_status) as app,a.garment_colors,a.role_wo_master_id,a.wo_master_dtl_proc_qty_rec,(c.wo_no) as rec,DATE(a.ex_fty_date) as ex_fty_date,to_char(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show,a.rework_seq,a.color_wash",
        $where,"wo_master_dtl_proc_id DESC");
        // echo "select a.wo_master_dtl_proc_id,a.wo_no,COALESCE(1,role_wo_status) as app,a.garment_colors,a.role_wo_master_id,a.wo_master_dtl_proc_qty_rec,(c.wo_no) as rec,DATE(a.ex_fty_date) as ex_fty_date,to_char(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show,a.rework_seq,a.color_wash from laundry_wo_master_dtl_proc a 
        // JOIN qrcode_workorders d on trim(a.wo_no)=trim(d.wo_no) and closed_sts <> 3
        // LEFT JOIN (SELECT wo_no,garment_colors FROM laundry_receive WHERE rec_status != 0 GROUP BY wo_no,garment_colors) as c on a.wo_no=c.wo_no and a.garment_colors=c.garment_colors 
        // LEFT JOIN (select role_wo_master_id,role_wo_status from laundry_role_wo where role_wo_status = 0 
        // GROUP BY role_wo_master_id,role_wo_status) as b
        // on a.role_wo_master_id=b.role_wo_master_id where $where";
foreach ($selcmt as $cmt) {
?>
<option value="<?php echo $cmt[wo_no]."_".$cmt[app]."_".$cmt[wo_master_dtl_proc_id]."_".$cmt[garment_colors]."_".$cmt[ex_fty_date]."_".$cmt[role_wo_master_id]."_".$cmt[wo_master_dtl_proc_qty_rec]."_".$cmt[rec]?>"><?php echo $cmt['wo_no'].'-'.$cmt['garment_colors']. " (".$cmt['color_wash'].") ".'-'.$cmt['ex_fty_date_show'].':'.$cmt['rework_seq']?></option>
<?php } ?>