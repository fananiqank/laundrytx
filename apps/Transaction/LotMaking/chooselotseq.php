<option value=""></option>
<?php
require( '../../../funlibs.php' );
$con=new Database;
session_start();

//cari nilai tertinggi dari role wo name sequence
foreach ($con->select("laundry_role_wo","max(role_wo_name_seq) as maxseqlot","role_wo_master_id = '$_GET[roleq]' and master_type_process_id = 2 and role_wo_status = 1") as $ms){}   
// echo "select max(role_wo_name_seq) as maxseqlot from laundry_role_wo where role_wo_master_id = '$_GET[roleq]' and master_type_process_id = 2";
//mendapatkan berapa banyak sequence yang di tampilkan
$selseqlot = $con->select("laundry_role_wo","role_wo_id,role_wo_name,role_wo_name_seq","role_wo_master_id = '$_GET[roleq]' and master_type_process_id = 2 and role_wo_status = 1","role_wo_name_seq");     
foreach ($selseqlot as $ssq) {
?>
<option value="<?php echo $ssq[role_wo_id].'_'.$ssq[role_wo_name_seq].'_'.$ms[maxseqlot]?>"><?php echo $ssq['role_wo_name']?></option>
<?php } ?>