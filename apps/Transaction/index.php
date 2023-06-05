<?php
session_start();
//echo $content;
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
						<input type="hidden" id="halaman" name="halaman" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>">
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
		        <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
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
		        <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
		        <!-- <button type="button" class="btn btn-primary">Send message</button> -->
		      </div>
		    </div>
		  </div>
		</div>


<script>

function model(id,t,u){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
		
		//input atau add pertama kali create sequence
		if (t == 1){
			if(!u){
				source = $('#typesource').val();
			} else {
				source = u;
			}
			$.get('apps/Transaction/Sequence/modsequence.php?'+pg+'&t='+t+'&type='+$('#status_seq').val()+"&source="+source, function(data) {
						$('#modalagent').html(data);    
				});
		}
		//edit role detail pada new sequence, jika ingin tambah / hapus process detail
		else if (t == 2) {
			$.get('apps/Transaction/Sequence/modsequence.php?'+pg+'&id='+id+'&d='+u+'&type='+$('#status_seq').val(), function(data) {
						$('#modalagent').html(data);    
				});
		} 
		//edit process sequence, posisi data sudah di input(bukan create new)
		else if (t == 3) {
			$.get('apps/Transaction/Sequence/modsequence.php?'+hal1+'&id='+id+'&det='+t, function(data) {
						$('#modalagent').html(data);    
				});
		}
		else if (t == 4) {
			$.get('apps/Transaction/Sequence/modsequence.php?'+pg+'&det='+t, function(data) {
						$('#modalagent').html(data);    
				});
		}
		//edit posisi sequence / ubah urutan role process
		else if (t == 5) {
			$.get('apps/Transaction/Sequence/modsequence.php?'+hal1+'&id='+id+'&det='+t, function(data) {
						$('#modalagent').html(data);    
				});
		}
		else {
			$.get('apps/Transaction/Sequence/modsequence.php?'+pg+'&t='+id+'&type='+$('#status_seq').val(), function(data) {
						$('#modalagent').html(data);    
				});
		}
	}
function modelrec(id,t){
	$("#funModal").show();
	pg = $("#getp").val();
	if (t==5){
		$.get('apps/Transaction/Receiving/modreceive.php?'+pg+'&id='+id+"&t="+t, function(data) {
				$('#modalagent').html(data);    
		});
	} else if (t==8){
		$.get('apps/Transaction/Receiving/modreceive.php?'+pg+'&id='+id+"&t="+t, function(data) {
				$('#modalagent').html(data);    
		});
	}
}

function modelcart(wo,colors,inseams,sizes){
	$("#funModalrec").show();
	pg = $("#getp").val();
		$.get('apps/Transaction/Receiving/modcart.php?'+pg+'&cm='+$("#no_cmt").val()+'&co='+$("#no_colors").val()+'&in='+$("#no_inseams").val()+'&si='+$("#no_sizes").val()+'&ex='+$("#exftydateasli").val(), function(data) {
				$('#modalrec').html(data);    
	});
}

function modelwo(id,idm,jenis,d,t){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0];
		//modal sequence 
		if(t == 2) {
			$.get('apps/Transaction/Sequence/modsequenceeditwo.php?'+hal1+'&id='+id+'&idm='+idm+'&jns='+jenis+'&d='+d+'&det='+t, function(data) {
					$('#modalagent').html(data);    
			});
		}
		else {
			$.get('apps/Transaction/Sequence/modsequenceeditwo.php?'+hal1+'&id='+id+'&idm='+idm+'&jns='+jenis+'&d='+d, function(data) {
					$('#modalagent').html(data);
			});
		}
	}
function modeladdwo(idm,d,t){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
		$.get('apps/Transaction/Sequence/modsequenceeditwo.php?'+hal1+'&idm='+idm+'&d='+d+'&t='+t+"&type="+$('#status_seq').val(), function(data) {
				$('#modalagent').html(data);    
		});
	}
function modellot(id,t,type){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
		$.get('apps/Transaction/LotMaking/modlot.php?'+hal1+'&id='+id, function(data) {
			 $('#modalagent').html(data);    
		});
}

function modelview(id){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
		$.get('apps/Transaction/ViewData/sourcetemplate.php?'+pg+'&id='+id, function(data) {
			 $('#modalagent').html(data);    
		});
}

function modelshipdate(wono,colors,buyerid){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
	//Color
 	trimcolor = colors.trim();
	expcolor = trimcolor.split('#');
		if (expcolor.length > 1) {
			expcolor1 = expcolor.join('K-');
		} else {
			expcolor1 = trimcolor;
		}
 	expcolor2 = expcolor1.split(' ');
	 	if (expcolor2.length > 1){
	 		expcolor3 = expcolor2.join('_');  
	 	} else {
	 		expcolor3 = expcolor1;
	 	}
 	expcolor4 = expcolor3.split('/');
		if (expcolor4.length > 1){
	 		color = expcolor4.join('G-');  
		} else {
	 		color = expcolor3;
	 	}
		$.get('apps/Transaction/Sequence/modshipdate.php?cmt='+wono+'&colors='+colors, 
			function(data) {
			 $('#modalagent').html(data);    
		});
}

