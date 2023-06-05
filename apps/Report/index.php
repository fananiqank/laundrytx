<?php
session_start();
	if ($awal != "v"){
		if($_GET[p] == 'ag1'){
			$get = "v".$_GET[p];
			$nametag = "Input Data";
		}else {
			$get = $_GET[p];
			$nametag = "Input Data";
		}
		
	} else {
		$get = substr($_GET[p],1);
		$nametag = "View Data";
	}

	if($_GET[d]){ 
		$kode = $_GET[d];
	}
	else{
		$kode = "";
	}
	
	if($_GET[d]){
		$jenis = "edit";
	} else {
		$jenis = "tambah";
	}
 ?>
				<section role="main">
					<header class="page-header">
						<h2><?php echo $title ?></h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="content.php">
										<i class="fa fa-copy"></i>
									</a>
								</li>
								<li><span><?php echo $parent ?></span></li>
								<li><span><?php echo $title ?></span></li>
							</ol>
					
							<!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
						</div>
					</header>

					<!-- start: page -->
						<div class="row">
							<div class="col-xs-12">
								
										<?php include $content ?>
							</div>
						</div>

				</section>
				
	<div class="modal fade" id="funModal" tabindex="-1" role="dialog" aria-labelledby="funModalLabel" aria-hidden="true">
		  <div class="modal-dialog" style="width: 70%" role="document">
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


<script>

function model(id){
	$("#funModal").show();
	pg = $("#hal").val();
	$.get('apps/transaction/request/modrequest.php?id='+id+'&p='+pg, function(data) {
				$('#modalagent').html(data);    
		});
	}
function modelwo(id){
	$("#funModal").show();
	pg = $("#hal").val();
	$.get('apps/transaction/wo/modwo.php?id='+id+'&p='+pg, function(data) {
				$('#modalagent').html(data);    
		});
	}
</script>