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
			$buyerno = "and A.buyer_id ILIKE '%$buyer_no%'";
		} else {
			$buyerno = "";
		}

		if ($style_no != ''){
			$styleno = "and A.buyer_style_no ILIKE '%$style_no%'";
		} else {
			$styleno = "";
		}

		if ($cmt_no != ''){
			$cmtno = "and A.wo_no = '$cmt_no'";
		} else {
			$cmtno = "";
		}

		if ($color_no != ''){
			$colorno = "and C.garment_colors ILIKE  '%$color_no%'";
		} else {
			$colorno = "";
		}

		if ($tglship1 == 'A' && $tglship2 == 'A'){
			$tgls = "";
		} else if ($tglship1 == '' && $tglship2 == ''){
			$tgls = "";
		} else {
			$tgls = "AND DATE(B.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
			//and DATE(a.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
		}

	$countwo = 1;
	$selcek = $con->select("(SELECT 
							  A.wo_no,
								C.garment_colors,
								E.buyer_id,
								SUM ( C.quantity ) AS qty 
							FROM
								work_orders
								A JOIN wo_bpo B ON A.wo_no = B.wo_no
								JOIN wo_sb C ON A.wo_no = C.wo_no 
								AND B.buyer_po_number = C.buyer_po_number
								JOIN order_instructions E ON A.order_no = E.order_no
								JOIN quotation_header F ON E.quote_no = F.quote_no 
							WHERE
								A.wo_no ILIKE'%' $denimno $buyerno $styleno $cmtno $colorno $tgls	
								AND concat ( A.wo_no, '_', C.garment_colors) NOT IN ( SELECT concat (wo_no,'_',garment_colors) FROM laundry_wo_master_dtl_proc )
							GROUP BY
								A.wo_no,
								C.garment_colors,
								E.buyer_id 
							ORDER BY
								A.wo_no )
							as ab","COUNT(wo_no) as cek");
	foreach ($selcek as $cek) {}
	if ($cek['cek'] == 0){
		echo "<script>
				swal({
					 icon: 'info',
					 title: 'Data Not Found',
					 text: 'Your Search Not Found',
					 footer: '<a href>Why do I have this issue?</a>'
					});
				</script>";
	}else {
    	$selwomas = $con->select("work_orders
							  A JOIN wo_bpo B ON A.wo_no = B.wo_no
							  JOIN wo_sb C ON A.wo_no = C.wo_no 
							  AND B.buyer_po_number = C.buyer_po_number
							  JOIN order_instructions E ON A.order_no = E.order_no
							  JOIN quotation_header F ON E.quote_no = F.quote_no ",
							  "COUNT(A.wo_no) as jmlwo,A.wo_no,C.garment_colors,E.buyer_id,SUM ( C.quantity ) AS qty,B.ex_fty_date",
							  "A.wo_no ILIKE'%' $denimno $buyerno $styleno $cmtno $colorno $tgls
								AND concat ( A.wo_no, '_', C.garment_colors) NOT IN ( SELECT concat ( wo_no, '_', garment_colors) FROM laundry_wo_master_keranjang ) 
								AND concat ( A.wo_no, '_', C.garment_colors) NOT IN ( SELECT concat (wo_no,'_',garment_colors) FROM laundry_wo_master_dtl_proc )
							  GROUP BY
								A.wo_no,
								C.garment_colors,
								E.buyer_id,
								B.ex_fty_date 
							  ORDER BY
								A.wo_no");
    	// echo "select COUNT(A.wo_no) as jmlwo,A.wo_no,C.garment_colors,E.buyer_id,SUM ( C.quantity ) AS qty,B.ex_fty_date from work_orders
					// 		  A JOIN wo_bpo B ON A.wo_no = B.wo_no
					// 		  JOIN wo_sb C ON A.wo_no = C.wo_no 
					// 		  AND B.buyer_po_number = C.buyer_po_number
					// 		  JOIN order_instructions E ON A.order_no = E.order_no
					// 		  JOIN quotation_header F ON E.quote_no = F.quote_no ",
					// 		  "COUNT(A.wo_no) as jmlwo,A.wo_no,C.garment_colors,E.buyer_id,SUM ( C.quantity ) AS qty,B.ex_fty_date where A.wo_no ILIKE'%' $denimno $buyerno $styleno $cmtno $colorno $tgls
					// 			AND concat ( A.wo_no, '_', C.garment_colors) NOT IN ( SELECT concat ( wo_no, '_', garment_colors) FROM laundry_wo_master_keranjang ) 
					// 			AND concat ( A.wo_no, '_', C.garment_colors) NOT IN ( SELECT concat (wo_no,'_',garment_colors) FROM laundry_wo_master_dtl_proc )
					// 		  GROUP BY
					// 			A.wo_no,
					// 			C.garment_colors,
					// 			E.buyer_id 
					// 		  ORDER BY
					// 			A.wo_no";
?>
	<table width="100%">
		<tr>
			<td>&nbsp;</td>
		</tr>
<?php
		foreach ($selwomas as $wm) {
		//echo count($cek['cek']);
	
?>
		<tr>
			<td width="5%">
	    		<input type="checkbox" id="cmtno_<?=$no?>" name="cmtno[]" value="<?php echo $wm[wo_no].'_'.$wm[garment_colors].'_'.$wm[qty].'_'.$wm[buyer_id].'_'.$wm[ex_fty_date]; ?>"> 
			</td>
			<td width="45%"><a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' style="color: #000000;" onclick="modelshipdate('<?=$wm[wo_no]?>','<?=$wm[garment_colors]?>','<?=$wm[buyer_id]?>')"><b> <?php echo $wm['wo_no']?></b></a></td>
			<td width="30%"><?php echo $wm['garment_colors']?></td>
		</tr>
<?php 
			$no++;
			$jmlwo += $countwo;
			} 
		
?>
			<input value="<?php echo $jmlwo;?>" id="jmlwip" name="jmlwip" type="hidden">
	</table>
<?php 
	}
}
?>


<!-- <script type="text/javascript">$('#loader-off').click();</script> -->