function modelscan(typescan,id,usercode){
	$("#funModalrec").show();
	pg = $("#getp").val();
		
			$.get('apps/Transaction/Scan/modscan.php?'+pg+'&type='+typescan+"&id="+id+"&usercode="+usercode, 
			function(data) {
			 	$('#modalrec').html(data);     
			});
}

function modelscanpro(typescan,lot_id,lot,qtylast){
	$("#funModalrec").show();
	pg = $("#getp").val();
		$.get('apps/Transaction/Scan/modscan_pro.php?'+pg+'&type='+typescan+"&id="+$("#lotid").val()+"&lot="+$("#lotnumber").val()+"&qtylast="+$("#qtylast").val()+"&usercode="+$("#usercode").val(), 
			function(data) {
			 $('#modalrec').html(data);     
		});
}

function modelqc(typescan){
	$("#funModalrec").show();
	pg = $("#halaman").val();
		$.get('apps/Transaction/QCmanual/modqc.php?p='+pg+'&type='+typescan+"&lot="+$("#lot_number").val(), 
			function(data) {
			 $('#modalrec').html(data);     
		});
}

function modelqcscan(typescan,id){
	$("#funModalrec").show();
	pg = $("#getp").val();
		$.get('apps/Transaction/QC/modscan.php?'+pg+'&type='+typescan+"&id="+id, 
			function(data) {
			 $('#modalrec').html(data);     
		});
}

function modelrework(id,t,type){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
		$.get('apps/Transaction/Rework/modlot.php?'+pg+'&id='+id, function(data) {
			 $('#modalagent').html(data);    
		});
}

function modelcetak(no){
	$("#funModal").show();
	pg = $("#getp").val();
	hal1 = $("#halaman").val();
	exphal = hal1.split("_");
	hal = exphal[0]; 
	hallast = exphal[1]; 
		$.get('apps/Transaction/Scan/phpqrcode/cetaklot.php?'+hal1+'&no='+no, function(data) {
			 $('#modalagent').html(data);    
		});
}

function modeldespatch(){
	$("#funModalrec").show();
	pg = $("#getp").val();
		$.get('apps/Transaction/Despatch/modcart.php?'+pg+'&lot='+$("#lot_no").val()+'&typelot='+$("#typelot").val()+'&usercode='+$("#username").val(), function(data) {
				$('#modalrec').html(data);    
	});
}

function modeldespscan(typescan,id){
	$("#funModalrec").show();
	pg = $("#getp").val();
		$.get('apps/Transaction/DespatchScan/modscan.php?'+pg+'&type='+typescan+"&id="+id+'&lot='+$("#lot_no").val()+'&qtylast='+$("#qtylast").val()+'&rolewoid='+$("#rolewoid").val()+'&typelot='+$("#typelot").val(), 
			function(data) {
			 $('#modalrec').html(data);     
	});
}

function hold(id,status){
	$("#funModal").show();
	pg = $("#getp").val();
		$.get('apps/Transaction/Sequence/hold.php?'+pg+'&id='+id+'&sta='+status, 
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
	$.get('apps/View/ViewSeq/modremarkdate.php?'+pg+'&id='+id, 
			function(data) {
			 $('#modalrec').html(data);    
	});
}

function modelReceiveScan(id,gdpbatch) {
	if($("#usercod").val() == '') {
			swal({
				title:'User Not Found!!',
				text: 'Please Fill User',
				icon: 'warning',
			}).then((willoke) => {
				if (willoke) {
					//$('#funModalrec').hide();
					window.location.reload();
					$('#usercod').focus();

				}
			});
			
	} else {
		$("#funModalrec").show();
		pg = $("#getp").val();
		$.get('apps/Transaction/Receiving_scan/modreceive.php?'+pg+'&id='+id+'&gdp='+gdpbatch+'&usercode='+$("#usercod").val(), 
				function(data) {
				 $('#modalrec').html(data);    
		});
	}
}
// function modelReceiveScan(id) {
// 	if($("#usercod").val() == '') {
// 			swal({
// 				title:'User Not Found!!',
// 				text: 'Please Fill User',
// 				icon: 'warning',
// 			}).then((willoke) => {
// 				if (willoke) {
// 					$('#funModalrec').hide();
// 					$('#usercod').focus();
// 				}
// 			});
			
// 	} else {
// 		$("#funModalrec").show();
// 		pg = $("#getp").val();
// 		$.get('apps/Transaction/Receiving_scan/modreceive.php?'+pg+'&id='+id+'&usercode='+$("#usercod").val(), 
// 				function(data) {
// 				 $('#modalrec').html(data);    
// 		});
// 	}
// }
function modelReScrapScan(id,scanStatus) {
	if($("#usercode").val() == '') {
			swal({
				title:'User Not Found!!',
				text: 'Please Fill User',
				icon: 'warning',
			}).then((willoke) => {
				if (willoke) {
					$('#funModalrec').hide();
					$('#usercode').focus();
				}
			});
			
	} else {
		$("#funModalrec").show();
		pg = $("#getp").val();
		$.get('apps/Transaction/ReScrap_scan/modreceive.php?'+pg+'&id='+id+'&scst='+scanStatus+'&usercode='+$("#usercode").val()+'&tyseq='+$("#status_seq").val(), 
				function(data) {
				 $('#modalrec').html(data);    
		});
	}
}
</script>