<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

?>
<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="tabs">
				<div id="overview" class="tab-pane active">
					<div id="edit" class="tab-pane">
						<fieldset >
							<h4>Approve Data</h4>
							<table width="100%" class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer">
								<thead>
									<tr>
										<th>Wo No</th>
										<th>Lot No</th>
										<th>Qty Pcs</th>
										<th>Qty Kg</th>
										<th>Lot Type</th>
										<th>Approve</th>
									</tr>
								</thead>
								<tbody id="appr_data">
									<?php include "approve_data.php"; ?>
								</tbody >
								<input id="wo_master_dtl_proc_id" name="wo_master_dtl_proc_id" value="<?=$_GET[id]?>" type="hidden">
								<input id="wo_no" name="wo_no" value="<?=$cmt?>" type="hidden">
								<input id="garment_colors" name="garment_colors" value="<?=$colors?>" type="hidden">
								<input id="buyer_style_no" name="buyer_style_no" value="<?=$wo[buyer_style_no]?>" type="hidden">
							</table>
							
						</fieldset>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-md-6 col-lg-6">
			<div class="tabs">
				<div id="overview" class="tab-pane active">
					<div id="edit" class="tab-pane">
						<fieldset>
							<div class="col-md-2"><b>Buyer Style</b></div>
							<div class="col-md-4"><b>:<?=$wo['buyer_style_no']?></b></div>
							<div class="col-md-2"><b>Color QR</b></div>
							<div class="col-md-4"><b>:<?=$wo['garment_colors']?></b></div>
							<!-- <div class="col-md-2"><b>Ex Fty date</b></div>
							<div class="col-md-4"><b>:<?=$wo['ex_fty_date']?></b></div> -->
							<div class="col-md-2"><b>Buyer</b></div>
							<div class="col-md-4"><b>:<?=$wo['buyer_id']?></b></div>
							<div class="col-md-2"><b>Color Wash</b></div>
							<div class="col-md-4"><b>:<?=$wo['color_wash']?></b></div>
							<div class="col-md-12">&nbsp;</div>
							<table width="100%" class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer">
								<thead>
									<tr>
										<th>Wo No</th>
										<th>Lot No</th>
										<th>Pcs</th>
										<th>KG</th>
										<th>Lot Type</th>
										<th>Approve</th>
									</tr>
								</thead>
								<tbody id="appr_view">
									<?php include "approve_view.php"; ?>
								</tbody>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</div>				
</form>
