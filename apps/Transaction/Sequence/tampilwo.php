<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;
$no = 1;
  $selwoker = $con->select("laundry_wo_master_keranjang","*","");
?>
	<table width="100%"  style="font-size: 12px;">
		<tr>
			<td>&nbsp;</td>
		</tr>
<?php
		foreach ($selwoker as $wk) { 
			if ($wk['status_seq'] == 1) {
				$sta = "New";
			} else {
				$sta = "Rework";
			}

			if ($wk['type_source'] == 1){
				$source= "EDI";
			} else if($wk['type_source'] == 2){
				$source= "Order";
			} else if($wk['type_source'] == 3){
				$source= "QR";
			}
?>
			<tr>
				<td width="5%" valign="top"> 
			      <input type="checkbox" id="wono_<?=$no?>" name="wono[]" value="<?php echo $wk[wo_master_keranjang_id]?>"> 
			    </td>
			    <td width="55%"><?php echo $wk['wo_no']."<br> Co.QR: ".$wk['garment_colors']."<br> Co.WS:  ".$wk['color_wash']; ?> </td>
			    <td width="30%" valign="top"><?php echo date('d-m-Y',strtotime($wk['ex_fty_date'])); ?> </td>
			    <td width="10%" valign="top"><?php echo $source; ?></td>
			    <td width="10%" valign="top"><?php echo $sta; ?> 
			    <?php 
					$selEDI = $con->select("(SELECT 
											A.wo_no,
											A.order_no,
											C.garment_colors,
											E.buyer_id,
											B.buyer_po_number
											FROM
											work_orders	A 
											JOIN wo_bpo B ON A.wo_no = B.wo_no and A.order_no=B.order_no
											JOIN order_size_breakdown C ON A.order_no = C.order_no AND B.buyer_po_number = C.buyer_po_number
											JOIN order_instructions E ON A.order_no = E.order_no
											JOIN quotation_header F ON E.quote_no = F.quote_no
											WHERE 
											A.wo_no = '$wk[wo_no]' and 
											C.garment_colors = '$wk[garment_colors]' and   
											A.buyer_id = '$wk[buyer_id]'
											GROUP BY
												A.wo_no,
												C.garment_colors,
												E.buyer_id,
												B.buyer_po_number
											) AS AB 
											JOIN exp_delivinfo_mst as AC ON AB.buyer_po_number=AC.original_buyer_po_number and AB.order_no=AC.order_no",
											"id as id_edi",
											"AC.status = 0 and buyer_po_qty != 0 and 
											 new_ex_fty_date = '$wk[ex_fty_date]'	
											 GROUP BY 
												AC.id"
										   );
					$jml = 0;
				?>
				<input type="hidden" name="idedi_<?php echo $wk[wo_master_keranjang_id]?>" id="idedi_<?php echo $wk[wo_master_keranjang_id]?>" value="<?php foreach ($selEDI as $EDI) { echo trim($EDI[id_edi]).';'; $jml++;}?>">
				<input type="hidden" name="totalidedi_<?php echo $wk[wo_master_keranjang_id]?>" id="totalidedi_<?php echo $wk[wo_master_keranjang_id]?>" value="<?php echo $jml; ?>" >
				</td>
			</tr>
<?php 
		  $no++; 
		} 
		
		$jmlker = $con->selectcount("laundry_wo_master_keranjang","wo_no as jmlker","");
?>	
		<input value="<?php echo $jmlker;?>" id="jmlwo" name="jmlwo" type="hidden" >
		<input value="<?php echo $wk[type_source];?>" id="typesource" name="typesource" type="hidden" >
	</table>