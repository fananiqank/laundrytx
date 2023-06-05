<?php 
	session_start();

?>
			
					<!-- start: page -->
<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
<div class="col-md-6">
	<div class="panel-body" id="tampilrole">
		<?php include "detailrole.php"; ?>
	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-sm-9 col-sm-offset-3">
				<button id="btn-simpan" class="btn btn-primary" type="submit" onClick="return confirm('Apakah Anda yakin menyimpan data??')">Submit</button>
				<?php if($_GET[d]){ ?>
				<a href="content.php?p=<?=$get?>" class="btn btn-default">Back</a>
				<?php } else { ?>
				<a href="content.php?p=<?=$_GET[p]?>" class="btn btn-default">Reset</a>
				<?php } ?>
			</div>
		</div>
	</footer>
</div>
<div class="col-md-6">
	<div class="panel-body">
		<div class="form-group">
			<table class="table table-bordered table-striped pre-scrollable" id="datatable-ajax">
				<thead>
					<tr>
						<th>No</th>
						<th>Role Name</th>
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
<input class="form-control" name="getp" id="getp" value="<?=$_GET[p]?>" type="hidden" />
</form>