<script>

$("#scanner").keyup(function(event){
    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
        $('#inputtype').click(); 
    } });

function scaninput(type){
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

if (typescanvalue == ''){
			swal({
				title: "Choose Type Scan!!",
				text: "Please Choose one of Type Scan",
				icon: "error",
				timer: 2000
			});
			$('#scanner').val('');
			$('#scan_type').val('');
			$('#scanner').focus();
} else if (typescanvalue == '2' && $('#defect').val() == '') {
		swal({
				title: "Choose Defect!!",
				text: "Please Choose one of Defect",
				icon: "error",
				timer: 2000
			});
			$('#scanner').val('');
			$('#scan_type').val('');
			$('#scanner').focus();
			$('#typescan_value').val(typescanvalue);
			$('#typescan').val(typescanvalue);
}
else {
	if(wodb != ''){
		if(wodb == woscan && colordb == colorscan){
			if (cutqty > totalqtyrec){
				$('#scan_type').val(type);
				var data = $('.form-user').serialize();
				$.ajax({
						type: 'POST',
						url:  "apps/Transaction/QC/simpan.php",
						data: data,
						success: function(ui) {
							splitui = ui.split('|');
							valreturn = splitui[0];
							valscan = splitui[1];
							valid = splitui[2];
							if (valreturn == 0){
								swal({
									 icon: 'warning',
									 title: 'Data Not Found',
									 text: 'Check again your QrCode :\n '+valscan,
									 footer: '<a href>Why do I have this issue?</a>'
									});
							} else if (valreturn == 2){
								swal({
									 icon: 'warning',
									 title: 'Data Not Scan on Output '+valid,
									 text: 'Check again your QrCode :\n '+valscan,
									 footer: '<a href>Why do I have this issue?</a>'
									});
							} 
							else if (valreturn == 3){
								swal({
									 icon: 'warning',
									 title: 'Data Exist',
									 text: 'Check again your QrCode :\n '+valscan,
									 footer: '<a href>Why do I have this issue?</a>'
									});
							} 
							else {
								if (type == 1){
									$('#tampilscan').load("apps/Transaction/QC/sourcedatascan.php");
								} else {
									$('#tampilscan').load("apps/Transaction/QC/sourcedatascan.php?type="+type);
								}
							}
							$('#cart').load("apps/Transaction/QC/cart.php?type="+type);
							$('#scanner').val('');
							$('#scan_type').val('');
							$('#scanner').focus();
							$('#typescan_value').val(typescanvalue);
						    $('#typescan').val(typescanvalue);
						}
				});
			} else {
				swal({
					title: "Maximum Cut Qty",
					text: "Receive more than Cut Qty\n Please Contact Supervisor",
					icon: "error",
					});
			$('#scanner').val('');
			$('#scanner').focus();
			}
		} else {
				swal({
					title: "CMT Colors Not Same",
					text: "Please Cek CMT No & Colors On Garment",
					icon: "error",
					timer: 2000
					});
			$('#scanner').val('');
			$('#scanner').focus();
		}
	} else {
			$('#scan_type').val(type);
			var data = $('.form-user').serialize();
			$.ajax({
					type: 'POST',
					url:  "apps/Transaction/QC/simpan.php",
					data: data,
					success: function(ui) {
						splitui = ui.split('|');
						valreturn = splitui[0];
						valscan = splitui[1];
						valid = splitui[2];
						if (valreturn == 0){
								swal({
								 icon: 'warning',
								 title: 'Data Not Found',
								 text: 'Check again your QrCode :\n '+valscan,
								 timer: 2000,
								});
						} else if (valreturn == 2){
								swal({
									 icon: 'warning',
									 title: 'Data Not Scan on Output '+valid,
									 text: 'Check again your QrCode :\n '+valscan,
									 footer: '<a href>Why do I have this issue?</a>'
									});
						} 
						else if (valreturn == 3){
								swal({
									 icon: 'warning',
									 title: 'Data Exist',
									 text: 'Check again your QrCode :\n '+valscan,
									 footer: '<a href>Why do I have this issue?</a>'
									});
						} 
						else {
							$('#tampilscan').load("apps/Transaction/QC/sourcedatascan.php?type="+type);
						}
						$('#cart').load("apps/Transaction/QC/cart.php?type="+type);
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

function switchtype(val){
	$('#tampiltypeinput').load("apps/Transaction/QC/typeinput.php?switchtype="+val);
}

function savetoreceive(type){
		//if ($('#balance').val() < 0) {
			// if($('#idlast').val() == 1){
			// 	if ($('#remark').val() != ''){
					swal({
						title: "Are You Sure?",
						text: "Save Data QC Inspect",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					}).then((savedata) => {
						if (savedata) {
							$('#confirm').val(type);
							var data = $('.form-user').serialize();
							$.ajax({
									type: 'POST',
									url:  "apps/Transaction/QC/simpan.php",
									data: data,
									success: function() {
										$('#confirm').val('');
										swal("Saved!", {
									      icon: "success",
									    });
										javascript: window.location.href="content.php?p="+$('#getpmod').val();
									}
							});
						}
					});
// 				} else {
// 					swal({
// 						icon: 'info',
// 						title: 'Fill Note',
// 						text: 'Receive Qty Less than Cutting Qty',
// 						footer: '<a href>Why do I have this issue?</a>'
// 					});
// 				}

// 			} else {
// 				swal({
// 						title: "Are You Sure?",
// 						text: "Save Receiving",
// 						icon: "warning",
// 						buttons: true,
// 						dangerMode: true,
// 					}).then((savedata) => {
// 						if (savedata) {
// 							$('#confirm').val(type);
// 							var data = $('.form-user').serialize();
// 							$.ajax({
// 									type: 'POST',
// 									url:  "apps/Transaction/QC/simpan.php",
// 									data: data,
// 									success: function() {
// 										$('#confirm').val('');
// 										swal("Saved!", {
// 									      icon: "success",
// 									    });
// 										javascript: window.location.href="content.php?p="+$('#getpmod').val();
// 									}
// 							});
// 						}
// 					});
// 			}
// 		// } else {
// 		// 	swal({
// 		// 		icon: 'error',
// 		// 		title: 'Can not Submit!!',
// 		// 		text: 'Receive Qty More than Cutting Qty',
// 		// 		footer: '<a href>Why do I have this issue?</a>'
// 		// 	});
// 		// }
	
 }

function tooltip(val,id){
		
		var will = $('#willrec').val();
		var cut = $('#cutqty').val();
		var jum = parseInt(cut)-parseInt(cut);
		if (val.checked){
			if (parseInt(will) > parseInt(cut)){
				swal({
				  icon: 'error',
				  title: 'Can not Submit!!',
				  text: 'Receive Qty More than Cutting Qty',
				  footer: '<a href>Why do I have this issue?</a>'
				});
				$('#sumbit').hide();
				$('#lastrec').prop("checked", false);
				$('#idlast').val(2);
			} else if(parseInt(will) < parseInt(cut)) {
				swal({
				  icon: 'info',
				  title: 'Fill Note',
				  text: 'Receive Qty Less than Cutting Qty',
				  footer: '<a href>Why do I have this issue?</a>'
				});
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

function typescan(val){
	$('#typescan_value').val(val);
	if (val == 2){
		$("#desinfect").show();
	} else {
		$('#defect').val('');
		$("#desinfect").hide();
		$('#defect_value').val('');
	}
}
function defectscan(val){
	$('#defect_value').val(val);
}

</script>