<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

if ($_GET['id']){
	foreach($con->select("laundry_wo_master_dtl_proc","wo_no,garment_colors,buyer_id,to_char(ex_fty_date, 'YYYY-MM-DD') as ex_fty_date,total_id_edi,id_edi","wo_master_dtl_proc_id = '$_GET[id]'") as $wo){}
	//echo "select wo_no,garment_colors,buyer_id,to_char(ex_fty_date, 'YYYY-MM-DD') as ex_fty_date,total_id_edi,id_edi from laundry_wo_master_dtl_proc where wo_master_dtl_proc_id = '$_GET[id]'";
//memecah id_edi dari db menjadi satu-satu
	$expid = explode(";",$wo['id_edi']);
//end memecah id_edi dari db menjadi satu-satu
}

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
									<td>WO No</td>
									<td>Colors</td>
									<td>Buyer PO</td>
									<td>Destination</td>
									<td>Ex Fty Date <br>(In Laundry)</td>
									<td>New Ex Fty Date</td>
									<td>First Ex Fty Date</td>
									<td>Last Ex Fty Date</td>
								</tr>
							<?php
										$no = 1;
										$tabelwomas1 = "laundry_edi";
										$fieldwomas1 = "wo_no,
														order_no,
														garment_colors,
														buyer_id,
														buyer_po_number,
														id_edi,
														destination,
														new_ex_fty_date,
														first_new_xfty_date,
														last_new_xfty_date
														";
										$wherewomas1 = "wo_no = '".$wo['wo_no']."' and
														garment_colors = '".$wo['garment_colors']."' 
														";
										$selwomas = $con->select($tabelwomas1,$fieldwomas1,$wherewomas1,"garment_colors");
			 							//echo "select $fieldwomas1 from $tabelwomas1 where $wherewomas1";
										foreach ($selwomas as $wm) {
									?>
								<tr>
									<td><?=$no;?></td>
									<td width="15%"><?php echo $wm['wo_no']?></td>
									<td width="15%"><?php echo $wm['garment_colors']?></td>
									<td width="15%"><?php echo $wm['buyer_po_number']?></td>
									<td width="10%"><?php echo $wm['destination']?></td>
									<td width="10%"><?php echo date('d-m-Y',strtotime($wo['ex_fty_date'])); ?> </td>
									<td width="10%"><?php echo date('d-m-Y',strtotime($wm['new_ex_fty_date'])); ?> </td>
									<td width="10%">
										<?php if($wm['first_new_xfty_date'] != '') {
												echo date('d-m-Y',strtotime($wm['first_new_xfty_date'])); 
											  }
										?> 		
									</td>
									<td width="10%">
										<?php if($wm['last_new_xfty_date'] != '') {
												echo date('d-m-Y',strtotime($wm['last_new_xfty_date'])); 
											  }
										?> 	
									</td>
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
