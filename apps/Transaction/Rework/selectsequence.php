<?php 

session_start();
include '../../../funlibs.php';
$con = new Database;

$cmt_no = $con->searchseqcmt($_GET['cm']);
$color_no2 = $con->searchseq($_GET['co']);
$colors2 = explode('Y|',$color_no2);
$colors3 = implode('&',$colors2);
$color_no = trim($colors3);
$exdate = $_GET['exdate'];
$typelot = $_GET['typelot'];

	$selseqpro = $con->select("laundry_role_wo_master a join laundry_wo_master_dtl_proc b on a.role_wo_master_id = b.role_wo_master_id","a.role_wo_master_id,a.role_wo_master_name,rework_seq,wo_master_dtl_proc_id,b.color_wash,a.role_wo_master_remark","b.wo_master_dtl_proc_status = '2' and b.wo_no = '".$cmt_no."' and b.garment_colors = '".$color_no."' and DATE(b.ex_fty_date) = '".$exdate."'");

?>
<option value=""></option>
<?php foreach ($selseqpro as $seqpro ) { ?>
  	<option value="<?php echo $seqpro[role_wo_master_id].'_'.$seqpro[wo_master_dtl_proc_id]; ?>"><?=$seqpro['role_wo_master_name'].' ('.$seqpro['color_wash'].') - '.$seqpro['rework_seq'].$seqpro['role_wo_master_remark']?></option>
<?php } ?>