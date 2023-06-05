<?php 
	session_start();
	include "../../../funlibs.php";
	$con = new Database();
?>
<a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('a')">Input Data</a>
<hr>
	<div class="panel-body">
    
    	<form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
						<div class="form-group">
							<table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" id="datatable-ajax2" width="100%">
								<thead>
									<tr>
										<th width="5%">No</th>
										<th width="10%">Lot No</th>
										<th width="15%">WO No</th>
										<th width="15%">Colors</th>
										<th width="10%">Ex Fty Date</th>
										<th width="10%">Qty</th>
										<th width="5%">Detail</th>	
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
				<input type="hidden" id="viewlot" name="viewlot" value="<?=$last?>">
				<input type="hidden" name="modsequenceeditid" id="modsequenceeditid" value="">
		</form>
	</div>