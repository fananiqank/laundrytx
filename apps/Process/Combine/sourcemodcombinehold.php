<?php 
if ($_GET['reload'] == 1){
	session_start();
	include "../../../funlibs.php";
	$con = new Database;
	foreach($con->select("laundry_lot_number","lot_no,lot_qty_good_upd,lot_id,combine_hold,last_lot_from_combine","lot_no = '".$_GET['lot']."'") as $ketlot){}

?>
<div>&nbsp;</div>
<table width="100%" class="table table-bordered table-striped pre-scrollable" id="datatable-ajax">
	
	<tr>
		<td width="25%"><b>Lot No</b></td>
		<td> : <?php echo $ketlot['lot_no'];?></td>
	</tr>
	<tr>
		<td width="25%"><b>Qty (Pcs)</b></td>
		<td> : <?php echo $ketlot['lot_qty_good_upd'];?></td>
	</tr>
	<tr>
		<td width="25%"><b>Combine Hold</b></td>
		<td> 
			<label class="switch">
				<input type="checkbox" id="setatus" name="setatus" value="1" onclick="check(this)" <?php if($ketlot['combine_hold'] == 1 && $ketlot['last_lot_from_combine'] == 0){echo "checked";} ?> >
			    <span class="slider round"></span>
			</label>
		</td>
	</tr>
	<tr>
		<td width="25%"><b>&nbsp;</b></td>
		<td> <a href="javascript:void(0)" id="save" name="save" style="display: none;" class="btn btn-success" onclick="editcombinehold('<?php echo $ketlot[lot_no]; ?>')">Submit</a></td>
		<input type="hidden" id="lot_id" name="lot_id" value="<?php echo $ketlot[lot_id]; ?>">
		<input type="hidden" id="action_mod" name="action_mod" >
	</tr>
</table>

<?php 
} else if ($_GET['reload'] == 2){
	
}
?>