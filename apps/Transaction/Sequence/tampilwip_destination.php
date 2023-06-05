<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

$denim_no = $_GET['de'];
$buyer_no = $con->searchseq($_GET['b']);
$style_no = $con->searchseq($_GET['s']);
$cmt_no = $con->searchseqcmt($_GET['cm']);
$color_no = $con->searchseq($_GET['co']);
$tglship1 = $_GET['tgl'];
$tglship2 = $_GET['tgl2'];

/*A.buyer_style_no = 'PT40'*/
				/*A.buyer_id = 'VANS'*/
				/*C.garment_colors = 'BLACK (ACCENT FOOTWEAR LIMITED)'*/
				/*A.wo_no = '07/E/JK/W/VANS/1226/1 '*/
				/*CASE
					WHEN D.ex_fty_date IS NULL THEN
					to_char( B.ex_fty_date, 'YYYY-mm-dd' ) ELSE to_char( D.ex_fty_date, 'YYYY-mm-dd' ) 
				END BETWEEN '2007-12-04' AND '2007-12-26'*/
$no=1;
if ($denim_no){
		if ($denim_no == '1'){
			$denimno = "and F.wash_category_id between 5 and 8";
		} else if ($denim_no == '2'){
			$denimno = "and F.wash_category_id > 8";
		}

		if ($buyer_no != ''){
			$buyerno = "and A.buyer_id = '$buyer_no'";
		} else {
			$buyerno = "";
		}

		if ($style_no != ''){
			$styleno = "and A.buyer_style_no = '$style_no'";
		} else {
			$styleno = "";
		}

		if ($cmt_no != ''){
			$cmtno = "and A.wo_no = '$cmt_no'";
		} else {
			$cmtno = "";
		}

		if ($color_no != ''){
			$colorno = "and C.garment_colors = '$color_no'";
		} else {
			$colorno = "";
		}

		if ($tglship1 == 'A' && $tglship2 == 'A'){
			$tgls = "";
		} else if ($tglship1 == '' && $tglship2 == ''){
			$tgls = "";
		} else {
			$tgls = "AND DATE(G.new_ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
			//and DATE(a.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
		}

		if ($_GET['statusseq'] == 2) {
			$notin = "IN";

		} else {
			$notin = "NOT IN";
		}

	$countwo = 1;
	$tabelcek = "(SELECT 
					 BA.wo_no,
					 BA.order_no,
					 BA.garment_colors,
					 BA.buyer_id,
					 BA.buyer_po_number,
					 BB.new_ex_fty_date
				FROM  
					(SELECT 
						A.wo_no,
						A.order_no,
						C.garment_colors,
						E.buyer_id,
						b.buyer_po_number
					FROM
						work_orders	A 
						JOIN wo_bpo B ON A.wo_no = B.wo_no and A.order_no=B.order_no
						JOIN wo_sb C ON A.wo_no = C.wo_no AND B.buyer_po_number = C.buyer_po_number
						JOIN order_instructions E ON A.order_no = E.order_no
						JOIN quotation_header F ON E.quote_no = F.quote_no
					WHERE
						A.wo_no ILIKE '%' 
						$denimno 
					  	$buyerno 
					  	$styleno 
					  	$cmtno 
					  	$colorno 
					  	$tgls
						GROUP BY
							A.wo_no,
							C.garment_colors,
							E.buyer_id,
							b.buyer_po_number
					) as BA
					JOIN exp_delivinfo_mst as BB ON BA.buyer_po_number=BB.original_buyer_po_number and BA.order_no=BB.order_no
					WHERE BB.status = 0 
					GROUP BY 
						BA.wo_no,
						BA.order_no,
						BA.garment_colors,
						BA.buyer_id,
						BA.buyer_po_number,
						BB.new_ex_fty_date
						ORDER BY garment_colors
					) as CA";
	$cek = $con->selectcount($tabelcek,"wo_no","CA.wo_no ILIKE '%' GROUP BY wo_no,garment_colors");
	//echo "select wo_no from $tabelcek where CA.wo_no ILIKE '%' GROUP BY wo_no,garment_colors";
	if ($cek == 0){
		if ($tgls != ""){
			$tgls = "AND DATE(B.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
		} else {
			$tgls = "";
		}
		$fieldcekorder = "ex_fty_date";
		$tabelcekorder = "(
							SELECT
								wo_no,
								BA.order_no,
								BA.garment_colors,
								BA.buyer_id,
								BA.buyer_po_number,
								BA.ex_fty_date,
								BA.destination 
							FROM
								(
									SELECT 
										A.wo_no,
										A.order_no,
										C.garment_colors,
										E.buyer_id,
										b.buyer_po_number,
									    b.ex_fty_date,
									    b.destination	
									FROM
										work_orders A 
										JOIN order_buyer_po B ON A.order_no = B.order_no
										JOIN wo_sb C ON A.wo_no = C.wo_no AND B.buyer_po_number = C.buyer_po_number
										JOIN order_instructions E ON A.order_no = E.order_no
										JOIN quotation_header F ON E.quote_no = F.quote_no 
									WHERE
										A.wo_no ILIKE'%' 
										$denimno 
									  	$buyerno 
									  	$styleno 
									  	$cmtno 
									  	$colorno 
									  	$tgls 
									GROUP BY
										A.wo_no,
										C.garment_colors,
										E.buyer_id,
										b.buyer_po_number,
									    b.ex_fty_date,
									    b.destination	
								) AS BA
							) AS CA ";
		$wherecekorder = " wo_no ILIKE '%' 
						GROUP BY
							wo_no,
							garment_colors,
							ex_fty_date,
							buyer_po_number,
							ex_fty_date,
							destination";
		$cekorder = $con->selectcount($tabelcekorder,$fieldcekorder,$wherecekorder);	
	//	echo "select $fieldcekorder from  $tabelcekorder where $wherecekorder";	
		$code = 2;
		if ($cekorder == 0){
			echo "<script>
				swal({
					 icon: 'info',
					 title: 'Data Not Found',
					 text: 'Your Search Not Found',
					 footer: '<a href>Why do I have this issue?</a>'
					});
				</script>";
		} else {
				$tabelwomas1 ="(
								SELECT 
								    A.wo_no,
									A.order_no,
									C.garment_colors,
									E.buyer_id,
									B.buyer_po_number,
								    B.ex_fty_date,
								    B.destination
								FROM
									work_orders A 
									JOIN order_buyer_po B ON A.order_no = B.order_no
									JOIN order_size_breakdown C ON B.order_no = C.order_no AND B.buyer_po_number = C.buyer_po_number
									JOIN order_instructions E ON A.order_no = E.order_no
									JOIN quotation_header F ON E.quote_no = F.quote_no
								WHERE
									A.wo_no ILIKE'%' 
								  	$denimno 
								  	$buyerno 
								  	$styleno 
								  	$cmtno 
								  	$colorno 
								 	$tgls
								GROUP BY
									A.wo_no,
									C.garment_colors,
									E.buyer_id,
									B.buyer_po_number,
								  	B.ex_fty_date,
								  	B.destination	
								) AS AB
								JOIN (
										SELECT COUNT
											( wo_no ) AS jmlwo,
											wo_no,
											garment_colors 
										FROM
											(
											SELECT
												BA.wo_no,
												BA.order_no,
												BA.garment_colors,
												BA.buyer_id,
												BA.ex_fty_date,
												BA.destination 
											FROM
												(
												SELECT 
												    A.wo_no,
													A.order_no,
													C.garment_colors,
													E.buyer_id,
													B.buyer_po_number,
												    B.ex_fty_date,
												    B.destination	
												FROM
													work_orders	A 
													JOIN wo_bpo B ON A.wo_no = B.wo_no AND A.order_no = B.order_no
													JOIN order_size_breakdown C ON A.order_no = C.order_no AND B.buyer_po_number = C.buyer_po_number
													JOIN order_instructions E ON A.order_no = E.order_no
													JOIN quotation_header F ON E.quote_no = F.quote_no 
												WHERE
													A.wo_no ILIKE'%' 
												  	$denimno 
												  	$buyerno 
												  	$styleno 
												  	$cmtno 
												  	$colorno 
												 	$tgls
												GROUP BY
													A.wo_no,
													C.garment_colors,
													E.buyer_id,
												    B.buyer_po_number,
													B.ex_fty_date,
													B.destination
												) AS BA
											GROUP BY
												BA.wo_no,
												BA.order_no,
												BA.garment_colors,
												BA.buyer_id,
												BA.ex_fty_date,
												BA.destination
											ORDER BY
												garment_colors 
											) AS CA 
										GROUP BY
											wo_no,
											garment_colors 
										) AS AD ON AB.wo_no = AD.wo_no AND AB.garment_colors = AD.garment_colors";
				$fieldwomas1 = "AB.wo_no,
								AB.order_no,
								AB.garment_colors,
								AB.buyer_id,
								AB.buyer_po_number,
								AB.ex_fty_date,
								AB.destination,
								CONCAT ( 'A', ROW_NUMBER ( ) OVER ( PARTITION BY AB.wo_no, AB.garment_colors ORDER BY AB.ex_fty_date ) ) AS codeseq,
								AD.jmlwo,
							  	'2' as type_source";
				$wherewomas1 = "AB.wo_no ILIKE'%' 
								GROUP BY
									AB.wo_no,
									AB.order_no,
									AB.garment_colors,
									AB.buyer_id,
									AB.buyer_po_number,
									AB.ex_fty_date,
									AB.destination,
									AD.jmlwo";
		}
	} else {
		$code = 1;
		$tabelwomas1 = "(SELECT 
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
							A.wo_no ILIKE'%' 
						  	$denimno 
						  	$buyerno 
						  	$styleno 
						  	$cmtno 
						  	$colorno 
						 	$tgls
						  	GROUP BY
								A.wo_no,
								C.garment_colors,
								E.buyer_id,
								B.buyer_po_number
						) AS AB 
						JOIN exp_delivinfo_mst as AC ON AB.buyer_po_number=AC.original_buyer_po_number and AB.order_no=AC.order_no
						JOIN ( select count (wo_no) as jmlwo,wo_no,garment_colors 
							   from (
							 		 SELECT 
										 BA.wo_no,
										 BA.order_no,
										 BA.garment_colors,
										 BA.buyer_id,
										 BA.buyer_po_number,
										 BB.new_ex_fty_date
									FROM  
										(SELECT 
											A.wo_no,
											A.order_no,
											C.garment_colors,
											E.buyer_id,
											b.buyer_po_number
									 	 FROM
											work_orders	A 
											JOIN wo_bpo B ON A.wo_no = B.wo_no and A.order_no=B.order_no
											JOIN order_size_breakdown C ON A.order_no = C.order_no AND B.buyer_po_number = C.buyer_po_number
											JOIN order_instructions E ON A.order_no = E.order_no
											JOIN quotation_header F ON E.quote_no = F.quote_no
										 WHERE
											A.wo_no ILIKE'%' 
											$denimno 
										  	$buyerno 
										  	$styleno 
										  	$cmtno 
										  	$colorno 
										  	$tgls
											GROUP BY
												A.wo_no,
												C.garment_colors,
												E.buyer_id,
												b.buyer_po_number
										) as BA
										JOIN exp_delivinfo_mst as BB ON BA.buyer_po_number=BB.original_buyer_po_number and BA.order_no=BB.order_no
										GROUP BY 
											BA.wo_no,
											BA.order_no,
											BA.garment_colors,
											BA.buyer_id,
											BA.buyer_po_number,
											BB.new_ex_fty_date,
											BB.destination
										ORDER BY garment_colors
							) as CA 
							GROUP BY wo_no,garment_colors
					 	) as AD ON AB.wo_no=AD.wo_no and AB.garment_colors=AD.garment_colors";
		$fieldwomas1 = "AB.wo_no,
						AB.order_no,
						AB.garment_colors,
						AB.buyer_id,
						AB.buyer_po_number,
						AC.id as id_edi,
						AC.destination,
						AC.new_ex_fty_date as ex_fty_date,
						CONCAT('A',
								ROW_NUMBER() 
								OVER ( PARTITION BY AB.wo_no,AB.garment_colors 
								       ORDER BY AC.new_ex_fty_date )
							  ) AS codeseq,
						AD.jmlwo,
						'1' as type_source";
		$wherewomas1 = "AC.status = 0 
						GROUP BY 
							AB.wo_no,
							AB.order_no,
							AB.garment_colors,
							AB.buyer_id,
							AB.buyer_po_number,
							AC.id,
							AC.destination,
							AC.new_ex_fty_date,
							AD.jmlwo";
	}
	
	// if ($cek == 0 && $cekorder){
	// 	echo "<script>
	// 			swal({
	// 				 icon: 'info',
	// 				 title: 'Data Not Found',
	// 				 text: 'Your Search Not Found',
	// 				 footer: '<a href>Why do I have this issue?</a>'
	// 				});
	// 			</script>";
	// }else {
		
    	
    	$selwomas1 = $con->select($tabelwomas1,$fieldwomas1,$wherewomas1,"garment_colors");
 		echo "select $fieldwomas1 from $tabelwomas1 where $wherewomas1";
    	foreach ($selwomas1 as $wm){

    		// $ceksumexftydate =  $con->selectcount("work_orders A 
						// 		JOIN wo_bpo B ON A.wo_no = B.wo_no
						// 		JOIN wo_sb C ON A.wo_no = C.wo_no AND B.buyer_po_number = C.buyer_po_number
						// 		JOIN order_instructions E ON A.order_no = E.order_no
						// 		JOIN quotation_header F ON E.quote_no = F.quote_no 
						// 		JOIN exp_delivinfo_mst G on B.buyer_po_number=G.buyer_po_number",
						// 		"wo_no",
						// 		"A.wo_no ILIKE'%' 
						// 	  	 $denimno 
						// 	  	 $buyerno 
						// 	  	 $styleno 
						// 	  	 $cmtno 
						// 	  	 $colorno 
						// 	  	 $tgls
						// 	  	 GROUP BY
						// 			A.wo_no,
						// 			C.garment_colors,
						// 			E.buyer_id,
						// 			G.new_ex_fty_date
						// 		 ORDER BY
						// 			G.new_ex_fty_date");

    		$cekkeranjang = $con->selectcount("laundry_wo_master_keranjang","wo_no","wo_no='$wm[wo_no]' and garment_colors = '$wm[garment_colors]' and seq_ex_fty_date='$wm[codeseq]'");
    		
    		$cekdtlproc = $con->selectcount("laundry_wo_master_dtl_proc","wo_no","wo_no='$wm[wo_no]' and garment_colors = '$wm[garment_colors]' and seq_ex_fty_date='$wm[codeseq]'");

    		if ($cekkeranjang == 0 && $cekdtlproc == 0) {
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
	    		<input type="checkbox" id="cmtno_<?=$no?>" name="cmtno[]" value="<?php echo $wm[wo_no].'|'.$wm[garment_colors].'|'.$wm[buyer_id].'|'.$wm[ex_fty_date].'|'.$_GET[statusseq].'|'.$seqcode; ?>"> 
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
<?php 
		$no++;
		$jmlwo += $countwo; 
			} 		
		}
?>
		<input value="<?php echo $jmlwo;?>" id="jmlwip" name="jmlwip" type="hidden">
	</table>
<?php 
	//}
}
?>


<script type="text/javascript">$('#loader-off').click();</script>