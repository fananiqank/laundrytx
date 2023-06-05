<?php 
//cek data di EDI apakah ada?
$tabelcek = "(SELECT 
					 CA.wo_no,
					 CA.order_no,
					 UPPER(CA.garment_colors) as garment_colors,
					 CA.buyer_id,
					 CA.ex_fty_date
				FROM  
					(SELECT 
						A.wo_no,
						A.order_no,
						A.garment_colors,
						A.buyer_id,
						A.ex_fty_date
					FROM
						laundry_data_wo	A 
					WHERE
						A.wo_no ILIKE '%' 
						".$denimno." 
					  	".$buyerno."
					  	".$styleno."
					  	".$cmtno."
					  	".$colorno." 
					  	".$tgls."
					  	AND
						  concat ( A.wo_no, '_', A.garment_colors, '_', DATE(A.ex_fty_date)) $notin ( SELECT concat (wo_no,'_',garment_colors,'_',DATE(ex_fty_date)) FROM laundry_wo_master_dtl_proc )
						GROUP BY
							A.wo_no,
							A.garment_colors,
							A.buyer_id,
							A.order_no,
							A.ex_fty_date
					ORDER BY garment_colors
					) as CA ) as DA";

$cek = $con->selectcount($tabelcek,"wo_no");
//echo "select wo_no from $tabelcek";
//echo $cek;
// jika tidak ada data di EDI maka akan di arahkan ke data order 
if ($cek == 0){
			echo "<script>
					swal({
						 icon: 'info',
						 title: 'Data Not Found',
						 text: 'Your Search Not Found',
						 footer: '<a href>Why do I have this issue?</a>'
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
				if ($tgls != ""){
					$tgls = "AND DATE(A.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
				} else {
					$tgls = "";
				}

				$tabelwomas1 ="laundry_data_wo A
								JOIN (
										SELECT COUNT ( wo_no ) as jmlwo,wo_no 
											FROM
												(
												SELECT
													wo_no 
												FROM
													laundry_data_wo A
												WHERE
													A.wo_no ILIKE '%'
													".$denimno." 
												  	".$buyerno."
												  	".$styleno." 
												  	".$cmtno." 
												  	".$colorno."
												GROUP BY
													wo_no,
													garment_colors,
												ex_fty_date 
												) AS AB
												GROUP BY wo_no
									) AS B ON A.wo_no = B.wo_no";
				$fieldwomas1 = "A.wo_no,
								A.order_no,
								UPPER(A.garment_colors) as garment_colors,
								A.buyer_id,
								A.ex_fty_date,
								A.type_source,
								B.jmlwo ";
				$wherewomas1 = "A.wo_no ILIKE '%' 
								".$denimno." 
							  	".$buyerno."
							  	".$styleno." 
							  	".$cmtno." 
							  	".$colorno."
							  	".$tgls."
								AND concat ( A.wo_no, '_', A.garment_colors, '_', DATE(A.ex_fty_date)) $notin ( SELECT concat (	wo_no,'_',garment_colors,'_',DATE(ex_fty_date)) FROM laundry_wo_master_dtl_proc )
								AND concat ( A.wo_no, '_', A.garment_colors, '_', DATE(A.ex_fty_date)) $notin ( SELECT concat (	wo_no,'_',garment_colors,'_',DATE(ex_fty_date)) FROM laundry_wo_master_keranjang )
								GROUP BY
									A.wo_no,
									A.order_no,
									A.garment_colors,
									A.buyer_id,
									A.ex_fty_date,
									A.type_source,
									B.jmlwo";
}
	   	
 $selwomas1 = $con->select($tabelwomas1,$fieldwomas1,$wherewomas1,"garment_colors,ex_fty_date");
 //echo "select $fieldwomas1 from $tabelwomas1 where $wherewomas1";
 foreach ($selwomas1 as $wm){

    $cekkeranjang = $con->selectcount("laundry_wo_master_keranjang","wo_no","wo_no='".$wm['wo_no']."' and garment_colors = '".$wm['garment_colors']."' and seq_ex_fty_date='".$wm['codeseq']."'");
    
     if ($cekkeranjang == 0){
    	if ($wm['jmlwo'] > 1){
    			$seqcode = $wm['codeseq'];
    			$showcode = $wm['codeseq'];
   		} else {
    			$seqcode = 'S1';
    			$showcode = '';
    	}
    				
?>
		<table width="100%">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width="5%" valign="top">
		    		<input type="checkbox" id="cmtno_<?php echo $no; ?>" name="cmtno[]" value="<?php echo $wm[wo_no].'|'.$wm[garment_colors].'|'.$wm[buyer_id].'|'.$wm[ex_fty_date].'|'.$_GET[statusseq].'|'.$seqcode.'|'.$wm[type_source]; ?>"> 
				</td>
				<td width="55%" valign="top">
					<!-- <a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' style="color: #000000;" onclick="modelshipdate('<?=$wm[wo_no]?>','<?=$wm[garment_colors]?>','<?=$wm[buyer_id]?>')"> -->
						<?php echo $wm['wo_no']."<br>".$wm['garment_colors']; ?>
					<!-- </a> -->
				</td>
				<td width="30%" valign="top"><?php echo date('d-m-Y',strtotime($wm['ex_fty_date']))."<br>".$wm['destination'];?></td>
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
<!-- <input value="<?php echo $wm[type_source];?>" id="typesource" name="typesource" type="hidden"> -->
<input value="<?php echo $jmlwo;?>" id="jmlwip" name="jmlwip" type="hidden">
	
