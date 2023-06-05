<?php 
    
 if ($_GET['woid']){
 	session_start();
	include "../../../funlibs.php";
	$con = new Database;
	$woid = $_GET['woid'];
 	$wono = $_GET['wono'];
 }  
 else {
 	$woid = $lotnum['wo_master_dtl_proc_id'];
 	$wono = $lotnum['wo_no'];
 } 

for ($i=1;$i<=$_GET['val'];$i++) {

	$sequencecount = $con->selectcount("laundry_lot_number","lot_id","wo_master_dtl_proc_id = '$woid'");
	$sequence = $sequencecount+$i;
	$urutcount = $con->selectcount("laundry_lot_number","lot_id","wo_no = '$wono'");
	$urut = $urutcount+$i;

	$expmt = explode('/',$wo_no);
	$trimexp6 = trim($expmt[6]);

	$nolb ='L'.$expmt[0].$expmt[4].$expmt[5].$trimexp6."B".sprintf('%03s', $urut);


?>
<b><?php echo trim($nolb); ?></b>   
<input id="lot_num[]" name="lot_num[]" type="hidden" value="<?=$nolb?>">
<input id="lot_for_js_<?=$no?>" name="lot_for_js_<?=$no?>" type="hidden" value="<?=$nolb?>">

<?php } ?>