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

	function cekuser(lot,user,usercode,master_type_process_id){
		var explot = lot.split('-');
		var lotres = explot[0].substr(-4,1);
		// console.log(usercode)
		//alert(lotres);
		if (lot == ''){
			swall('Lot Number Empty','Lot Number Must be Filled','error',2000);
		} else if ( usercode == ''){
			swall('User Empty','User Must be Filled','error',2000);
			$("#lot_no").val('');
		} else {
			//alert(lotres);	
			$('#tampilcmt').load("apps/Process/Process/tampilcmt.php?lot="+lot+"&usercode="+encodeURI(usercode)+"&typelot="+lotres);
			
			
		}
	}
	
	function enterrec(a){
		$('#tampilcontent').load("apps/Process/Process/content_rec.php");
	}

	function enterlot(a){
		$('#tampilcontent').load("apps/Process/Process/content_lot.php");
	}

	// function ceklot(lot,user){
	// 	alert(user);
	// 	if (lot == ''){
	// 		alert(lot);
	// 	} else {
	// 		idcmt = $('#idcmt').val();
	// 		showcmt = $('#showcmt').val();
	// 		trimcmt = showcmt.trim();
	// 		expcmt = trimcmt.split('/');
	//  		cmt = expcmt.join('-');
	// 		$.ajax({
	// 			type: 'GET',
	// 			url:  "apps/Process/Dry/tampillotnumber.php?id="+id+"&cmt="+cmt+"&idc="+idcmt,
	// 			success: function() {
	// 				$('#tampillot').load("apps/Process/LotMaking/tampillotnumber.php?id="+id+"&cmt="+cmt+"&idc="+idcmt);
	// 			}
	// 		});
	// 	}
	// }
	// var input = document.getElementById("user");
	// input.addEventListener("keyup", function(event) {
	//   if (event.keyCode === 13) {
	//   	var lot = $('#lot_no').val();
	//   	if (lot == ''){
	//   		alert("Error");
	//   		window.location.reload();
	//   	} else {
	//    		event.preventDefault();
	//    		cekuser($('lot_no').val(),$('user').val());
	//   	}
	//   }
	// });

	// var input = document.getElementById("lot_no");
	// input.addEventListener("keyup", function(event) {
	//   if (event.keyCode === 13) {
	//   	var user = $('#user').val();
	//   	if (user == ''){
	//   		alert("Error");
	//   		window.location.reload();
	//   	} else {
	// 	   event.preventDefault();
	// 	   cekuser($('lot_no').val(),$('user').val());
	//   	}
	//   }
	// });
	

	$(function() {  
		    	//$('#nocmt').focus();
		        $("#user").autocomplete({  
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Process/Process/sourcedata.php",
			                {term:request.term }, 
			                response
			            );
			        },
		           	minLength:0, 
		        	select: function (event, ui) {
		            if (ui.item != undefined) {
		                $(this).val(ui.item.value);	
		                //$('#selected_id').val(ui.item.id);
				        $( "#userid" ).val( ui.item.id );
				       	//depar(ui.item.id_departemen);
		            	} 
		            	return false;
	        		}
		        });
		        
	});

	function split(lot,parlot,parent1){
		window.location.href="content.php?p=MjAxNA==&pro=1&parent1="+parent1;
		//$('#tampilcontent').load("apps/Process/Split/content.php");
	}

	function combine(lot,parlot,parent1){
		window.location.href="content.php?p=MjAxNQ==&pro=1&parent1="+parent1;
	}

	function cancel(){
		window.location.reload();
	}

	function correct(lot,user,role,typelot,master_type_process,master_process_id,no_id,roledtlid,parent,usemachine,role_wo_id,typeqc,qtylast,type_sequence){
		usercode = $('#usercode').val();
		if (usemachine == 1) {
			//cek machineplan on tampilmachine. 
			if ($('#machineplan').val() == ''){
				swall("Choose Machine","You Must Choose Machine","error",2000);
			}
			//cek waktu machine. harus di isi.
			else if ($('#machinetime').val() == '' || $('#machinetime').val() == 0){
				swall("Fill Time Machine","You Must Fill Time Machine, Can not 0","error",2000);
			} else {
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
						// var data = $('.form-user').serialize();
						// $.ajax({
						// 	type: 'POST',
						// 	url:  "apps/Process/Process/simpan.php",
						// 	data: data,
						// 	success: function() {
								dealcorrect(lot,user,role,typelot,master_type_process,master_process_id,no_id,roledtlid,parent,usemachine,role_wo_id,typeqc,qtylast,type_sequence,encodeURI(usercode));
						// 	}
						// });
					  	
					  }
					});
			}
		} else {
				swal({
					  title: "Are you sure?",
					  text: "Data is Correct",
					  icon: "warning",
					  buttons: true,
					  dangerMode: true,
				}).then((willDelete) => {
					  if (willDelete) {
					  	$('#master_type_process').val(master_type_process);
						// var data = $('.form-user').serialize();
						// $.ajax({
						// 	type: 'POST',
						// 	url:  "apps/Process/Process/simpan.php",
						// 	data: data,
						// 	success: function() {
								dealcorrect(lot,user,role,typelot,master_type_process,master_process_id,no_id,roledtlid,parent,usemachine,role_wo_id,typeqc,qtylast,type_sequence,encodeURI(usercode));
						// 	}
						// });
					  }
				});

		}
	}

	function dealcorrect(lot,user,role,typelot,master_type_process,master_process_id,no_id,roledtlid,parent,usemachine,role_wo_id,typeqc,qtylast,type_sequence,usercode){
		if (master_type_process == 2) {
			if (type_sequence == 2){
				swall('Lot Making Rework Menu','Please go to Lot making rework menu','error','2000');
				//window.location="content.php?option=Transaction&task=lot_making_re&act=ugr_transaction";
			} else if (type_sequence == 1) {
				swall('Lot Making Menu','Please go to Lot making menu','error','2000');
				//window.location="content.php?option=Transaction&task=lot_making&act=ugr_transaction";
			}
		} else if (master_type_process == 3) {
			if(typeqc == 'S'){
				swall('QC Receive Menu','Please go to QC Receive menu','error','2000');
				//$('#tampilcontent').load("apps/Process/Scan/content_scan_pro.php?d="+master_type_process+"&lot="+lot+"&user="+user+"&roledtlid="+roledtlid+"&typelot="+typelot+"&id="+no_id+"&typeqc="+typeqc+"&rolewoid="+role_wo_id+"&qtylast="+qtylast+"&usercode="+usercode);
			} else {
				swall('QC Manual Menu','Please go to QC manual menu','error','2000');
				//$('#tampilcontent').load("apps/Process/QCmanual/content.php?d="+master_type_process+"&lot="+lot+"&user="+user+"&roledtlid="+roledtlid+"&typelot="+typelot+"&id="+no_id+"&typeqc="+typeqc+"&rolewoid="+role_wo_id+"&qtylast="+qtylast+"&usercode="+usercode);
			}
		} else if (master_type_process == 4) {
			if (master_process_id == '42'){
				$('#tampilcontent').load("apps/Process/Scan/content_scan_pro.php?d="+master_process_id+"&lot="+lot+"&user="+user+"&roledtlid="+roledtlid+"&typelot="+typelot+"&id="+no_id+"&typeqc="+typeqc+"&rolewoid="+role_wo_id+"&qtylast="+qtylast+"&usercode="+usercode);
				// window.location="content.php?p=MjAxMw==&d="+master_process_id+"_"+no_id+"_"+role;
			} else {
				$('#tampilcontent').load("apps/Process/Dry/content_process.php?lot="+lot+"&user="+user+"&role="+role+"&typelot="+typelot+"&q="+$('#qty_process').val()+"&parent="+parent+"&mpd="+master_process_id+"&rwid="+role_wo_id+"&usercode="+encodeURI(usercode));
				loopnotif(lot,user,role,typelot,$('#qty_process').val(),master_type_process,parent,master_process_id,role_wo_id,usercode);
			}
			
		} else if (master_type_process == 5) {
			$('#tampilcontent').load("apps/Process/Wet/content_process.php?lot="+lot+"&user="+user+"&role="+role+"&typelot="+typelot+"&q="+$('#qty_process').val()+"&parent="+parent+"&mpd="+master_process_id+"&rwid="+role_wo_id+"&usercode="+encodeURI(usercode));
			loopnotif(lot,user,role,typelot,$('#qty_process').val(),master_type_process,parent,master_process_id,role_wo_id,encodeURI(usercode));
		}  
		else if (master_type_process == 6) {
			if(typeqc == 'S'){
				swall('Despatch Scan menu','Please go to Despatch Scan menu','error','2000');
				//window.location="content.php?option=Transaction&task=despatch_scan&act=ugr_transaction";
			} else {
				swall('Despatch manual Menu','Please go to Despatch manual menu','error','2000');
				//window.location="content.php?option=Transaction&task=rework&act=ugr_transaction";
			}
		}

	}

	function loopnotif(lot,user,role,typelot,qty,master_type_process,parent,master_process_id,role_wo_id,usercode){
		if(master_type_process == 4){
			 myLoop = setInterval(function(){ $('#tampilcontent').load("apps/Process/Dry/content_process.php?lot="+lot+"&user="+user+"&role="+role+"&typelot="+typelot+"&q="+qty+"&mtype="+master_type_process+"&parent="+parent+"&mpd="+master_process_id+"&rwid="+role_wo_id+"&usercode="+encodeURI(usercode));  }, 5000);

		} else {
			 myLoop = setInterval(function(){ $('#tampilcontent').load("apps/Process/Wet/content_process.php?lot="+lot+"&user="+user+"&role="+role+"&typelot="+typelot+"&q="+qty+"&mtype="+master_type_process+"&parent="+parent+"&mpd="+master_process_id+"&rwid="+role_wo_id+"&usercode="+encodeURI(usercode));  }, 5000);
		}
	}

	function back(a){
		if (a == 'process'){
			// clearInterval(myLoop);
			// $('#tampilcontent').load("apps/Process/Process/content_isi.php?stat="+a);
			window.location.reload(); 
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
						url:  "apps/Process/Process/simpan.php",
						data: data,
						success: function(lot) {
							if(lot == 'none'){
								swall('Role Process Not Found','Please Check your Sequence','error','2000');
							} 
							$('#tampilcontent').load("apps/Process/Process/content_isi.php?usercode="+encodeURI($("#usercode").val())+"&stat=process");
							$("#lot_no").focus();
						}
					});
		}
				
	}
	function process_start(a,machinetime,usemachine,master_process_id,qty){
		// if(machinetime == 0){
		// 	swall('Time Not Set','Please Set Time on Sequence','error','2000');
		// } else {
		if($('#foreman').val() == ''){
			swall('foreman Not Found','Please Input foreman','error','2000');
			$('#foreman').focus();
		} else if($('#operator').val() == ''){
			swall('operator Not Found','Please Input operator','error','2000');
			$('#operator').focus();
		} else if ($('#machinecode').val() == '') {
			swall('Machine Not Found','Please Input Machine','error','2000');
			$('#machinecode').focus();
		} else if ($('#machinecode').val() != '' && $('#machineid').val() == '') {
			swall('Machine Code Not Found','Check Machine Code','error','2000');
			$('#machinecode').focus();
		} else {
				$('#process-status').val(a);
					var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Process/Process/simpan.php",
						data: data,
						success: function() {
							
							$('#tampilcontent').load("apps/Process/Process/content_isi.php?usercode="+encodeURI($("#usercode").val())+"&stat=process");
							$("#lot_no").focus();
						}
					});
		}
	}

	function process_end(a,lot,machine,master_type_process_id,master_process_id,qty){
		var total = $('#qtytotal').val();
		var goodori = $('#qtygoodori').val();
		if($('#qtygood').val() == 'undefined' ){
			swall('Restart Modal','Close and Click Again END','error','2000');
		} else if($('#qtygood').val() == '' ){
			swall('Restart Modal','Close and Click Again END','error','2000');
		} else if(parseInt(goodori) != parseInt(total)){
			swall('Qty Not Match','Check Out Qty and In Qty','error','2000');
			$('#qtygood').val(goodori);
			$('#qtyreject').val(0);
			$('#qtytotal').val(goodori);
		} else {
			if(machine = 'undefined') {
				var machmach = 0;
			} else {
				var machmach = machine;
			} 

			$('#process-status').val(a);
					var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Process/Process/simpan.php?machine="+machmach+"&lot="+lot,
						data: data,
						success: function(lot) {
							if (lot != ''){
								explotout=lot.split("_");
								swal({
									  icon: 'success',
									  title: 'Lot No : '+explotout[0],
									  text: 'Saved',
									  footer: '<a href>Why do I have this issue?</a>'
									}).then((willoke) => {
										$('#tampilcontent').load("apps/Process/Process/content_isi.php?usercode="+encodeURI($("#usercode").val())+"&stat=process");
										$("#lot_no").focus();
										javascript: window.open("lib/pdf-qrcode.php?c="+explotout[1], "_blank", "width=700,height=450");

									});	
							} else {
								$('#tampilcontent').load("apps/Process/Process/content_isi.php?usercode="+encodeURI($("#usercode").val())+"&stat=process");
								$("#lot_no").focus();
							}
						}
					});
		}		
	}
	function process_out(a,lot,user,master_type_process_id){
		var total = $('#qtytotal').val();
		var goodori = $('#qtygoodori').val();
		if(parseInt(goodori) > parseInt(total)){
			swall('Less Qty','Check Out Qty and In Qty','error','2000');
			$('#qtygood').val(goodori);
			$('#qtyreject').val(0);
			$('#qtytotal').val(goodori);
		} else {
			swal({
				  title: "Are you sure?",
				  text: "Out This Lot From this Section",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
			}).then((willDelete) => {
				  	if (willDelete) {
				  		$('#process-status').val(a);
						var data = $('.form-user').serialize();
						$.ajax({
							type: 'POST',
							url:  "apps/Process/Process/simpan.php?lot="+lot+"&mtpid="+master_type_process_id,
							data: data,
							success: function(lot) {
									// if (lot != ''){
									// 	explotout=lot.split("_");
									// 	swal({
									// 		  icon: 'success',
									// 		  title: 'Lot No : '+explotout[0],
									// 		  text: 'Saved',
									// 		  footer: '<a href>Why do I have this issue?</a>'
									// 	}).then((willoke) => {
									// 		javascript: window.open("lib/pdf-qrcode.php?c="+explotout[1], "_blank", "width=700,height=450");
									// 		window.location.reload();
									// 	});
									// } else {
										window.location.reload();
									//}
							}	
						});
				  	}
				});
		}
	}
	// var status = document.getElementById("process-status").val();
	// if (status == 1){
	// 	alert("masuk");
	// 	document.getElementById("process-in").disabled = true; 
	// }
	
	function machine(id,lot,time,role_wo_id,master_id,usemulti){

		var sumtime = parseInt($('#jumtime').val())+parseInt(time);
		if (sumtime > parseInt($('#time').val())){
			swall('Over Time Process','Check Your Time on Planning Process','error','2000');
		}
		else {
			$('#machineplan').val(id);
			$('#machine-input').val(1);
			var data = $('.form-user').serialize();
			$.ajax({
				type: 'POST',
				url:  "apps/Process/Process/simpan.php",
				data: data,
				success: function() {
					$('#tampilmachine').load("apps/Process/Process/tampilmachine.php?id="+id+"&lot="+lot+"&rolewoid="+role_wo_id+"&mastertype="+master_id+"&usemulti="+usemulti);
					//$('#nextprocess').show();
					//$('#machine-input').val(0);
				}	
			});
		}
		
	}

	function machineinprocess(id,lot){

		$('#machine-input').val(4);
			var data = $('.form-user').serialize();
			$.ajax({
				type: 'POST',
				url:  "apps/Process/Process/simpan.php",
				data: data,
				success: function(ui) {
					//alert(ui);
					if (ui == 0) {
						swal({
						  icon: 'error',
						  title: 'Not Save',
						  text: 'Time Not Set'
						});
					} else {
						$('#button-process').load("apps/Process/Dry/button-process.php?machine="+id+"&lot="+lot);
					}
				}	
			});
		
	}

	function savetime(val,master,lot,no){
		$('#machinetime').val(val);
		hitungtime(val,no);
	}

	function hitungtime(val,no){
		var timeall = $('#time').val();
		var jumlahid = $('#jumid').val();
		var sum = 0;
		for (j=1;j<=jumlahid;j++){
			if ($('#timemac_'+j).val() == ''){
				var dtl = "0";
			} else { 	
				var dtl = $('#timemac_'+j).val();
			}

			sum += parseInt(dtl);
			if (parseInt(sum) > parseInt(timeall)){
				swall('Overtime','Time machine more than Time Planning','error','2000');
				$('#timemac_'+j).val('0');
				inputtime(no);
			} else {
				inputtime(no);

				
			}
		}
	}

	function inputtime(no){
			$('#machine-input').val(3);
				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Process/Process/simpan.php?no="+no,
					data: data,
					success: function() {
						$('#tampilmachine').load("apps/Process/Dry/tampilmachine.php?id="+id+"&lot="+lot);
						$('#machine-input').val(0);
					}	
				});
	}

	function hapusmachine(procid,id,lot,sub,role_wo_id){
		swal({
			title: "Are You Sure?",
			text: "Delete Machine" ,
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				
				$('#machine-input').val(2);
				$('#machine_id').val(procid);

				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Process/Process/simpan.php",
					data: data,
					success: function() {
						if (sub == 1){
							$('#button-process').load("apps/Process/Dry/button-process.php?machine="+id+"&lot="+lot);
						} else {
							$('#tampilmachine').load("apps/Process/Process/tampilmachine.php?id="+id+"&lot="+lot+"&mastertype="+sub+"&rolewoid="+role_wo_id);
						}
						$('#machine-input').val(0);
						$('#machineplan').val('');
					}	
				});
			}
		});	
	}

