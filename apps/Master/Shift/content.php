<?php 
  session_start();
 //perpindahan menu
  $exppage = explode('_',$_GET['p']);
  $page = $exppage[0];  
  $last = $exppage[1];  
  $selcmt = $con->select("laundry_wo_master_dtl_proc","*","wo_master_dtl_proc_id = '$_GET[idm]'");
  foreach ($selcmt as $cmt) {}

?>
			
<!-- start: page -->
<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
	<div class="col-md-4">
		<div class="panel-body" id="tampilprocess">
			<?php include "detailshift.php"; ?>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-sm-9 col-sm-offset-3">
					<a href="javascript:void(0)" id="btn-simpan" class="btn btn-primary" onClick="simpan()">Submit</a>
					<?php if($_GET[d]){ ?>
					<a href="content.php?option=Master&task=shift&act=ugr_master" class="btn btn-default">Back</a>
					<?php } else { ?>
					<a href="content.php?option=Master&task=shift&act=ugr_master" class="btn btn-default">Reset</a>
					<?php } ?>
				</div>
			</div>
		</footer>
	</div>
	<div class="col-md-8">
		<div class="panel-body">
			<div class="form-group">
				<table class="table table-bordered table-striped pre-scrollable" id="datatable-ajax" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>Name</th>
							<th>Start</th>
							<th>End</th>
							<th>Status</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>	
	</div>
	<input class="form-control" name="peg" id="peg" value="<?=$idpegawai?>" type="hidden" />
	<input class="form-control" name="getp" id="getp" value="option=Master&task=shift&act=ugr_master" type="hidden" />
</form>