<script>
	document.getElementById('usercod').focus();

	function swall(title, text, icon, timer) {
		swal({
			title: title,
			text: text,
			icon: icon,
			timer: timer
		});
	}

	cm = $('#nocmt').val();
	co = $('#nocolor').val();
	xty = $('#exftydate').val();
	trimcmt = cm.trim();
	expcmt = trimcmt.split('/');
	cmts = expcmt.join('-');

	trimcolor = co.trim();
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
	// (function($) {

	// 	'use strict';

	// 	var datatableInit = function() {
	// 		var $table = $('#datatable-ajax2');
	// 		$table.dataTable({
	// 			"processing": true,
	// 			"serverSide": true,
	// 			"ajax": "apps/Transaction/Receiving_scan/data.php",
	// 			// "fnRowCallback": function(nRow, aData, iDisplayIndex) {
	// 			// 	var index = iDisplayIndex + 1;
	// 			// 	$('td:eq(0)', nRow).html(index);
	// 			// 	return nRow;
	// 			// },
	// 			"lengthMenu": [
	// 				[10, 25, 50, 100, -1],
	// 				[10, 25, 50, 100, "All"]
	// 			]
	// 		});

	// 	};

	// 	$(function() {
	// 		datatableInit();
	// 	});

	// 	console.log(datatableInit);
	// }).apply(this, [jQuery]);
