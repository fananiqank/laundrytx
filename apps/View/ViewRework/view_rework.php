	<div class="panel-body">
    	<h4 align="center"><b>Rework Data</b></h4>
    	<form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
						<div class="form-group">
							<table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" id="datatable-ajax" width="100%">
								<thead>
									<tr>
										<th width="5%">No</th>
										<th width="10%">Lot No</th>
										<th width="15%">WO No</th>
										<th width="15%">Color QR</th>
										<th width="15%">Color Wash</th>
										<th width="10%">Ex Fty Date</th>
										<th width="10%">Qty</th>
										<!-- <th width="5%">Detail</th>	 -->
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
				<input type="hidden" id="viewapp" name="viewapp" value="<?php echo $last?>">
				<input type="hidden" name="modsequenceeditid" id="modsequenceeditid" value="">
		</form>
	</div>