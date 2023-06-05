<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

if ($_GET['id']){
	foreach($con->select("laundry_wo_master_dtl_proc","wo_no,garment_colors,buyer_id,to_char(ex_fty_date, 'YYYY-MM-DD') as ex_fty_date,total_id_edi,id_edi","wo_master_dtl_proc_id = '".$_GET['id']."'") as $wo){}
	
//memecah id_edi dari db menjadi satu-satu
	$expid = explode(";",$wo['id_edi']);
//end memecah id_edi dari db menjadi satu-satu
}

foreach($con->select("laundry_wo_master_dtl_proc","DATE(ex_fty_date) as ex_fty_date,wo_master_dtl_proc_id,wo_no,garment_colors,type_source","wo_master_dtl_proc_id = '".$_GET['id']."'") as $cekproc){}

foreach($con->select("qrcode_ticketing_master","DATE(ex_fty_date) as ex_fty_date","trim(wo_no) = trim('".$cekproc['wo_no']."') and trim(color) = trim('".$cekproc['garment_colors']."') GROUP BY wo_no,color,ex_fty_date") as $cekqrcode){}
?>
<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-12 col-lg-12">
			<div class="tabs">
				<div id="overview" class="tab-pane active">
					<div id="edit" class="tab-pane">
						<fieldset>
							<?php if($_GET['task'] == 'sequence_v') { ?>
							<table width="100%" class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer">
								<thead>
									<tr>
										<td>&nbsp;</td>
										<td>EIS</td>
										<td>QRCODE</td>
										<td>Act</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Ex Fty Date</td>
										<td><?=$cekproc[ex_fty_date]?></td>
										<td><?=$cekqrcode[ex_fty_date]?></td>
										<td><?php 
											if($cekproc['ex_fty_date'] != $cekqrcode['ex_fty_date']){
												echo "<a href='javascript:void(0)' class='btn btn-danger' onclick=updproc($_GET[id],'$cekqrcode[ex_fty_date]')>Sesuaikan</a>";
											}
										?></td>
									</tr>
								</tbody>

							</table><hr>
							<?php } ?>
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
									<td><?php echo $no; ?></td>
									<td width="15%"><?php echo $wm['wo_no']; ?></td>
									<td width="15%"><?php echo $wm['garment_colors']; ?></td>
									<td width="15%"><?php echo $wm['buyer_po_number']; ?></td>
									<td width="10%"><?php echo $wm['destination']; ?></td>
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

								<input value="<?php echo $jmlwo; ?>" id="jmlwip" name="jmlwip" type="hidden">
							</table>
							
						</fieldset>
					</div>
				</div>
			</div>
		</div>	
	</div>				
</form>
