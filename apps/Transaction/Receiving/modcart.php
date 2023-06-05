<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();
$selrec = $con->select("laundry_wo_master", "*", "wo_no = '$_GET[wo]'");
foreach ($selrec as $wp) {
}
$selwipe = $con->select("laundry_receive_manualdtl_tmp", "COUNT(*) as jmlwip", "userid = '$_SESSION[ID_LOGIN]'");
foreach ($selwipe as $wipe) {
}
?>

<!-- Theme CSS -->
<!-- Vendor CSS -->

<span class="separator"></span>
<form class="form-user" id="formku" method="post" action="content.php?p=<?= $_GET[p] ?>_s" enctype="multipart/form-data">
	<div class="row">
		<div class="form-group">
			<div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; ">

				<div class="row" style="text-align: center;">
					<h4><b>Cart Receive Data</b></h4>
				</div>
				<div class="pre-scrollable">
					<table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax3" style="font-size: 13px;">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th width="15%">WO No</th>
								<th width="15%">Colors</th>
								<th width="5%">Inseams</th>
								<th width="5%">Sizes</th>
								<th width="5%">Ex Fty Date</th>
								<th width="5%">Qty Receive</th>
								
								<th width="5%">Act</th>
							</tr>
						</thead>
						<tbody id="tampilmodcart">
							<?php include "sourcedatamodcart.php"; ?>
						</tbody>
					</table>
					<input type="hidden" name="confmodcart" id="confmodcart" value="">
					<input type="hidden" name="hpsmodcart" id="hpsmodcart" value="">
					<input type="hidden" name="getpmodcart" id="getpmodcart" value="<?= $_GET[p] ?>">
				</div>
				<?php if ($jmldtl > 0) { ?>
					<div class="col-sm-12 col-md-12 col-lg-12" id="sumbit" style="display: block;text-align: center;">
						<a href="javascript:void(0)" class="btn btn-primary" onclick="saverec()">Submit</a>
					</div>
					<input type="hidden" name="idlast" id="idlast" value="">
				<?php } ?>
			</div>
		</div>
		<!-- <div class="col-md-12" align="center" style="margin-top: 2%;">
								<button id="btn-simpan" name="simpan" class="btn btn-primary" type="submit" <?= $clicksave ?> value="app">
								 Submit
				                </button>
						</div>
						</div> -->
	</div>
</form>

<!-- end: page -->