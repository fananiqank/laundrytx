<?php
session_start();
	
 ?>
				<section role="main">
					<header class="page-header">
						<h2><?php echo $title ?></h2>
					
						<!-- <div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="content.php">
										<i class="fa fa-copy"></i>
									</a>
								</li>
								<li><span><?php echo $parent ?></span></li>
								<li><span><?php echo $title ?></span></li>
							</ol> -->
					
							<!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
						<!-- </div> -->
					</header>

					<!-- start: page -->
						<div class="row">
							<div class="col-xs-12">
								
										<?php include $content ?>
							</div>
						</div>
						<div id="loading" style="display: none"></div>
						<input type="hidden" id="halaman" name="halaman" value="<?=$_GET[p]?>">
				</section>
				
	<div class="modal fade" id="funModal" tabindex="-1" role="dialog" aria-labelledby="funModalLabel" aria-hidden="true">
		  <div class="modal-dialog" style="width: 50%" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="funModalLabel"><?=$title?></h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		       		<div id="modalagent"> 
		                       
		            </div>    
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <!-- <button type="button" class="btn btn-primary">Send message</button> -->
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="funModalrec" tabindex="-1" role="dialog" aria-labelledby="funModalrecLabel" aria-hidden="true">
		  <div class="modal-dialog" style="width: 100%;" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="funModalrecLabel"><?=$title?></h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		       		<div id="modalrec"> 
		                       
		            </div>    
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <!-- <button type="button" class="btn btn-primary">Send message</button> -->
		      </div>
		    </div>
		  </div>
		</div>


<script>

function model(process_id,lotno,user,forwhat,role_wo_id,qty,master_process_id,typelot){
	//alert(jmlmachine);
		$("#funModal").show();
		pg = $("#getp").val();
		hal1 = $("#halaman").val();
		exphal = hal1.split("_");
		hal = exphal[0]; 
		hallast = exphal[1]; 
		if(forwhat == 1){
			$.get('apps/Process/Dry/modmachine.php?id='+process_id+"&lot="+lotno+"&user="+user+"&rolewoid="+role_wo_id, function(data) {
							$('#modalagent').html(data);    
			});		
		} else if (forwhat == 2) {
			$.get('apps/Process/Dry/modprocess.php?machine='+process_id+"&lot="+lotno+"&user="+user+"&forwhat="+forwhat+"&mpid="+master_process_id+"&q="+qty+"&typelot="+typelot, function(data) {
							$('#modalagent').html(data);    
			});	
		} else if (forwhat == 3) {
			$.get('apps/Process/Dry/modprocess.php?machine='+process_id+"&lot="+lotno+"&user="+user+"&forwhat="+forwhat+"&q="+qty+"&typelot="+typelot, function(data) {
							$('#modalagent').html(data);    
			});	
		}
	
}

function modelend(process_end,lotno,master_type_process_id,master_process_id){
	//alert(jmlmachine);
		$("#funModal").show();
		pg = $("#getp").val();
		hal1 = $("#halaman").val();
		exphal = hal1.split("_");
		hal = exphal[0]; 
		hallast = exphal[1]; 
		$.get('apps/Process/Process/modprocess.php?process='+process_end+"&lot="+lotno+"&mtpid="+master_type_process_id+"&mpid="+master_process_id+"&qty="+$('#qty_process').val()+"&operator="+$('#operator').val()+"&machine="+$('#machineid').val(), function(data) {
				$('#modalagent').html(data);    
			});	
}

function modelscan(typescan,id,roledtlid,lot,rolewoid,qtylast){
	$("#funModalrec").show();
	pg = $("#halaman").val();
		$.get('apps/Process/Scan/modscan_pro.php?p='+pg+'&type='+typescan+"&id="+id+"&roledtlid="+roledtlid+"&lot="+lot+"&rolewoid="+rolewoid+"&qtylast="+qtylast, 
			function(data) {
			 $('#modalrec').html(data);     
		});
}

function modelqc(typescan,id,roledtlid,lot,role_wo_id,d){
	$("#funModalrec").show();
	pg = $("#halaman").val();
		$.get('apps/Process/QCmanual/modqc.php?p='+pg+'&type='+typescan+"&id="+id+"&roledtlid="+roledtlid+"&lot="+lot+"&rolewoid="+role_wo_id+"&d="+d, 
			function(data) {
			 $('#modalrec').html(data);     
		});
}

function modelcombine(){
	$("#funModalrec").show();
	pg = $("#halaman").val();
		$.get('apps/Process/Combine/modcombinehold.php?p='+pg, 
			function(data) {
			 $('#modalrec').html(data);     
		});
}

function modelchangeprocess(lot,roledtlid){
	$("#funModal").show();
	pg = $("#halaman").val();
		$.get('apps/Process/Process/modchangeprocess.php?lotno='+lot+'&mastertypeprocessid='+roledtlid, 
			function(data) {
			 $('#modalagent').html(data);     
		});
}
</script>