if(cm != ''){
	$(document).ready(function(){
       // tampil_data_barang();   //pemanggilan fungsi tampil barang.
        var spintable = $('#datatable-ajax2').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": "apps/Transaction/Receiving_scan/data.php?cm="+cmts+"&co="+color+"&xty="+xty,
				// "fnRowCallback": function(nRow, aData, iDisplayIndex) {
				// 	var index = iDisplayIndex + 1;
				// 	$('td:eq(0)', nRow).html(index);
				// 	return nRow;
				// },
				"lengthMenu": [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"]
				]
			});
      //  testrecursive();
     //    function testrecursive(){
    	// 	setTimeout(() => {  spintable.ajax.reload(); testrecursive(); console.log("World!"); }, 10000);    
    	// }

	});
}
    function pindahData2(cmt,colors){

	 	//CMT
	 	if (cmt == '') {
	 		swal({
				icon: 'error',
				title: 'WO No Not Found',
				text: 'Please Fill Wo No Number',
				footer: '<a href>Why do I have this issue?</a>'
			});
	 	} else if (colors == '') {
	 		swal({
				icon: 'error',
				title: 'Colors Not Found',
				text: 'Please Fill Colors',
				footer: '<a href>Why do I have this issue?</a>'
			});
	 	} 
	 	// else if (exftydate == '') {
	 	// 	swal({
			// 	icon: 'error',
			// 	title: 'Ex Fty Date Not Found',
			// 	text: 'Please Fill Ex Fty Date',
			// 	footer: '<a href>Why do I have this issue?</a>'
			// });
	 	// } 
	 	else {
	 		trimcmt = cmt.trim();
			expcmt = trimcmt.split('/');
	 		cmts = expcmt.join('-');
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

	 		 var hal = $('#getp').val();
			 window.location="content.php?"+hal+"&cm="+cmts+"&co="+color;
		}
	}

	$(function() {  
		    	//$('#nocmt').focus();
		        $("#nocmt").autocomplete({  
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Transaction/Receiving_scan/sourcedata.php",
			                { d:'1', term:request.term}, 
			                response
			            );
			        },
		           	minLength:2, 
		        	select: function (event, ui) {
		            if (ui.item != undefined) {
		                $(this).val(ui.item.value);	
		                //$('#selected_id').val(ui.item.id);
				        $( "#idcmt" ).val( ui.item.id );
				       	//depar(ui.item.id_departemen);
		            	} 
		            	return false;
	        		}
		        });
	});

	$(function() {  
		        $("#nocolor").autocomplete({
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Transaction/Receiving_scan/sourcedata.php",
			                { d:'2', term:request.term, scmt:$('#nocmt').val(), xty:$('#exftydate').val()}, 
			                response
			            );
			        },  
		           	minLength:0, 
		           	select: function (event, ui) {
		           	if (ui.item != undefined) {
		                $(this).val(ui.item.value);	
				        $( "#idcolor" ).val( ui.item.id );
		            	} 
		            	return false;
	        		}
		        }).focus(function () {
				    $(this).autocomplete("search", "");
			});        
	});

	$(function() {  
		    	
		        $("#exftydate").autocomplete({
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Transaction/Receiving_scan/sourcedata.php",
			                { d:'3', term:request.term, scmt:$('#nocmt').val(), scolor:$('#nocolor').val()}, 
			                response
			            );
			        }, 
		           	minLength:0, 
		           	select: function (event, ui) {
		            if (ui.item != undefined) {
		                $(this).val(ui.item.value);	
		            	} 
		            	return false;
	        		}
		        }).focus(function () {
				    $(this).autocomplete("search", "");
			});
	});
   
	function savetoreceive(id,type) {
		if ($('#balance').val() <= 0) {
			if ($('#idlast').val() == 1) {
				if ($('#remark').val() != '') {
					if ($('#usercode').val() != ''){
						swal({
							title: "Are You Sure?",
							text: "Save Receiving",
							icon: "warning",
							buttons: true,
							dangerMode: true,
						}).then((savedata) => {
							if (savedata) {
								$('#confirm').val(type);
								var data = $('.form-user').serialize();
								$.ajax({
									type: 'POST',
									url: "apps/Transaction/Receiving_scan/simpan.php",
									data: data,
									success: function(lot) {
									    $('#datatable-ajax2').DataTable().ajax.reload();
										explot = lot.split('_');
										$('#confirm').val('');
										swal({
											icon: 'success',
											title: 'Lot No : ' + explot[0],
											text: 'Saved',
										}).then((willoke) => {
											if (willoke) {
												javascript: window.open("lib/pdf-qrcode.php?c=" + explot[1], "_blank", "width=700,height=450");
												window.location.reload();
												$('#close').click(function() {
													$('#funModalrec').modal('hide');
												});
												$('#tampilscan').load("apps/Transaction/Receiving_scan/sourcedatascan.php");
												$('#cart').load("apps/Transaction/Receiving_scan/cart.php");
											}
										});
									}
								});
							}
						});
					} else {
						swall('User Not Found', 'Please Input User!!', 'error', 2000);
					}
				} else {
					swall('Fill Note', 'Receive Qty Less than Cutting Qty', 'error', 2000);
				}

			} else {
				if ($('#usercode').val() != ''){
					swal({
						title: "Are You Sure?",
						text: "Save Receiving",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					}).then((savedata) => {
						if (savedata) {
							$('#confirm').val(type);
							var data = $('.form-user').serialize();
							$.ajax({
								type: 'POST',
								url: "apps/Transaction/Receiving_scan/simpan.php",
								data: data,
								success: function(lot) {
									$('#datatable-ajax2').DataTable().ajax.reload();
									explot = lot.split('_');
									$('#confirm').val('');
									swal({
										icon: 'success',
										title: 'Lot No : ' + explot[0],
										text: 'Saved',
									}).then((willoke) => {
										if (willoke) {
											// javascript: window.open("content.php?option=phpqrcode&task=print_qr_rec&act=ugr_laundry_print?no="+lot, "_blank", "width=700,height=450");
											javascript: window.open("lib/pdf-qrcode.php?c=" + explot[1], "_blank", "width=700,height=450");
											//window.location.reload();
											document.getElementById("tutup").click();
											$('#tampilscan').load("apps/Transaction/Receiving_scan/sourcedatascan.php");
											$('#cart').load("apps/Transaction/Receiving_scan/cart.php");
										}
									});
								}
							});
						}
					});
				} else {
					swall('User Not Found', 'Please Input User!!', 'error', 2000);
				}
			}
		} else {
			swall('Can not Submit!!', 'Receive Qty More than Cutting Qty', 'error', 2000);
		}

	}

	function tooltip(val, id) {
		var will = $('#willrec').val();
		var cut = $('#cutqty').val();
		var jum = parseInt(cut) - parseInt(will);
		if (val.checked) {
			if (parseInt(will) > parseInt(cut)) {
				swall('Can not Submit!!', 'Receive Qty More than Cutting Qty', 'error', 2000);
				$('#sumbit').hide();
				$('#lastrec').prop("checked", false);
				$('#idlast').val(2);
			} else if (parseInt(will) < parseInt(cut)) {
				swall('Fill Note', 'Receive Qty Less than Cutting Qty', 'info', 2000);
				$('#sumbit').show();
				$('#idlast').val(1);
				$('$remark').focus();
			} else {
				alert("c");
				$('#sumbit').show();
				$('#idlast').val(0);
			}
		} else {
			$('#idlast').val(0);
		}
	}

	function ceknow(skg,rcv){
		// alert(skg);
		// alert(rcv);
		if(parseInt(rcv) < parseInt(skg)) {
			swall('Qty More Than Receive Scan', 'Check Receive Now', 'error', 2000);
			$('#recnow').val('');
			$('#willrec').val(0);
			$('#balance').val(0);
		} else {
			 var willrec =  parseInt($('#qtyrec').val())+parseInt(skg);
			 if(willrec > 0){
			 	$('#willrec').val(willrec);
			 	$('#balance').val(parseInt(willrec)-$('#totalrecscan').val())

			 }else {
			 	$('#willrec').val(0);
			 	$('#balance').val(0);
			 }
		}

	}
</script>