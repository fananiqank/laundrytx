<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

if ($_GET['id']){
	foreach($con->select("laundry_wo_master_dtl_proc","wo_no,garment_colors,ex_fty_date,status_hold","wo_master_dtl_proc_id = '$_GET[id]'") as $wo){}
	if ($wo['ex_fty_date'] != ''){
		$exdate = date('d-m-Y',strtotime($wo['ex_fty_date']));
	} else {
		$exdate = '';
	}
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
									<td>Wo No</td>
									<td><?php echo $wo['wo_no']; ?></td>
								</tr>
								<tr>
									<td>Colors</td>
									<td><?php echo $wo['garment_colors']; ?></td>
								</tr>
								<tr>
									<td>Ex Fty Date</td>
									<td><?php echo $exdate; ?></td>
								</tr>
								<tr>
									<td>Hold</td>
									<td>
										<label class="switch">
										  <input type="checkbox" id="setatus" name="setatus" value="1" <?php if($wo['status_hold'] == 1){echo "checked";} ?> >
										  <span class="slider round"></span>
										</label>
  									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-primary" onclick="savehold('<?=$_GET[id]?>')">Submit</a>
  									</td>
								</tr>
							</table>
							<input type="hidden" id="simpanhold" name="simpanhold" value="" >
						</fieldset>
					</div>
				</div>
			</div>
		</div>	
	</div>				
</form>
