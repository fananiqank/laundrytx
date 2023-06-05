
<?php 
  session_start();
 //perpindahan menu
  $exppage = explode('_',$_GET['task']);
  $page = $exppage[0];  
  $last = $exppage[1];  
  $selcmt = $con->select("laundry_wo_master_dtl_proc","*","wo_master_dtl_proc_id = '".$_GET['idm']."'");
  foreach ($selcmt as $cmt) {}

?>
			
<!-- start: page -->
<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
	<div class="col-md-4">
		<div class="panel-body" id="tampilprocess">
			<?php include "detailmachine.php"; ?>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-sm-9 col-sm-offset-3">
					<a href="javascript:void(0)" id="btn-simpan" class="btn btn-primary" onClick="simpan()">Submit</a>
					<?php if($_GET[d]){ ?>
					<a href="javascript:void(0)" onclick="Reset()" class="btn btn-default">Back</a>
					<?php } else { ?>
					<a href="javascript:void(0)" onclick="Reset()" class="btn btn-default">Reset</a>
					<?php } ?>
				</div>
			</div>
		</footer>
	</div>
	<div class="col-md-8">
		<div class="panel-body">
			<div class="form-group">
				<div class="row">
				<div class="col-sm-12 col-sm-offset-8">
					<a href="lib/pdf-qrcode_machinex.php" id="btn-simpan" class="btn btn-primary" target="_blank">Print All Barcode Machines</a>
					
				</div>
			</div>
				<table class="table table-bordered table-striped pre-scrollable" id="datatable-ajax" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>Code</th>
							<th>Name</th>
							<th>Cat</th>
							<th>Type</th>
							<th>Detail</th>
							<th>Status</th>
							<th>Ed</th>
							<th>Pr</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>	
	</div>
	<input class="form-control" name="peg" id="peg" value="<?php echo $idpegawai; ?>" type="hidden" />
	<input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
</form>