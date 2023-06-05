<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

$selcombinehold = $con->select("laundry_lot_number","lot_no,lot_qty_good_upd,combine_hold,last_lot_from_combine","combine_hold = 1");
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
									<td>No</td>
									<td>Lot</td>
									<td>Qty (Pcs)</td>
									<td>Status</td>
									<td>Act</td>
								</tr>
									<?php
									$no = 1;
									foreach ($selcombinehold as $cmhold) {
										if ($cmhold['last_lot_from_combine'] == 1){
											$keterangan = "Last Lot";
										} else {
											$keterangan = "Hold";
										}
									?>
								<tr>
									<td><?=$no;?></td>
									<td width="45%"><?php echo $cmhold['lot_no']?></td>
									<td width="30%"><?php echo $cmhold['lot_qty_good_upd']?></td>
									<td width="20%"><?php echo $keterangan; ?> </td>
									<td width="20%">
										<a href='javascript:void(0)' class="btn btn-primary" onclick="opencolaps('<?=$no?>')"><i class="fa fa-pencil"></i></a>
										<input type="text" id="tempcolaps_<?=$no?>" name="tempcolaps">
									</td>
								</tr>
								<tr id="#kolaps_<?=$no?>" style="display: none;">
									<td colspan="5"><?=$no;?></td>
								</tr>
									<?php 
										$no++;
										} 
									?>
								
							</table>
							
						</fieldset>
					</div>
				</div>
			</div>
		</div>	
	</div>				
</form>
<script type="text/javascript">
	function opencolaps(a){
		if ($('#tempcolaps_'+a).val() == 1){
			$('#tempcolaps_'+a).val(0);
			$('#kolaps_'+a).hide();	
		} else {
			$('#tempcolaps_'+a).val(1);
			$('#kolaps_'+a).show();
			
		}

	}
</script>