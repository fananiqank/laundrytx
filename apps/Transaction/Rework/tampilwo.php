<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;
$no = 1;
  $selwoker = $con->select("laundry_wo_master_keranjang","*","wo_master_keranjang_createdby = '$_SESSION[ID_LOGIN]'");
?>
	<table width="100%">
		<tr>
			<td>&nbsp;</td>
		</tr>
<?php
		foreach ($selwoker as $wk) { 
?>
			<tr>
				<td width="5%"> 
			      <input type="checkbox" id="wono_<?=$no?>" name="wono[]" value="<?php echo $wk[wo_master_keranjang_id]?>"> 
			    </td>
			    <td width="45%"><?php echo $wk['wo_no'] ?> </td>
			    <td width="30%"><?php echo $wk['garment_colors']; ?> </td>
			</tr>
<?php 
		  $no++; 
		} 
		
		$jmlker = $con->selectcount("laundry_wo_master_keranjang","wo_no as jmlker","wo_master_keranjang_createdby = '$_SESSION[ID_LOGIN]'");
?>
		<input value="<?php echo $jmlker;?>" id="jmlwo" name="jmlwo" type="hidden" >
	</table>