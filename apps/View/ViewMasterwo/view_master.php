	<div class="panel-body">
    
    	<form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
						<div class="form-group">
							<table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" id="datatable-ajax" width="100%">
								<thead>
									<tr>
										<th width="5%">No</th>
										<th width="20%">WO No</th>
										<th width="15%">Colors</th>
										<th width="15%">Ex Fty Date</th>
										<th width="15%">Cut Qty</th>
										<th width="15%">Status</th>
										<th width="5%">Remark Ex Fty Date</th>
										<th width="5%">Detail</th>	
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
				<input type="hidden" id="viewapp" name="viewapp" value="<?=$last?>">
				<input type="hidden" name="modsequenceeditid" id="modsequenceeditid" value="">
		</form>
	</div>