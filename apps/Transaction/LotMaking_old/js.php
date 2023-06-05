<script>
function ucode(){
	    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	       $("#lot_no").focus();
	    }
}
function lcode () {
    	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	        var lot1 = $("#lot_no").val();
	        var lot = lot1.trim();
	        var user = $("#userid").val();
	        var usercode = $("#usercode").val();
	        var master_type_process_id = $("#mastertypeid").val();
	        var id = $("#id").val();
	        cekuser(lot,user,usercode,master_type_process_id,id);
 	   } 
}

function cekuser(lot,user,usercode,master_type_process_id,id){
		var explot = lot.split('-');
		var lotres = explot[0].substr(-4,1);
		if (lot == ''){
			swall('Lot Number Empty','Lot Number Must be Filled','error',2000);
		} else if ( usercode == ''){
			swall('User Empty','User Must be Filled','error',2000);
			$("#lot_no").val('');
		} else {
		//alert(lotres);	
		//$('#tampilcmt').load("apps/Process/Process/tampilcmt.php?lot="+lot+"&usercode="+usercode+"&typelot="+lotres+"&taskprocess=1");
			if(id == 2){
				$('#tampilcmt').load("apps/Transaction/LotMaking/tampilcmt2.php?lot="+lot+"&user="+user+"&typelot="+lotres+"&taskprocess=1");
			} else {
				$('#tampilcmt').load("apps/Transaction/LotMaking/tampilcmt3.php?lot="+lot+"&user="+user+"&typelot="+lotres+"&taskprocess=1");
			}
		}
	}

