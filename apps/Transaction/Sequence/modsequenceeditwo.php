<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();
$seltypeseq = $con->select("laundry_master_type_process","master_type_process_id,master_type_process_name","master_type_process_status = '1'");
$selmodseq = $con->select("laundry_master_process","*","master_process_status = '1'");
$selrolewomas = $con->select("laundry_role_wo_master","role_wo_master_id","role_wo_master_status = 1");
    foreach ($selrolewomas as $womas) {}

$selnamemaster = $con->select("laundry_wo_master_dtl_proc a join laundry_role_wo_master b on a.role_wo_master_id=b.role_wo_master_id","b.role_wo_master_name","a.wo_master_dtl_proc_id = $_GET[id]");
foreach ($selnamemaster as $nms) {}

$seldetail = $con->select("laundry_wo_master_dtl_proc a join laundry_role_wo_master b on a.role_wo_master_id=b.role_wo_master_id 
	join laundry_role_wo c on b.role_wo_master_id=c.role_wo_master_id","c.*","a.wo_master_dtl_proc_id = $_GET[id]");


if ($_GET['id']){
	$selseqker = $con->select("laundry_role_wo","*","role_wo_id = $_GET[id]");
	foreach ($selseqker as $sqr) {}

}

if ($_GET['t'] == 1){
	$codep = "input";
	$clicksave = "onClick='saveedit($_GET[d],$_GET[idm],1)'";
} else {
	$codep = "";
	$clicksave = "onClick='saveedit($_GET[d],$_GET[idm],2)'";
}

$idrolemaster = $con->idurut("laundry_role_wo_master","role_wo_master_id");

?>	
		<span class="separator"></span>
		<input type="hidden" name="role_wo_m_id" id="role_wo_m_id" value="<?=$sqr[role_wo_master_id]?>">		
			<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
				<?php if ($_GET['det'] == 2){ ?>
					<div class="row">
                    	
						<input id="kode" name="kode" value="<?=$_GET[id]?>" type="hidden" >
						<input id="getp" name="getp" value="<?=$_GET[p]?>" type="hidden" >
						<input id="getd" name="getd" value="<?=$_GET[d]?>" type="hidden" >
						<input id="hpsdtl" name="hpsdtl" type="hidden">
						<input id="createtype" name="createtype" type="hidden" value="<?=$_GET[type]?>">
						<input id="codeproc" name="codeproc" type="hidden" value="<?=$codep?>">
						<div class="col-md-12 col-lg-12">
							<div class="tabs">
									<div id="overview" class="tab-pane active">
										<div id="edit" class="tab-pane">
											<fieldset>
											
												<div class="form-group">
												 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Type</label>
												 	<div class="col-md-6 col-lg-6">
												 		<?php 
												 			echo $sqr['role_wo_name'];
												 		?>
													</div>
													<div class="col-md-2 col-lg-2">
														&nbsp;
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Process
														
													</label>
													<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
														<?php include "sourcesequenceeditwo.php" ?>
													</div>
												  			
											  </div>
											  <div>
											</fieldset>									
										</div>
									</div>
								
							</div>
						</div>
					</div>
				<?php } else { ?>
					<div class="row">
                    	<div class="col-md-12">
                            <div class="col-md-10" style="padding: 1%;margin-bottom: 2%">
                        	</div>
							<input id="getp" name="getp" value="<?=$_GET[p]?>" type="hidden" >
							<input id="getd" name="getd" value="<?=$_GET[d]?>" type="hidden" >
							<input id="getd" name="getidm" value="<?=$_GET[idm]?>" type="hidden" >
							<input id="appr" name="appr" value="<?=$_SESSION['ID_PEG'];?>" type="hidden" >
							<input id="hpsdtl" name="hpsdtl" type="hidden">
							<input id="rolemasterid" name="rolemasterid" value="<?=$womas[role_wo_master_id]?>" type="hidden">
							<input id="codeproc" name="codeproc" type="hidden" value="<?=$codep?>">
							<div class="col-md-12 col-lg-12">
								<div class="tabs">
										<div id="overview" class="tab-pane active">
											<!-- <h4 class="mb-xlg">Personal Information</h4> -->
											<div id="edit" class="tab-pane">

												<fieldset>
													<div class="form-group">
													 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Type</label>
													 	<div class="col-md-6 col-lg-6">
													 		<?php if ($_GET['jns'] == '') { ?>
														  		<select data-plugin-selectTwo class="form-control select2 populate select2"  placeholder="None Selected" name="type" id="type" onchange="kate(this.value)">
														  			<option value="">Choose Type</option>
														    		<?php 
																		  foreach ($seltypeseq as $types) { 
																	?>
														    		<option value="<?=$types[master_type_process_id].'_'.$types[master_type_process_name]?>"><?=$types['master_type_process_name']?></option>
														    		<?php } ?>
													    		</select>
													    	<?php } else {
													    			echo $sqr['role_wo_name'];
													    		  }
													    	?>
														</div>
														<div class="col-md-2 col-lg-2">
															&nbsp;
														</div>
													</div>
													<div class="form-group">
														<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Process
															
														</label>
														<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
															<?php include "sourceprocesseditwo.php" ?>

														</div>
													  			
												  </div>
												  <div>
												</fieldset>									
											</div>
										</div>
									
								</div>

							</div>
							
							<div class="col-md-12" align="center" style="margin-top: 2%;">
									<a href="javascript:void(0)" id="btn-simpan" name="simpan" class="btn btn-primary" <?=$clicksave?> value="app" data-dismiss="modal">
									 Submit
					                </a>
							</div>
						</div>
					</div>
				<?php } ?>
				</form>
					<!-- end: page -->
<script type="text/javascript">
	$(document).ready(function() {
      $(".select2").select2();
    });
	
	$( ".row_position" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.row_position>tr').each(function() {
                selectedData.push($(this).attr("id"));
            });
            updateOrder(selectedData);
        }
    });

    function updateOrder(data) {
        $.ajax({
            url:"apps/Transaction/Sequence/ajaxPro.php?edit=1&rolemasterid="+$('#role_wo_m_id').val(),
            type:'post',
            data:{position:data},
            success:function(){
            	swal({
					  icon: "success",
					  title: 'Saved',
					  text: 'your change successfully saved',
					  timer: 1000,
					});
            	$('#tampildata').load("apps/Transaction/Sequence/tampileditwo.php?d="+$('#isid').val()+"&idm="+$('#idm').val());
            	$('#editseqwo').val(1);
            }
        })
    }
</script>