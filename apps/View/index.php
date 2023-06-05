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

function modelview(id){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
		$.get('apps/View/ViewData/modrole.php?id='+id+'&p='+pg, function(data) {
			 $('#modalagent').html(data);    
		});
}

function modelcekloths(lot){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
		$.get('apps/View/ViewData/modcekloths.php?lot='+lot, function(data) {
			 $('#modalagent').html(data);    
		});
}

function modeldtlview(lot,roleid,roledtlid,mpid,t,type,stp,cp){
	$("#funModalrec").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
		$.get('apps/View/ViewData/moddtlview.php?p='+pg+'&lot='+lot+'&roleid='+roleid+'&roledtlid='+roledtlid+'&mpid='+mpid+'&t='+t+'&type='+type+'&cp='+cp+'&stp='+stp, function(data) {
			 $('#modalrec').html(data);    
		});
}

function modellotview(id,t,type){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
		$.get('apps/View/ViewLot/modlot.php?id='+id+'&p='+hal1, function(data) {
			 $('#modalagent').html(data);    
		});
}

function modelrecdetqr(recno){
	$("#funModalrec").show();
	pg = $("#getmoddetqr").val();
		$.get('apps/View/ViewRec/moddetqr.php?rec='+recno+'&p='+pg, function(data) {
			 $('#modalrec').html(data);    
		});
}

function modelcancellot(lot,typecancel){
	$("#funModal").show();
	pg = $("#getp").val();
		$.get('apps/View/ViewRec/modcancel.php?lot='+lot+'&typecancel='+typecancel, function(data) {
			 $('#modalagent').html(data);    
		});
}

function modelqcdetqr(lotno,good,reject,rework,status){
	$("#funModalrec").show();
	pg = $("#getp").val();
		$.get('apps/View/ViewQCFinal/moddetqr.php?lotno='+lotno+'&g='+good+'&rj='+reject+'&rw='+rework+'&st='+status+'&p='+pg, function(data) {
			 $('#modalrec').html(data);    
		});
}

function modeldespdetqr(id){
	$("#funModal").show();
	pg = $("#getp").val();
		$.get('apps/View/ViewDespatch/moddetqr.php?id='+id+'&p='+pg, function(data) {
			 $('#modalagent').html(data);    
		});
}

function modelcekshipdate(id){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
	$.get('apps/Transaction/Sequence/modshipdate.php?id='+id, 
			function(data) {
			 $('#modalagent').html(data);    
	});
}

function modelremarkdate(id) {
	$("#funModalrec").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
	$.get('apps/View/ViewSeq/modremarkdate.php?id='+id, 
			function(data) {
			 $('#modalrec').html(data);    
	});
}

function modeldetail(id,t,u){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 

	$.get('apps/Transaction/Sequence/modsequence.php?id='+id+'&p='+hal+'&det='+t+'&last='+hallast, function(data) {
			$('#modalagent').html(data);    
		  }
	);
}

function modelrework(id,t,type){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
		$.get('apps/Transaction/Rework/modlot.php?id='+id+'&p='+pg, function(data) {
			 $('#modalagent').html(data);    
		});
}
</script>