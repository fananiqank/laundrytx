
<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();

$exptask = explode("_", $_GET['task']);
$last = $exptask[1];

$seltypeseq = $con->select("laundry_master_type_process","master_type_process_id,master_type_process_name","master_type_process_status = '1'");

$selmodseq = $con->select("laundry_master_process","*","master_process_status = '1'");


$selrolegrupmas = $con->select("laundry_role_grup_master","role_grup_master_id,type_receive","role_grup_master_status = 1 and role_grup_master_createdby = '".$_SESSION['ID_LOGIN']."'");

foreach ($selrolegrupmas as $grupmas) {}

//pengecekan apakah role grup master memiliki turunan pada role grup
$cekrolegrup = $con->selectcount("laundry_role_grup","role_grup_id","role_grup_master_id = '".$grupmas['role_grup_master_id']."'");
// end pengecekan

$selnamemaster = $con->select("laundry_wo_master_dtl_proc a join laundry_role_grup_master b on a.role_grup_master_id=b.role_grup_master_id","b.role_grup_master_name","a.wo_master_dtl_proc_id = '".$_GET['id']."'");
foreach ($selnamemaster as $nms) {}

$seldetail = $con->select("laundry_wo_master_dtl_proc a join laundry_role_wo c on a.role_wo_master_id=c.role_wo_master_id and c.role_wo_status != 2","a.role_wo_master_id,c.role_wo_id,c.role_wo_name,master_type_process_id,role_wo_time,role_wo_seq,role_wo_status,role_wo_rev,a.wo_master_dtl_proc_status","a.wo_master_dtl_proc_id = '".$_GET['id']."'","role_wo_seq");
foreach ($seldetail as $headtail) {}

$sellog = $con->select("laundry_wo_master_dtl_proc a JOIN laundry_log b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id","COUNT(log_id) as jmllog","a.wo_master_dtl_proc_id = '".$_GET['id']."'");
foreach ($sellog as $log) {}

if ($_GET['id']){
	$selseqker = $con->select("laundry_role_grup","*","role_grup_id = '".$_GET['id']."'");
	foreach ($selseqker as $sqr) {}
}

if ($_GET['t'] == 1){
	$codep = "input";
	$clicksave = "onClick='return confirm(Apakah Anda yakin menyimpan data??)'";
} else {
	$codep = "";
	$clicksave = "onClick='saveeditnew()'";
}

$idrolemaster = $con->idurut("laundry_role_grup_master","role_grup_master_id");

//cek process apa ada process yang sudah Normal (LOT Normal (N)) dalam cmt color tersebut
$cekprocess= $con->selectcount("laundry_wo_master_dtl_proc a join laundry_lot_number b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id join laundry_process c on b.lot_no = c.lot_no","b.lot_no","b.wo_master_dtl_proc_id = '".$_GET['id']."' and b.lot_type = 'N'");

//echo $cekprocess;

