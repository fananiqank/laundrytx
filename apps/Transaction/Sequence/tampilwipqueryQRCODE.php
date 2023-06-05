<?php 
if ($cmt_no != ''){
	$cmtnoQR = $cmt_no;
} else {
	$cmtnoQR = "";
}

if ($color_no != ''){
	$colornoQR = "and AC.color like '%$color_no%'";
} else {
	$colornoQR = "";
}


if ($tglship1 == 'A' && $tglship2 == 'A'){
		$tglsQR = "";
} else if ($tglship1 == '' && $tglship2 == ''){
		$tglsQR = "";
} else {
		$tglsQR = "AND DATE(AC.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
		//and DATE(a.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
}
		$code = 3;
		$tabelwomas1 = "laundry_data_wo AB
						JOIN qrcode_ticketing_master AC on trim(AB.wo_no) = trim(AC.wo_no) ";
		$fieldwomas1 = "AB.wo_no,
						AB.order_no,
						AB.buyer_style_no,
						AC.color as garment_colors,
						AB.buyer_id,
						AC.ex_fty_date,
						'3' as type_source";
		$wherewomas1 = "AB.wo_no = '".$cmt_no."'
						$colornoQR $tglsQR
						AND concat ( AB.wo_no, '_', trim(AC.color), '_', DATE(AC.ex_fty_date)) $notin ( SELECT concat (wo_no,'_',trim(garment_colors),'_',DATE(ex_fty_date)) FROM laundry_wo_master_dtl_proc )
						AND concat ( AB.wo_no, '_', trim(AC.color), '_', DATE ( AC.ex_fty_date ) ) NOT IN ( SELECT concat ( wo_no, '_', trim(garment_colors), '_', DATE ( ex_fty_date ) ) FROM laundry_wo_master_keranjang ) 
						GROUP BY 
							AB.wo_no,
							AB.order_no,
							AC.color,
							AB.buyer_id,
							AC.ex_fty_date,
							AB.buyer_style_no";
		   	
 $selwomas1 = $con->select($tabelwomas1,$fieldwomas1,$wherewomas1,"color");
 $cekdatawo = $con->selectcount($tabelwomas1,$fieldwomas1,$wherewomas1,"color");
 if ($_GET['statusseq'] == 1){

 	$ceksdhmasuk = $con->selectcount($tabelwomas1,$fieldwomas1,"AB.wo_no = '".$cmt_no."'
						$colornoQR
						AND concat ( AB.wo_no, '_', trim(AC.color)) IN ( SELECT concat (wo_no,'_',trim(garment_colors)) 
						FROM laundry_wo_master_dtl_proc )
						GROUP BY 
							AB.wo_no,
							AB.order_no,
							AC.color,
							AB.buyer_id,
							AC.ex_fty_date,
							AB.buyer_style_no");

}
// echo "select $fieldwomas1 from $tabelwomas1 where $wherewomas1";
if ($_GET['reloseq'] != '1' && $cekdatawo == 0) {
	if ($ceksdhmasuk > 0){
		echo "<script>
					swal({
						 icon: 'info',
						 title: 'Already Create Sequence',
						 text: 'Check On View Sequence,
						 timer:2000,
					});
					$('#buyer_name').val('');
					$('#buyer_no').val('');
					$('#cmt_no').val('');
					$('#style_no').val('');
					$('#color_no').val('');
					$('#tglship').val('');
					$('#tglship2').val('');
					 </script>";
	} else {
	 	echo "<script>
					swal({
						 icon: 'info',
						 title: 'Data Not Found',
						 text: 'Check Data QrCode and Data WO',
						 timer:2000,
					});
					$('#buyer_name').val('');
					$('#buyer_no').val('');
					$('#cmt_no').val('');
					$('#style_no').val('');
					$('#color_no').val('');
					$('#tglship').val('');
					$('#tglship2').val('');
					
			   </script>";
	}
} 
	foreach ($selwomas1 as $wm){

    $cekkeranjang = $con->selectcount("laundry_wo_master_keranjang","wo_no","wo_no='".$wm['wo_no']."' and garment_colors = '".$wm['garment_colors']."' and seq_ex_fty_date='".$wm['codeseq']."'");
    		
    $cekdtlproc = $con->selectcount("laundry_wo_master_dtl_proc","wo_no","wo_no='".$wm['wo_no']."' and garment_colors = '".$wm['garment_colors']."' and seq_ex_fty_date='".$wm['codeseq']."'");

	    if ($cekkeranjang == 0) {
	    	if ($wm['jmlwo'] > 1){
	    			$seqcode = $wm['codeseq'];
	    			$showcode = $wm['codeseq'];
	   		} else {
	    			$seqcode = 'S1';
	    			$showcode = '';
	    	}
		
			if ($wm['ex_fty_date'] == ''){
				$eftydate = "";
				$checkhidden = "style='display:none;'";
			}  else {
				$eftydate = date('d-m-Y',strtotime($wm['ex_fty_date']));
				$checkhidden = "style='display:block;'";
			}

	?>
			<table width="100%"  style="font-size: 12px;">
				<tr>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td width="5%" valign="top">
			    		<input type="checkbox" id="cmtno_<?=$no?>" name="cmtno[]" value="<?php echo $wm[wo_no].'|'.$wm[garment_colors].'|'.$wm[buyer_id].'|'.$wm[ex_fty_date].'|'.$_GET[statusseq].'|'.$seqcode.'|'.$code.'|'.$wm[buyer_style_no].'|'.$color_wash; ?>" <?=$checkhidden;?>> 
					</td>
					<td width="45%" valign="top">
						<!-- <a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' style="color: #000000;" onclick="modelshipdate('<?=$wm[wo_no]?>','<?=$wm[garment_colors]?>','<?=$wm[buyer_id]?>')"> -->
							<?php echo $wm['wo_no']."<br> Co.QR: ".$wm['garment_colors']."<br> Co.WS:  ".$color_wash; ?>
						<!-- </a> -->
					</td>
					<td width="20%" valign="top"><?php echo $wm['buyer_style_no'];?></td>
					<td width="20%" valign="top"><?php echo $eftydate."<br>".$wm['destination'];?></td>
					<td width="10%" valign="top"><?php echo $showcode; ?></td>
					<td width="5%" valign="top"><?php echo $wm['type_source'].$wm['id_edi']; ?></td>
				</tr>
			</table>
<?php 
			$no++;
			$jmlwo += $countwo; 
		} 		
	}

?>
<input value="<?php echo $jmlwo;?>" id="jmlwip" name="jmlwip" type="hidden">
	