//fungsi ini untuk mengecek machine yang digunakan pada process
	function cekmachine(lot,jmlmachine,counttime,time,multimachine){
		
			$('#machine-input').val(4);
			var jmlcheck = $(":checkbox:checked").length;
			var jmlall = parseInt(jmlmachine)+parseInt(jmlcheck);
			
	//jika sebagai single machine
			if (multimachine == 0) {
				if(jmlall > 1){
					swall('Max Machine 1','','error','2000');
					$(":checkbox:checked").prop("checked", false);
					$('#machine-input').val(0);
					$('#timemachine').val('');
				}
			} 
	//jika menggunakan multi machine
			else {
				if (jmlall > 1){
					swall('Max Machine 1','','error','2000');
					$(":checkbox:checked").prop("checked", false);
					$('#machine-input').val(0);
					$('#timemachine').val('');
				}
			}
	}

	// function hitungqtymach(good,reject){
	// 	if (reject == ''){
	// 		var reject = 0;
	// 	} else {
	// 		var reject = reject;
	// 	} 

	// 	var goodori = $('#qtygoodori').val();
	// 	var total = parseInt(good)+parseInt(reject);
	// 	if (total > parseInt($('#qtylot').val())){
	// 		swall('Over Qty','Check Qty Lot','error','2000');
	// 		$('#qtygood').val(goodori);
	// 		$('#qtyreject').val(0);
	// 		$('#qtytotal').val(goodori);
	// 	} else {
	// 		if ($('#qtyreject').val() == ''){
	// 			$('#qtyreject').val(0);
	// 		}
	// 	$('#qtytotal').val(total);
	// 	}
	// }

	function hitungqtymach(good,std){
		if (std == ''){
			var std = 0;
		} else {
			var std = std;
		} 
		var goodori = $('#qtygoodori').val();
		var isigood = parseInt(goodori) - parseInt(std);
		var isistd = parseInt(goodori) - parseInt(good);
		var total = parseInt($('#qtygood').val())+parseInt($('#qtystd').val());
		if (total > parseInt($('#qtylot').val())){
			swall('Qty Lebih','Check Qty Lot','error','2000');
			$('#qtygood').val(goodori);
			$('#qtyreject').val(0);
			$('#qtystd').val(0);
			$('#qtytotal').val(goodori);
		} else {
			$('#qtystd').val(isistd);
			//totalall = parseInt(good)+parseInt(std);
			//$('#qtytotal').val(totalall);
		}
	}


