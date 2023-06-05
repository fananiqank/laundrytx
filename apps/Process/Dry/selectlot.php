<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

if ($_GET['id']){
	$sellotnumber = $con->select("laundry_lot_number","COUNT(lot_id) as qa","wo_master_dtl_proc_id = '$_GET[id]' and master_type_lot_id = 1");
	//echo "select COUNT(lot_id) as QA from laundry_lot_number where wo_master_dtl_proc_id = '$_GET[id]' and master_type_lot_id = 1";
    foreach ($sellotnumber as $lot){}
    	if ($lot['qa'] > '0'){
    		$where = "and master_type_lot_id > 1";
    	} else {
    		$where = "";
    	}

    $seltype = $con->select("laundry_master_type_lot","*","master_type_lot_status = 1 $where");
    //	echo "select * from laundry_master_type_lot where master_type_lot_status = 1 $where";
?>

<select data-plugin-selectTwo class="form-control populate"  placeholder="Choose One" name="type" id="type" onchange="lotnumber(this.value)">
	<option value="">Choose</option>
	<?php
    	foreach ($seltype as $tp) {
	?>
  		<option value="<?php echo $tp[master_type_lot_id]?>"><?php echo $tp['master_type_lot_name']?></option>
	<?php } ?>
</select>

<?php } ?>