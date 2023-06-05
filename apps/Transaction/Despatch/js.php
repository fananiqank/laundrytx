<script>
	function ucode(){
	    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	       $("#lot_no").focus();
	    }
	}
	function lcode () {
    	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	        var lot = $("#lot_no").val();
	        var user = $("#userid").val();
	        var usercode = $("#usercode").val();
	        var master_type_process_id = $("#mastertypeid").val();
	        $('#tampilcmt').html('<img src="./assets/images/spinner.gif">');
	        cekuser(lot,user,usercode,master_type_process_id);
 	   } 
	}

	function cekuser(lot,user,master_type_process_id){
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
			$('#tampilcmt').load("apps/Transaction/QC_scan/tampilcmt2.php?lot="+lot+"&user="+user+"&typelot="+lotres+"&taskprocess=2");
		}
		//$('#tampilcmt').load("apps/Transaction/Despatch/tampilcmt2.php?lot="+lot+"&user="+user+"&typelot="+lotres);
	}

	

	function enterlot(a){
		$('#tampilcontent').load("apps/Transaction/Despatch/content_create.php");
	}

	function reclot(a){
		$('#formku').load("apps/Transaction/Despatch/content_isi.php?id="+a);
		$('#button_rec_lot').load("apps/Transaction/Despatch/button_rec_lot.php?id="+a);
		
	}

	function ccmt(id,type){
		//CMT
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
 		if (app == 0) {
 			swall('Sequence Process must be Approve','Please Check Sequence Process,\n if you are ready create lot, \nPlease Approve all Sequence Process','warning','3000');	
 			$('#tampilcmt').load("apps/Transaction/Despatch/tampilcmt.php");
 			$('#tampilselectlot').load("apps/Transaction/Despatch/selectlot.php");
			$('#showcmt').val('');
			$('#showcolors').val('');
			$('#rolecmt').val('');
 			$('#idcmt').val('');
 		}
 		else if (rec == '') {
 			swall('Item Not Received','Please Check Received Item','info','2000');	
 			$('#tampilcmt').load("apps/Transaction/Despatch/tampilcmt.php");
 			$('#tampilselectlot').load("apps/Transaction/Despatch/selectlot.php");
			$('#showcmt').val('');
			$('#showcolors').val('');
			$('#rolecmt').val('');
 			$('#idcmt').val('');
 		}
 		else {
				showexdate = exdate.split('-');
				exdateshow = showexdate[2]+"-"+showexdate[1]+"-"+showexdate[0];

							$('#tampilcmt').load("apps/Transaction/Despatch/tampilcmt.php?type="+type+"&col="+colors+"&exdate="+exdate+"&cmt="+cmts);
							$('#tampilselectlot').load("apps/Transaction/Despatch/selectlot.php?id="+id);
							$('#showcmt').val(cmtq);
							$('#showcolors').val(colorq);
							$('#showexftydate').val(exdateshow);
							$('#rolecmt').val(roleq);
 							$('#idcmt').val(id);
 							$('#totalrec').val(qtyrecq);
					
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
				swall('Qty Lot Over','Qty Lot More than Balance Qty Lot','info','2000');				
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
				url:  "apps/Transaction/Despatch/tampillotnumber.php?id="+id+"&cmt="+cmt+"&idc="+idcmt,
				success: function() {
					$('#tampillot').load("apps/Transaction/Despatch/tampillotnumber.php?id="+id+"&cmt="+cmt+"&idc="+idcmt);
					$('#tampilshade').load("apps/Transaction/Despatch/selectshade.php?lastrec="+lastrec);
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
		else {

			swal({
				  title: "Are You Sure?",
				  text: "Lot Making",
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
							url:  "apps/Transaction/Despatch/simpan.php",
							data: data,
							success: function(lot) {
									explot = lot.split('_');
									swal({
										  icon: 'success',
										  title: 'Lot No : '+explot[0],
										  text: 'Saved',
										  footer: '<a href>Why do I have this issue?</a>'
									}).then((willoke) => {
										//javascript: window.open("lib/pdf-qrcode.php?c="+explot[1], "_blank", "width=700,height=450");
										window.location.reload();
									});
								
							}
						});
				    
					} 
				});
		}
	}

	function correct(lot,user,role,typelot,master_type_process){

			swal({
				  title: "Are you sure?",
				  text: 'Good:'+$('#qtyprocess').val(),
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
						url:  "apps/Transaction/Despatch/simpan.php",
						data: data,
						success: function() {
							swal("Saved!", {
						      icon: "success",
						    });
						  	//$('#formku').load("apps/Transaction/Despatch/content_isi.php?id=1");
							window.location.reload();
						}
					});
				  	
				  }
				});
		
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
						url:  "apps/Transaction/QC_scan/simpan.php",
						data: data,
						success: function() {
							
							$('#tampilcontent').load("apps/Transaction/Despatch/content_isi.php?usercode="+encodeURI($("#usercode").val())+"&stat=process");
							$("#lot_no").focus();
						}
					});
		}
				
	}

	function process_end(a){
		if($('#qtygood').val() == 'undefined' ){
			swall('Restart Modal','Close and Click Again END','error','2000');
		} else {
			
			$('#process-status').val(a);
					var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/Despatch/simpan.php",
						data: data,
						success: function() {

							$('#tampilcontent').load("apps/Transaction/Despatch/content_isi.php?usercode="+encodeURI($("#usercode").val())+"&stat=process");
							$("#lot_no").focus();
						}
					});
		}		
	}

	function hitung (val) {
		const total = parseInt($('#qtygood').val())+parseInt($('#qtyrework').val())+parseInt($('#qtyreject').val());
		if (total > $('#qty_process').val()) {
			swall("Over Qty!!","Qty only "+$('#qty_process').val()+" Pcs","error",3000);
			$('#qtyreject').val(0);
			$('#qtyrework').val(0);
			$('#qtytotal').val($('#qtygood').val());
		} else {
			$('#qtytotal').val(total);
		}
	}

	function newrework(a){
		if(a == 1) {
			$('#gruplotmaking').show();
			$('#nocmt').load('apps/Transaction/Despatch/choosecmt.php?type='+a);
			$('#tampilcmt').load('apps/Transaction/Despatch/tampilcmt.php?type='+a);
		} else if (a == 2) {
			$('#gruplotmaking').show();
			$('#nocmt').load('apps/Transaction/Despatch/choosecmt.php?type='+a);
			$('#tampilcmt').load('apps/Transaction/Despatch/tampilcmt.php?type='+a);
		} else {
			$('#gruplotmaking').hide();
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