//scan garment 

function onEnteruser(event,val){
 	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	        $('#typescan').focus(); 
	} 
}

function onEnterPanel(event,val){
 	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	        $('#reject_type').focus(); 
	} 
}

function onEnterReject(event,val){
 	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	        $('#scanner').focus(); 
	} 
}

function onEnter(){
	    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	        $('#inputtype').click(); 
	    };
}

function scaninput(type,lot){
	var scanner = $('#scanner').val();
	var splitscan = scanner.split('_');
	var wodb1 = $('#wo_no_scan').val();
	var wodb = wodb1.trim(); 						//wo dari db scan status 0
	var colordb = $('#garment_colors_scan').val();  //color dari db scan status 0
	var woscan = splitscan[0];						//wo dari yang sedang di scan
	var colorscan = splitscan[1]; 					//color dari yang sedang di scan
	var cutqty = parseInt($('#cutqtywo').val());    //Cutting Qty dari wo color yang sedang discan
	var totalqtyrec = parseInt($('#totalqtyrec').val()); //total qty dari yang sudah discan dan yang sudah di receive
	var typescanvalue = $('#typescan_value').val();
	var totalqtyscan = parseInt($('#totalqtyscan').val());
	var qtylast = $('#qtylast').val();

	if (typescanvalue == ''){
			swall("Choose Type Scan!!","Please Choose one of Type Scan","error",1000);
			$('#scanner').val('');
			$('#scan_type').val('');
			$('#scanner').focus();
	} 
	//jika jumlah yang di scan melebihi qty pada lot number tersebut
	else if (totalqtyscan >= qtylast){
			swall("Over Qty Lot!!","","error",1000);
			$('#scanner').val('');
			$('#scan_type').val('');
			$('#scanner').focus();
	}  
	//jika type yang digunakan adalah reject harus pilih defect
	else if (typescanvalue != '1' && ($('#panel_type').val() == '' || $('#reject_type').val() == '')) {
			swall("Location or Reject Empty!!","Please input location & reject","error",1000);
			$('#scanner').val('');
			$('#scan_type').val('');
			$('#typescan_value').val(typescanvalue);
			$('#typescan').val(typescanvalue);
			$('#panel_type').focus();
	}
	else {
		if(wodb != ''){
			if(wodb == woscan && colordb == colorscan){
				if (cutqty > totalqtyrec){
					$('#scan_type').val(type);
					var data = $('.form-user').serialize();
					$.ajax({
							type: 'POST',
							url:  "apps/Process/Scan/simpan_pro.php",
							data: data,
							success: function(ui) {
								splitui = ui.split('|');
								valreturn = splitui[0];
								valscan = splitui[1];
								valid = splitui[2];
								if (valreturn == 0){
									swall('Data Not Found','Check again your QrCode :\n '+valscan,'warning',1000);
									$('#scanner').focus();
								} else if (valreturn == 2){
									swall('Data Not Scan on Output '+valid,'Check again your QrCode :\n '+valscan,'warning',3000);
									$('#scanner').focus();
								} 
								else if (valreturn == 3){
									swall('The Data is already in the QC','Check again your QrCode :\n '+valscan,'warning',3000);
									$('#scanner').focus();
								} 
								else if (valreturn == 4){
									swall('Data Exist On Cart','Check again your QrCode :\n '+valscan,'warning',3000);
									$('#scanner').focus();
								} 
								else {
									if (type == 1){
										$('#tampilscan').load("apps/Process/Scan/sourcedatascan.php");
									} else {
										$('#tampilscan').load("apps/Process/Scan/sourcedatascan_pro.php?type="+type);
									}
								}
								$('#cart').load("apps/Process/Scan/cart_pro.php?type="+type+"&lot="+lot);
								$('#scanner').val('');
								$('#scan_type').val('');
								$('#scanner').focus();
								$('#typescan_value').val(typescanvalue);
							    $('#typescan').val(typescanvalue);
							}
					});
				} else {
					swall("Maximum Cut Qty","Receive more than Cut Qty\n Please Contact Supervisor","error",3000);
					$('#scanner').val('');
					$('#scanner').focus();
				}
			} else {
					swall("WO No Colors Not Same","Please Cek WO No & Colors On Garment","error",2000);
				$('#scanner').val('');
				$('#scanner').focus();
			}
		} else {
		
			$('#scan_type').val(type);
			var data = $('.form-user').serialize();
			$.ajax({
					type: 'POST',
					url:  "apps/Process/Scan/simpan_pro.php",
					data: data,
					success: function(ui) {
						splitui = ui.split('|');
						valreturn = splitui[0];
						valscan = splitui[1];
						valid = splitui[2];
						if (valreturn == 0){
								swall('Data Not Found','Check again your QrCode :\n '+valscan,'warning',1000);
								$('#scanner').focus();
						} else if (valreturn == 2){
								swall('Data Not Scan on Output '+valid,'Check again your QrCode :\n '+valscan,'warning',3000);
								$('#scanner').focus();
						} 
						else if (valreturn == 3){
								swall('The Data is already in the QC','Check again your QrCode :\n '+valscan,'warning',3000,);
								$('#scanner').focus();
						} 
						else if (valreturn == 4){
								swall('Data Exist On Cart','Check again your QrCode :\n '+valscan,'warning',3000);
								$('#scanner').focus();
						} 
						else {
							
								if (type == 1){
									$('#tampilscan').load("apps/Process/Scan/sourcedatascan.php");
								} else {
									$('#tampilscan').load("apps/Process/Scan/sourcedatascan_pro.php?type="+type);
								}
						}
						$('#cart').load("apps/Process/Scan/cart_pro.php?type="+type+"&lot="+lot);
						$('#scanner').val('');
						$('#scan_type').val('');
						$('#scanner').focus();
						$('#typescan_value').val(typescanvalue);
						$('#typescan').val(typescanvalue);
					}
			});
		}
	}
}