?>
		<span class="separator"></span>

		<?php  
		//cek edit process sequence
		if ($_GET['det'] == 3) { 
			foreach ($con->select("laundry_wo_master_dtl_proc a 
					join laundry_role_wo_master b on a.role_wo_master_id=b.role_wo_master_id",
					"b.type_receive,a.wo_master_dtl_proc_status","a.wo_master_dtl_proc_id = '".$_GET['id']."'") as $headdet){}
			
			if($headdet['type_receive'] == '1'){
				$typereceive = "Manual";
			} else {
				$typereceive = "Scan QRCode";
			}
		?>
				<div class="row" style="padding-bottom: 3%;">
                	<div class="col-sm-12 col-md-12 col-lg-12" style="font-size: 14px;">
                		<div class="col-sm-3 col-md-3 col-lg-3">
                			Type Receive :
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b><?php echo $typereceive; ?></b>
                		</div>
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			<b>&nbsp;</b>
                		</div>
                		<div class="col-sm-3 col-md-1 col-lg-3">
                			<b>&nbsp;</b>
                		</div>
                	</div>
            	</div>
				<div class="row" style="padding-bottom: 3%;">
                	<div class="col-sm-12 col-md-12 col-lg-12" style="font-size: 14px;">
                		<div class="col-sm-3 col-md-3 col-lg-3">
                			&nbsp;
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b>Sequence</b>
                		</div>
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			<b>Time</b>
                		</div>
                		<div class="col-sm-3 col-md-1 col-lg-3">
                			<b>Approve</b>
                		</div>
                	</div>
            	</div>
            	<?php 
            	$hu = 'A';
            	foreach ($seldetail as $detail ) {  
            		if ($detail['role_wo_status'] == 1){
            			$status = "<i class='fa fa-check' aria-hidden='true' style='color:#00FF00'></i>";
            		} else {
            			$status = "<i class='fa fa-close' aria-hidden='true' style='color:#FF0000'></i>";
            		}
            	?>
            	<div class="row">
                	<div class="col-sm-12 col-md-12 col-lg-12">
                		<div class="col-sm-3 col-md-3 col-lg-3">
                			&nbsp;
                		</div>
                		<div class="col-sm-4 col-md-4 col-lg-4">
                			<b><?php echo $hu.". ".$detail['role_wo_name']?></b>
                		</div>
                		<div class="col-sm-2 col-md-2 col-lg-2">
                			<b><?php echo $detail['role_wo_time']?></b>
                		</div>
                		<div class="col-sm-3 col-md-3 col-lg-3">
                			<b><?php echo $status?></b>
                		</div>
                	</div>
            	</div>
            		<?php 
            			$no = 1;
            			$seldetdtl = $con->select("laundry_role_dtl_wo a join laundry_master_process b on a.master_process_id = b.master_process_id","master_process_name,role_dtl_wo_time","role_wo_id = '".$detail['role_wo_id']."' and role_dtl_wo_status = 1","role_dtl_wo_seq");
            			// echo "select master_process_name,role_dtl_wo_time from laundry_role_dtl_wo a join laundry_master_process b on a.master_process_id = b.master_process_id and master_process_status = 1 where role_wo_id = '".$detail['role_wo_id']."' and role_dtl_wo_status = 1";
            			foreach ($seldetdtl as $detdtl ) {  
            		?>
		            	<div class="row">
		                	<div class="col-sm-12 col-md-12  	col-lg-12">
		                		<div class="col-sm-3 col-md-3 col-lg-3">
		                			&nbsp;
		                		</div>
		                		<div class="col-sm-4 col-md-4 col-lg-4">
		                			&emsp;<?php echo $no.". ".$detdtl['master_process_name']?>
		                			
		                		</div>
		                		<div class="col-sm-2 col-md-2 col-lg-2">
		                			&emsp;<?php echo $detdtl['role_dtl_wo_time']?>
		                		</div>
		                		<div class="col-sm-3 col-md-3 col-lg-3">
		                			&nbsp;
		                		</div>
		                	</div>
		            	</div>
<?php 
		            $no++; }
		            $hu++;
		            echo "<br>";
		 	}
		 			//jika Lot number sudah menggunakan Normal maka Tidak bisa di edit
		 			
		 			if($_GET['task'] == 'sequence_v' || $_GET['task'] == 'sequence_a'){
		 			//if($_GET['task'] == 'sequence_a'){
		 				//echo $headtail['wo_master_dtl_proc_status'];
		 				// if ($cekprocess == 0 && $headtail['wo_master_dtl_proc_status'] > 0){
		 				if ($headtail['wo_master_dtl_proc_status'] > 0){
		 				 ?>		
		 				 <div class="col-sm-12 col-md-12 col-lg-12" align="center">
                			<a href="javascript:void(0)" class="btn btn-info" onclick="editrolewo('<?php echo $_GET[option]; ?>','<?php echo $_GET[task]; ?>','<?php echo $_GET[act]; ?>','<?php echo $detail[role_wo_master_id]; ?>','<?php echo $_GET[id]; ?>','<?php echo $last; ?>')">Edit</a>
                		</div>
<?php 					} 
					}
			 } else if ($_GET['det'] == 5){ ?>
					<div class="row">
                    	
						<input id="kode" name="kode" value="<?php echo $_GET[id]; ?>" type="hidden" >
						<input id="iddata" name="iddata" value="<?php echo $mg[id_data]; ?>" type="hidden" >
						<input id="notiket" name="notiket" value="<?php echo $mg[no_ticket]; ?>" type="hidden" >
						<input id="getp" name="getp" value="option=<?php echo $_GET[option]; ?>&task=sequence&act=<?php echo $_GET[act]; ?>" type="hidden" >
						<input id="getd" name="getd" value="<?php echo $_GET[d]; ?>" type="hidden" >
						<input id="appr" name="appr" value="<?php echo $_SESSION[ID_PEG]; ?>" type="hidden" >
						<input id="hpsdtl" name="hpsdtl" type="hidden">
						<input id="rolemasterid" name="rolemasterid" value="<?php echo $grupmas[role_grup_master_id]; ?>" type="hidden">
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
												 		if ($sqr['master_type_process_id']){
												 			echo $sqr['role_grup_name'];
												 		} else {
												 		?>
													  		<select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="type" id="type" onchange="kate(this.value)">
													  			<option value="">Choose Type</option>
													    		<?php 
																	  foreach ($seltypeseq as $types) { 
																?>
													    		<option value="<?php echo $types[master_type_process_id].'_'.$types[master_type_process_name]; ?>"><?php echo $types['master_type_process_name']; ?></option>
													    		<?php } ?>
												    		</select>
											    		<?php } ?>
													</div>
													<div class="col-md-2 col-lg-2">
														&nbsp;
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Process
														
													</label>
													<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
														<?php include "sourcesequence.php" ?>
													</div>
												  			
											  </div>
											  <div>
											</fieldset>									
										</div>
									</div>
								
							</div>
						</div>
					</div>
		<?php } else {?>
				
				<form class="form-user" id="formku" method="post" action="content.php?option=<?php echo $_GET[option]; ?>&task=simpan_<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" enctype="multipart/form-data">
					<div class="row">
                    	<div class="col-md-12">
							
                            <div class="col-md-12" style="padding: 1%;margin-bottom: 2%">
                    
                        	</div>
						<input id="kode" name="kode" value="<?php echo $_GET[id]; ?>" type="hidden" >
						<input id="tyrec" name="tyrec" value="<?php echo $grupmas[type_receive]; ?>" type="hidden">
						<input id="iddata" name="iddata" value="<?php echo $mg[id_data]; ?>" type="hidden" >
						<input id="notiket" name="notiket" value="<?php echo $mg[no_ticket]; ?>" type="hidden" >
						<input id="getp" name="getp" value="option=<?php echo $_GET[option]; ?>&task=sequence&act=<?php echo $_GET[act]; ?>" type="hidden" >
						<input id="getd" name="getd" value="<?php echo $_GET[d]; ?>" type="hidden" >
						<input id="appr" name="appr" value="<?php echo $_SESSION[ID_PEG]; ?>" type="hidden" >
						<input id="hpsdtl" name="hpsdtl" type="hidden">
						<input id="createtype" name="createtype" value="<?php echo $_GET[type]; ?>" type="hidden">
						<input id="rolemasterid" name="rolemasterid" value="<?php echo $grupmas[role_grup_master_id]; ?>" type="hidden">
						<input id="codeproc" name="codeproc" type="hidden" value="<?=$codep?>">
						<div class="col-md-12 col-lg-12">
							<div class="tabs">
									<div id="overview" class="tab-pane active">
										<div id="edit" class="tab-pane">

											<fieldset>
												<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Create Status</label>
													<div class="col-md-6 col-lg-6">
														<b>
														<?php 
															if ($_GET['type'] == 1){echo "New";}
															else {echo "Rework";}
														?>
														</b>
													</div>
												</div>
												<div class="form-group">
												 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Type Receive</label>
												 	<div class="col-md-6 col-lg-6">
												 		<?php 
												 		if ($grupmas['type_receive'] != '' && $cekrolegrup > 0){
												 			if($grupmas['type_receive'] == 1){
												 				echo "Manual";
												 				$trec = 1;
												 			} else {
												 				echo "Scan QRCode";
												 				$trec = 2;
												 			}
												 			echo "<input type='hidden' name='typerec' id='typerec' value='$trec'>";
												 		} else {
												 		?>
													  		<select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="typerec" id="typerec" onchange="kate(this.value)"> 
													  			<?php if ($_GET['source'] != '3'){?>
													    			<option value="1">Manual</option>
													    		<?php } else { ?>
													    			<option value="2">Scan QRCode</option>
												    			<?php } ?>
												    		</select>
											    		<?php } ?>
													</div>
													<div class="col-md-2 col-lg-2">
														&nbsp;
													</div>
												</div>
												<div class="form-group">
												 	<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Type</label>
												 	<div class="col-md-6 col-lg-6">
												 		<?php 
												 		if ($sqr['master_type_process_id']){
												 			echo $sqr['role_grup_name'];
												 		} else {
												 		?>
													  		<select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="type" id="type" onchange="kate(this.value)">
													  			<option value="">Choose Type</option>
													    		<?php 
																	  foreach ($seltypeseq as $types) { 
																?>
													    		<option value="<?php echo $types[master_type_process_id].'_'.$types[master_type_process_name]; ?>"><?php echo $types['master_type_process_name']; ?></option>
													    		<?php } 
													    		 if($_GET['type'] == 2){ 
													    			echo "<option value='200_Dry Process_PPSpray'>PP Spray Rework</option>
													    			  <option value='201_Wet Process_OzonRwk'>Ozon Rework</option>
													    			  <option value='202_Wet Process_RewashMach'>Rewash Machine</option>";
												    			 }
												    			?>
												    		</select>
											    		<?php } ?>
													</div>
													<div class="col-md-2 col-lg-2">
														&nbsp;
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-4 col-lg-4 control-label" for="profileAddress">Process
														
													</label>
													<div class="col-md-8 col-lg-8" style="background-color: #FFFFFF" id="proc">
														<?php include "sourceprocessedit.php" ?>
													</div>
												  			
											  </div>
											  <div>
											</fieldset>									
										</div>
									</div>
								
							</div>

						</div>
<?php if ($_GET['t'] == 1){ ?>
						<div class="col-md-12" align="center" style="margin-top: 2%;">
								<button id="btn-simpan" name="simpan" class="btn btn-primary" type="submit" <?php echo $clicksave; ?> value="app">
								 Submit
				                </button>
						</div>
<?php } else { ?>
						<div class="col-md-12" align="center" style="margin-top: 2%;">
								<a href="javascript:void(0)" id="btn-simpan" name="simpan" class="btn btn-primary" type="submit" <?php echo $clicksave; ?> value="app">
								 Submit
				                </a>
						</div>
<?php } ?>
						</div>
					</div>
				</form>
			<?php } ?>
					<!-- end: page -->
<script type="text/javascript">
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
            url:"apps/Transaction/Sequence/ajaxPro.php",
            type:'post',
            data:{position:data},
            success:function(){
            	swal({
					  icon: "success",
					  title: 'Saved',
					  text: 'your change successfully saved',
					  footer: '<a href>Why do I have this issue?</a>'
					});
            	$('#tampildata').load("apps/Transaction/Sequence/tampil.php");
            }
        })
    }
</script>