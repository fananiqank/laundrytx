<?php
session_start();
include "../../../funlibs.php";
$con = new Database();
$cmt_no = $con->searchseqcmt($_GET['cmt']);
$color_no = $con->searchseq($_GET['colors']);

if ($denim_no == '1'){
	$denimno = "and F.wash_category_id between 5 and 8";
} else if ($denim_no == '2'){
	$denimno = "and F.wash_category_id > 8";
}

if ($buyer_no != ''){
	$buyerno = "and A.buyer_id = '".$buyer_no."'";
} else {
	$buyerno = "";
}

if ($style_no != ''){
	$styleno = "and A.buyer_style_no = '".$style_no."'";
} else {
	$styleno = "";
}

if ($cmt_no != ''){
	$cmtno = "and A.wo_no = '".$cmt_no."'";
} else {
	$cmtno = "";
}

if ($color_no != ''){
	$colorno = "and C.garment_colors = '">$color_no."'";
} else {
	$colorno = "";
}

if ($tglship1 == 'A' && $tglship2 == 'A'){
	$tgls = "";
} else if ($tglship1 == '' && $tglship2 == ''){
	$tgls = "";
} else {
	$tgls = "AND DATE(G.new_ex_fty_date) BETWEEN '".$tglship1."' AND '".$tglship2."'";
			//and DATE(a.ex_fty_date) BETWEEN '$tglship1' AND '$tglship2'";
}


if ($_GET['id']){
	foreach($con->select("laundry_wo_master_dtl_proc","wo_no,garment_colors","wo_master_dtl_proc_id = '".$_GET['id']."'") as $wo){}
	$selwomas = $con->select("
			 work_orders A 
	         JOIN wo_bpo B ON A.wo_no = B.wo_no
			 JOIN wo_sb C ON B.wo_no = C.wo_no AND B.buyer_po_number = C.buyer_po_number
			 JOIN order_instructions E ON A.order_no = E.order_no
			 JOIN quotation_header F ON E.quote_no = F.quote_no ",
			"A.wo_no,C.garment_colors,E.buyer_id,SUM ( C.quantity ) AS qty,B.ex_fty_date",
			"A.wo_no = '".$wo['wo_no']."' and C.garment_colors = '".$wo['garment_colors']."'
			GROUP BY
				A.wo_no,
				C.garment_colors,
				E.buyer_id,
				B.ex_fty_date 
			ORDER BY
				A.wo_no");
} else {
	$selwomas = $con->select("
				(SELECT 
					A.wo_no,
					A.order_no,
					C.garment_colors,
					E.buyer_id,
					B.buyer_po_number
				FROM
					work_orders	A 
					JOIN wo_bpo B ON A.wo_no = B.wo_no and A.order_no=B.order_no
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
						B.buyer_po_number
				) AS AB 
				JOIN exp_delivinfo_mst as AC ON AB.buyer_po_number=AC.original_buyer_po_number and AB.order_no=AC.order_no
				JOIN (
				 	  select count (wo_no) as jmlwo,wo_no,garment_colors 
					  from (
						  SELECT 
							 BA.wo_no,
							 BA.order_no,
							 BA.garment_colors,
							 BA.buyer_id,
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
								BB.new_ex_fty_date
							ORDER BY garment_colors
					) as CA 
					GROUP BY wo_no,garment_colors
				) as AD ON AB.wo_no=AD.wo_no and AB.garment_colors=AD.garment_colors
			",
			"AB.wo_no,
			 AB.order_no,
			 AB.garment_colors,
			 AB.buyer_id,
			 AC.new_ex_fty_date as ex_fty_date,
			 CONCAT('A',
					ROW_NUMBER() 
					OVER ( PARTITION BY AB.wo_no,AB.garment_colors 
			        ORDER BY AC.new_ex_fty_date )
			 ) AS codeseq,
			 AD.jmlwo",
			"AB.wo_no ILIKE'%' 
			 GROUP BY 
				AB.wo_no,
				AB.order_no,
				AB.garment_colors,
				AB.buyer_id,
				AC.new_ex_fty_date,
				AD.jmlwo");
}

// echo "select A.wo_no,C.garment_colors,E.buyer_id,SUM ( C.quantity ) AS qty,B.ex_fty_date from work_orders A 
// 	         JOIN wo_bpo B ON A.wo_no = B.wo_no
// 			 JOIN wo_sb C ON A.wo_no = C.wo_no 
// 			 AND B.buyer_po_number = C.buyer_po_number
// 			 JOIN order_instructions E ON A.order_no = E.order_no
// 			 JOIN quotation_header F ON E.quote_no = F.quote_no where A.wo_no = '$cmt_no' and C.garment_colors = '$color_no'
// 			GROUP BY
// 				A.wo_no,
// 				C.garment_colors,
// 				E.buyer_id,
// 				B.ex_fty_date 
// 			ORDER BY
// 				A.wo_no";
?>
<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<div class="tabs">
				<div id="overview" class="tab-pane active">
					<div id="edit" class="tab-pane">
						<fieldset>
							<table width="100%" class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer">
								<tr>
									<td>No</td>
									<td>No CMT</td>
									<td>Colors</td>
									<td>Date</td>
								</tr>
									<?php
									$no = 1;
									foreach ($selwomas as $wm) {
									?>
								<tr>
									<td><?=$no;?></td>
									<td width="45%"><?php echo $wm['wo_no']?></td>
									<td width="30%"><?php echo $wm['garment_colors']?></td>
									<td width="20%"><?php echo date('d-m-Y',strtotime($wm['ex_fty_date'])); ?> </td>
								</tr>
									<?php 
										$no++;
										$jmlwo += $countwo;
										} 
									?>
								<input value="<?php echo $jmlwo;?>" id="jmlwip" name="jmlwip" type="hidden">
							</table>
							
						</fieldset>
					</div>
				</div>
			</div>
		</div>	
	</div>				
</form>