function scaninputmanual(type,lot){
	var totalqty = parseInt($('#qtygood').val())+parseInt($('#qtyreject').val())+parseInt($('#qtyrework').val());
	if(totalqty > $('#qtytotal').val() ){
		swall("Over Qty!!","Qty only "+$('#qtytotal').val()+" Pcs","error",3000);
		$('#qtyreject').val(0);
		$('#qtyrework').val(0);
	} else {
		swal({
			title: "Are You Sure?",
			text: "Save QC Final",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((savedata) => {
			if (savedata) {

				//jika qty NULL maka di ubah menjadi 0 
				if($('#qtygood').val() == ''){
					$('#qtygood').val(0);
				} 

				if($('#qtyreject').val() == ''){
					$('#qtyreject').val(0);
				} 

				if($('#qtyrework').val() == ''){
					$('#qtyrework').val(0);
				} 

				$('#confirm').val(type);
				var data = $('.form-user').serialize();
				$.ajax({
						type: 'POST',
						url:  "apps/Process/QCmanual/simpan.php",
						data: data,
						success: function() {
							$('#confirm').val('');
							swal("Saved!", {
						      icon: "success",
						    });
							$('#tampcontent').load("apps/Process/QCmanual/tampilcontent.php?lot="+lot+"&reload=1");
							$('#cart').load("apps/Process/QCmanual/cartqc.php?type="+type);
						}
				});
			}
		});	
	}
	
}
function savetoreceive(type){
	//if (type == '2') {
		swal({
			title: "Are You Sure?",
			text: "Save Scan",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((savedata) => {
			if (savedata) {
				$('#confirm').val(type);
				var data = $('.form-user').serialize();
				$.ajax({
						type: 'POST',
						url:  "apps/Process/Scan/simpan_pro.php",
						data: data,
						success: function(lot) {
							$('#confirm').val('');
							explot = lot.split('|');
							lotreject = explot[0];
							lotrework = explot[1];

							if (lotreject != '' && lotrework == ''){
								swal({
									  	icon: 'success',
									  	title: 'Lot Reject : '+lotreject,
									  	text: 'Saved',
									  	footer: '<a href>Why do I have this issue?</a>'
								}).then((willoke) => {
									javascript: window.open("lib/phpqrcode/index.php?no="+lotreject, "_blank", "width=700,height=450");
									javascript: window.location.href="content.php?p="+$('#getpmod').val();
								});
							} else if (lotrework != '' && lotreject == ''){
								swal({
									  	icon: 'success',
									  	title: 'Lot Rework : '+lotrework,
									  	text: 'Saved',
									  	footer: '<a href>Why do I have this issue?</a>'
								}).then((willoke) => {
									javascript: window.open("lib/phpqrcode/index.php?no="+lotrework, "_blank", "width=700,height=450");
								});
							} else if (lotreject != '' && lotrework != ''){
								swal({
									  	icon: 'success',
									  	title: 'Lot reject : '+lotreject+"\n Lot Rework: "+lotrework,
									  	text: 'Saved',
									  	footer: '<a href>Why do I have this issue?</a>'
								}).then((willoke) => {
									javascript: window.open("lib/phpqrcode/index.php?no="+lotreject, "_blank", "width=700,height=450");
									javascript: window.open("lib/phpqrcode/index.php?no="+lotrework, "_blank", "width=700,height=450");
								});
							}
							
							//javascript: window.location.href="content.php?p="+$('#getpmod').val();
						}
				});
			}
		});	
	//}
}

function savetoreceivemanual (){
	swal({
			title: "Are You Sure?",
			text: "Save QC Manual",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((savedata) => {
		if (savedata) {
					$('#conf').val(2);
					var data = $('.form-user').serialize();
					$.ajax({
							type: 'POST',
							url:  "apps/Process/QCmanual/simpan.php",
							data: data,
							success: function(lot) {
								$('#conf').val('');
								explot = lot.split('|');
								lotreject = explot[0];
								lotrework = explot[1];
								swal({
									  icon: 'success',
									  title: 'Lot No : '+lot,
									  text: 'Saved',
									  footer: '<a href>Why do I have this issue?</a>'
								}).then((willoke) => {
									if (lotreject != '' && lotrework == ''){
										javascript: window.open("lib/phpqrcode/index.php?no="+lotreject, "_blank", "width=700,height=450");
									} else if (lotrework != '' && lotreject == ''){
										javascript: window.open("lib/phpqrcode/index.php?no="+lotrework, "_blank", "width=700,height=450");
									} else if (lotreject != '' && lotrework != ''){
										javascript: window.open("lib/phpqrcode/index.php?no="+lotreject, "_blank", "width=700,height=450");
										javascript: window.open("lib/phpqrcode/index.php?no="+lotrework, "_blank", "width=700,height=450");
									}
									window.location.reload();
								});
							}
					});
			}
		});
}

function tooltip(val,id){
		
		var will = $('#willrec').val();
		var cut = $('#cutqty').val();
		var jum = parseInt(cut)-parseInt(cut);
		if (val.checked){
			if (parseInt(will) > parseInt(cut)){
				swall('Can not Submit!!','Receive Qty More than Cutting Qty','error',3000);
				$('#sumbit').hide();
				$('#lastrec').prop("checked", false);
				$('#idlast').val(2);
			} else if(parseInt(will) < parseInt(cut)) {
				swall('Fill Note','Receive Qty Less than Cutting Qty','info',3000);
				$('#sumbit').show();
				$('#idlast').val(1);
				$('$remark').focus();
			} else {
				$('#sumbit').show();
				$('#idlast').val(0);
			}
		} else {
			$('#idlast').val(0);
		}		
}

function hitungcut(val,id){
		$('#cutqty').val();
		$('#willrec').val();
		var jumbalance = parseInt($('#willrec').val())-parseInt($('#cutqty').val());
		$('#balance').val(jumbalance);
}

function ftypescan(val){
	$('#typescan_value').val(val);
	if (val == 2){
		$('#desinfect').show();
		$('#panel_type').focus();
	} else if (val == 3){
		$('#desinfect').show();
		$('#panel_type').focus();
	} else {
		$('#defect').val('');
		$('#desinfect').hide();
		$('#defect_value').val('');
		$('#scanner').focus();
	}
}
function defectscan(val){
	$('#defect_value').val(val);
}

function hapusqckeranjang(lot,type_qc,role_wo_id,d){
	swal({
			title: "Are You Sure?",
			text: "Delete from Cart",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((savedata) => {
			if (savedata) {
				var data = $('.form-user').serialize();
				$.ajax({
						type: 'POST',
						url:  "apps/Process/QCmanual/simpan.php?lot="+lot+"&type_qc="+type_qc,
						data: data,
						success: function() {
							$('#confirm').val('');
							swall("Deleted","","success",2000);
							$('#tampilmodcart').load("apps/Process/QCmanual/sourcedatamodqc.php?lot="+lot+"&type_qc="+type_qc);
							$('#sumbit').hide();
							$('#tampcontent').load("apps/Process/QCmanual/tampilcontent.php?lot="+lot+"&reload=1");
							$('#cart').load("apps/Process/QCmanual/cartqc.php?type="+type_qc);
						}
				});
			}
		});	
}

function ceksend(sval){
	 if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	    if(sval == $('#lot_no').val()){
	    	swall("check your Sender!!","","error","1000");
	    	$('#sender').val('');
	    	$('#sender').focus();
	    } else {
	    	 $("#receiver").focus();
	    }
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

function cekopr(oval){
	 if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	    if(oval == $('#lot_no').val()){
	    	swall("check your receiver!!","","error","1000");
	    	$('#operator').val('');
	    	$('#operator').focus();
	    } 
	 }
}

function process_change(val){
	if(val == 'reset') {
		$('#cp').val('');
		$('#cpr').html("");
	} else if(val == '0') {
		$('#cp').val('');
		$('#cpr').html("");
	} else {
		expcp = val.split('|');
		$('#cp').val(expcp[0]);
		$('#cpr').html("<b>Changed to : "+expcp[1]+"</b><br>");
	}
}

function cstd()
{
  if (document.getElementById('cekstd').checked) 
  {
      document.getElementById("qtygood").readOnly = false; 
      //document.getElementById("qtystd").readOnly = false; 
  } else {
  	  $('#qtygood').val($('#qtygoodori').val());
  	  $('#qtystd').val(0);
      document.getElementById("qtygood").readOnly = true; 
      //document.getElementById("qtystd").readOnly = true; 
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
				timer: timer
			});
}
</script>