(function( $ ) {

	'use strict';

	var datatableInit = function() {
		var $table = $('#datatable-ajax');
		$table.dataTable({
			"processing": true,
         	"serverSide": true,
        	"ajax": "apps/Transaction/LotMaking/data.php",
        	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			   var index = iDisplayIndex +1;
			   $('td:eq(0)',nRow).html(index);
			   return nRow;
			},
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"order": [ 0, 'desc' ],
        });

	};
	
	$(function() {
		datatableInit();
	});

 }).apply( this, [ jQuery ]);

	function pindahData2(buyer,style,cmt,color,tgl,tgl2){
 		if (tgl != ''){
 			var tga = tgl.split('/');
 			var istga = tga[2]+"-"+tga[0]+"-"+tga[1];
 		} else {
 			var istga = 'A';
 		}
 		if (tgl2 != ''){
 			var tgb = tgl2.split('/');
 			var istgb = tgb[2]+"-"+tgb[0]+"-"+tgb[1];
 		} else {
 			var istgb = 'A';
 		}
 		//Buyer
 		trimbuyer = buyer.trim();
		expbuyer = trimbuyer.split('#');
			if (expbuyer.length > 1) {
				expbuyer1 = expbuyer.join('K-');
			} else {
				expbuyer1 = trimbuyer;
			}
 		expbuyer2 = expbuyer1.split(' ');
	 		if (expbuyer2.length > 1){
	 			expbuyer3 = expbuyer2.join('_');  
	 		} else {
	 			expbuyer3 = expbuyer1;
	 		}
 		expbuyer4 = expbuyer3.split('/');
	 		if (expbuyer4.length > 1){
	 			buyer = expbuyer4.join('G-');  
	 		} else {
	 			buyer = expbuyer3;
	 		}

	 	//CMT
 		trimcmt = cmt.trim();
		expcmt = trimcmt.split('/');
 		cmt = expcmt.join('-');

 		//Style
 		trimstyle = style.trim();
		expstyle = trimstyle.split('#');
			if (expstyle.length > 1) {
				expstyle1 = expstyle.join('K-');
			} else {
				expstyle1 = trimstyle;
			}
 		expstyle2 = expstyle1.split(' ');
	 		if (expstyle2.length > 1){
	 			expstyle3 = expstyle2.join('_');  
	 		} else {
	 			expstyle3 = expstyle1;
	 		}
 		expstyle4 = expstyle3.split('/');
	 		if (expstyle4.length > 1){
	 			style = expstyle4.join('G-');  
	 		} else {
	 			style = expstyle3;
	 		}

	 	//Color
 		trimcolor = color.trim();
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
	 			expcolor5 = expcolor4.join('G-');  
	 		} else {
	 			expcolor5 = expcolor3;
	 		}
	 	expcolor6 = expcolor5.split('&');
	 		if (expcolor6.length > 1){
	 			color = expcolor6.join('Y|');  
	 		} else {
	 			color = expcolor5;
	 		}

 		var hal = $('#hal').val();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/Sequence/tampilwip.php?b="+buyer+"&s="+style+"&cm="+cmt+"&co="+color+"&tgl="+istga+"&tgl2="+istgb,
						success: function() {
							$('#tampilwip').load("apps/Transaction/Sequence/tampilwip.php?b="+buyer+"&s="+style+"&cm="+cmt+"&co="+color+"&tgl="+istga+"&tgl2="+istgb);
							$('#no_buyer').val(buyer);
							$('#no_style').val(style);
							$('#no_cmt').val(cmt);
							$('#no_color').val(color);
							$('#tanggal1').val(istga);
							$('#tanggal2').val(istgb);
						}
					});
		//window.location="content.php?p="+hal+"&b="+buyer+"&s="+style+"&cm="+cmt+"&co="+color+"&tgl="+istga+"&tgl2="+istgb;
	}

	function enterlot(a){
		$('#tampilcontent').load("apps/Transaction/LotMaking/content_create.php");
	}

	function reclot(a){
		$('#formku').load("apps/Transaction/LotMaking/content_isi.php?id="+a);
		$('#button_rec_lot').load("apps/Transaction/LotMaking/button_rec_lot.php?id="+a);
		
	}

	function clot(id){
		trimcmt = id.trim();
 		expapp = trimcmt.split('_');
 		cmtq = expapp[0];
 		app = expapp[1];
 		id = expapp[2];
 		colorq = expapp[3];
 		exdate = expapp[4];
 		roleq = expapp[5];
 		qtyrecq = expapp[6];
 		rec = expapp[7];
		expcmt = cmtq.split('/');
 		cmts = expcmt.join('-');

		typework = $('#typework').val();
		$('#allseqlot').load("apps/Transaction/LotMaking/chooselotseq.php?roleq="+roleq);
	}

	function ccmt(id,type,allseqlot){
		//CMT
		//alert(seqlot);
 		trimcmt = id.trim();
 		expapp = trimcmt.split('_');
 		cmtq = expapp[0];
 		app = expapp[1];
 		id = expapp[2];
 		colorq = expapp[3];
 		exdate = expapp[4];
 		roleq = expapp[5];
 		qtyrecq = expapp[6];
 		rec = expapp[7];
		expcmt = cmtq.split('/');
 		cmts = expcmt.join('-');

 		trimcolor = colorq.trim();
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
			 			expcolor5 = expcolor4.join('G-');  
			 		} else {
			 			expcolor5 = expcolor3;
			 		}
	 	expcolor6 = expcolor5.split('&');
			 		if (expcolor6.length > 1){
			 			colors = expcolor6.join('Y|');  
			 		} else {
			 			colors = expcolor5;
			 		}

		typework = $('#typework').val();

 		expallseqlot = allseqlot.split('_');
 		rolewoid = expallseqlot[0];
 		seqlot = expallseqlot[1];
 		nextseqlot = parseInt(seqlot)+1;
 		if (nextseqlot <= expallseqlot[2]){
 			nextseqlot = nextseqlot;
 		} else {
 			nextseqlot = 'A';
 		}
 		
 		if (app == 0) {
 			swall('Sequence Process must be Approve','Please Check Sequence Process,\n if you are ready create lot, \nPlease Approve all Sequence Process','warning','3000');	
 			$('#tampilcmt').load("apps/Transaction/LotMaking/tampilcmt.php");
 			$('#tampilselectlot').load("apps/Transaction/LotMaking/selectlot.php");
 			$('#tampillot').load("apps/Transaction/LotMaking/tampillotnumber.php");
			$('#tampilshade').load("apps/Transaction/LotMaking/selectshade.php");
			$('#showcmt').val('');
			$('#showcolors').val('');
			$('#showexftydate').val('');
			$('#rolecmt').val('');
 			$('#idcmt').val('');
 			$('#rolewoid').val('');
			$('#seqlot').val('');
			$('#nextseqlot').val('');
 		}
 		else if (rec == '') {
 			swall('Item Not Received','Please Check Received Item','info','2000');	
 			$('#tampilcmt').load("apps/Transaction/LotMaking/tampilcmt.php");
 			$('#tampilselectlot').load("apps/Transaction/LotMaking/selectlot.php");
 			$('#tampillot').load("apps/Transaction/LotMaking/tampillotnumber.php");
			$('#tampilshade').load("apps/Transaction/LotMaking/selectshade.php");
			$('#showcmt').val('');
			$('#showcolors').val('');
			$('#showexftydate').val('');
			$('#rolecmt').val('');
 			$('#idcmt').val('');
 			$('#rolewoid').val('');
			$('#seqlot').val('');
			$('#nextseqlot').val('');
 		}
 		else {
 				$('#tampilcmt').html('<img src="./assets/images/spinner.gif">');
				showexdate = exdate.split('-');
				exdateshow = showexdate[2]+"-"+showexdate[1]+"-"+showexdate[0];
							$('#tampilcmt').load("apps/Transaction/LotMaking/tampilcmt.php?type="+type+"&rolewoid="+rolewoid+"&seqlot="+seqlot+"&col="+colors+"&exdate="+exdate+"&cmt="+cmts);
							$('#tampilselectlot').load("apps/Transaction/LotMaking/selectlot.php?id="+id);
							$('#tampillot').load("apps/Transaction/LotMaking/tampillotnumber.php");
							$('#tampilshade').load("apps/Transaction/LotMaking/selectshade.php?col="+colors+"&cmt="+cmts);
							$('#showcmt').val(cmtq);
							$('#showcolors').val(colorq);
							$('#showexftydate').val(exdateshow);
							$('#showexftydateasli').val(exdate);
							$('#rolecmt').val(roleq);
 							$('#idcmt').val(id);
 							$('#totalrec').val(qtyrecq);
 							$('#rolewoid').val(rolewoid);
 							$('#seqlot').val(seqlot);
 							$('#nextseqlot').val(nextseqlot);
 							
		}
	}

	$(function() {  
		    	//$('#nocmt').focus();
		        $( "#nocmt" ).autocomplete({
		         source: "sourcedata.php",  
		           minLength:2, 
		           select: function (event, ui) {
		            if (ui.item != undefined) {

		            	//alert('as');
		            	//alert(ui.item.id_departemen);
		                $(this).val(ui.item.value);
		                $( "#idcmt" ).val( ui.item.id );
		                //$('#selected_id').val(ui.item.id);
				       
		            	} 
		            	return false;
	        		}
		        });
		        
	});

	function cekpcs(a){
			if(parseInt(a) > parseInt($('#sisalotout').val())){
				swall('Qty more than receive','Qty Lot More than Balance Qty Lot','info','2000');				
				$('#pcs').val('');
			} 
			
	}

	function lotnumber(id){
			idcmt = $('#idcmt').val();
			showcmt = $('#showcmt').val();
			lastrec = $('#lastrec').val();
			trimcmt = showcmt.trim();
			expcmt = trimcmt.split('/');
	 		cmt = expcmt.join('-');
			$.ajax({
				type: 'GET',
				url:  "apps/Transaction/LotMaking/tampillotnumber.php?id="+id+"&cmt="+cmt+"&idc="+idcmt,
				success: function() {
					$('#tampillot').html('<img src="./assets/images/spinner.gif">');
					$('#tampillot').load("apps/Transaction/LotMaking/tampillotnumber.php?id="+id+"&cmt="+cmt+"&idc="+idcmt);
					$('#tampilshade').load("apps/Transaction/LotMaking/selectshade.php?lastrec="+lastrec+"&col="+colors+"&cmt="+cmts);
					$('#ceknolot').val(1);
				}
			});
	}
	
	function savelot(){
		//alert($('#ceknolot').val());
		if ($('#ceknolot').val() != '1'){
			swall("Data Not Complete","","error","2000");
		} 
		else if ($('#pcs').val() == ''){
			swall("Data Not Complete","","error","2000");
		} 
		else if ($('#shade').val() == ''){
			swall("Data Not Complete","","error","2000");
		} 
		else if ($('#usercode_lot').val() == ''){
			swall("User Not Found","","error","2000");
			$('#usercode_lot').focus();
		} 
		else {

			swal({
				  title: "Lot Making "+$('#seqlot').val(),
				  text: "are you sure create Lot Making "+$('#seqlot').val(),
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				  	$('#jenis').val("input");
				  	$('#typesequence').val("input");
					var data = $('.form-user').serialize();
					swal("Saved!", {
				      icon: "success",
				    });
						$.ajax({
							type: 'POST',
							url:  "apps/Transaction/LotMaking/simpan.php",
							data: data,
							success: function(lot) {
								if (lot == 1) {
									swal({
										  icon: 'info',
										  title: 'Lot No is already in use',
										  text: 'please create again!',
										  footer: '<a href>Why do I have this issue?</a>'
									}).then((willoke) => {
										//window.location.reload();
									});
								} else {

									explot = lot.split('_');
									swal({
										  icon: 'success',
										  title: 'Lot No : '+explot[0],
										  text: 'Saved',
										  footer: '<a href>Why do I have this issue?</a>'
									}).then((willoke) => {
										javascript: window.open("lib/pdf-qrcode.php?c="+explot[1], "_blank", "width=700,height=450");
										window.location.reload();
									});
								}
							}
						});
				    
					} 
				});
		}
	}

	function correct(lot,user,role,typelot,master_type_process){
				swal({
				  title: "Are you sure?",
				  text: "Data is Correct",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				  	$('#master_type_process').val(master_type_process);
					var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/LotMaking/simpan.php",
						data: data,
						success: function() {
							swal("Saved!", {
						      icon: "success",
						    });
						  	$('#formku').load("apps/Transaction/LotMaking/content_isi.php?id=1");
						}
					});
				  	
				  }
				});
	}

	function newrework(a){
		$('#tampilcmt').html('<img src="./assets/images/spinner.gif">');
		if(a == 1) {
			$('#gruplotmaking').show();
			$('#nocmt').load('apps/Transaction/LotMaking/choosecmt.php?type='+a);
			$('#tampilcmt').load('apps/Transaction/LotMaking/tampilcmt.php?type='+a);
		} else if (a == 2) {
			$('#gruplotmaking').show();
			$('#nocmt').load('apps/Transaction/LotMaking/choosecmt.php?type='+a);
			$('#tampilcmt').load('apps/Transaction/LotMaking/tampilcmt.php?type='+a);
		} else {
			$('#gruplotmaking').hide();
		}
	}

	function process_in(a,lot,usemachine,master_process_id,time,qty){
		
		if($('#sender').val() == ''){
			swall('Sender Not Found','Please Input Sender','error','2000');
			$('#sender').focus();
		} else if ($('#receiver').val() == '') {
			swall('Receiver Not Found','Please Input Receiver','error','2000');
			$('#receiver').focus();
		} else {
			$('#process-status').val(a);
					var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/LotMaking/simpan.php",
						data: data,
						success: function() {
							
							$('#formku').load("apps/Transaction/LotMaking/content_isi.php?id=2&usr="+encodeURI($("#usercode").val()));
							$('#button_rec_lot').load("apps/Transaction/LotMaking/button_rec_lot.php?id=2");
							$("#lot_no").focus();
						}
					});
		}
				
	}

	function process_end(a,lot,usemachine,master_process_id,time,qty){
		
		if($('#sender').val() == ''){
			swall('Sender Not Found','Please Input Sender','error','2000');
			$('#sender').focus();
		} else if ($('#receiver').val() == '') {
			swall('Receiver Not Found','Please Input Receiver','error','2000');
			$('#receiver').focus();
		} else {
			$('#process-status').val(a);
					var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/LotMaking/simpan.php",
						data: data,
						success: function() {
							
							$('#formku').load("apps/Transaction/LotMaking/content_isi.php?id=3&usr="+encodeURI($("#usercode").val()));
							$('#button_rec_lot').load("apps/Transaction/LotMaking/button_rec_lot.php?id=3");
							$("#lot_no").focus();
						}
					});
		}
				
	}

	function receiver(rval){
	 if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	    if(rval == $('#lot_no').val()){
	    	swall("check your receiver!!","","error","1000");
	    	$('#receiver').val('');
	    	$('#receiver').focus();
	    } else {
	    	 $("#inbutton").focus();
	    }
	 }
	}

	function Reset () {
		window.location.reload();
	}

	function swall (title,text, icon, timer){
		swal({
				title: title,
				text: text,
				icon: icon,
				time: timer,
				});
	}
</script>