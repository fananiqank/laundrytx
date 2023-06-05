<script>
//content scan pro
function onEnterUser(event,val){
 	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	        $('#lot_no').focus(); 
	}
} 

function onEnterLot(event,val){
 	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	    var lot = $("#lot_no").val();
        var user = $("#userid").val();
        var master_type_process_id = $("#mastertypeid").val();
        cekuser(lot,user,master_type_process_id);
	}
} 	

function onEnter(){
	    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	        $('#inputtype').click(); 
	    };
}

	function cekuser(lot,user,master_type_process_id){
		var explot = lot.split('-');
		var lotres = explot[0].substr(-4,1);
		//alert(lotres);
		if (lot == ''){
			swal({
					  icon: 'error',
					  title: 'Lot Number Empty',
					  text: 'Lot Number Must be Filled',
					  footer: '<a href>Why do I have this issue?</a>'
					});
		} else {
		//alert(lotres);	
		$('#sourcedespscan').load("apps/Transaction/DespatchScan/sourcedespscan.php?lot="+lot+"&user="+user+"&typelot="+lotres);
		$('#cart').load("apps/Transaction/DespatchScan/cart.php?lot="+lot);
		}
	}


function scaninput(type,lot){
	var scanner = $('#scanner').val();
	var splitscan = scanner.split('_');
	var wodb1 = $('#wo_no_scan').val();
	var wodb = wodb1.trim(); 	
	var colordb = $('#garment_colors_scan').val();  //color dari db scan status 0
	var woscan = splitscan[0];						//wo dari yang sedang di scan
	var colorscan = splitscan[1];	//color dari yang sedang di scan
	var cutqty = parseInt($('#cutqtywo').val());    //Cutting Qty dari wo color yang sedang discan
	var totalqtyrec = parseInt($('#totalqtyrec').val()); //total qty dari yang sudah discan dan yang sudah di receive
	var typescanvalue = $('#typescan_value').val();
	var totalqtyscan = parseInt($('#totalqtyscan').val());
	var qtylast = $('#qtylast').val();
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
	} 
	//jika jumlah yang di scan melebihi qty pada lot number tersebut
	else if (totalqtyscan >= qtylast){
			swal({
				title: "Over Qty Lot!!",
				text: "",
				icon: "error",
				timer: 2000
			});
			$('#scanner').val('');
			$('#scan_type').val('');
			$('#scanner').focus();
	}  
	// jika type yang digunakan adalah reject harus pilih defect
	else if (typescanvalue == '2' && $('#defect').val() == '') {
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
			if(wodb == woscan){
				if (cutqty > totalqtyrec){
					$('#scan_type').val(type);
					var data = $('.form-user').serialize();
					$.ajax({
							type: 'POST',
							url:  "apps/Transaction/DespatchScan/simpan.php",
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
										 title: 'Data not Good in QC'+valid,
										 text: 'Check again your QrCode :\n '+valscan,
										 timer: 2000,
										});
								} 
								else if (valreturn == 3){
									swal({
										 icon: 'warning',
										 title: 'The Data is already Scan',
										 text: 'Check again your QrCode :\n '+valscan,
										 timer: 2000,
										});
								} 
								else {
									if (type == 1){
										$('#tampilscan').load("apps/Transaction/DespatchScan/sourcedatascan.php");
									} else {
										$('#tampilscan').load("apps/Transaction/DespatchScan/sourcedatascan.php?type="+type);
									}
								}
								$('#cart').load("apps/Transaction/DespatchScan/cart.php?type="+type+"&lot="+lot);
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
						title: "WO No Not Same",
						text: "Please Cek WO No On Garment",
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
					url:  "apps/Transaction/DespatchScan/simpan.php",
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
								 title: 'Data not Good in QC'+valid,
								 text: 'Check again your QrCode :\n '+valscan,
								 timer: 2000,
							});
						} else if (valreturn == 3){
							swal({
								 icon: 'warning',
								 title: 'The Data is already Scan',
								 text: 'Check again your QrCode :\n '+valscan,
								 timer: 2000,
							});
						} 
						else {
								if (type == 1){
									$('#tampilscan').load("apps/Transaction/DespatchScan/sourcedatascan.php");
								} else {
									$('#tampilscan').load("apps/Transaction/DespatchScan/sourcedatascan.php?type="+type);
								}
						}
						$('#cart').load("apps/Transaction/DespatchScan/cart.php?type="+type+"&lot="+lot);
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
	$('#tampiltypeinput').load("apps/Transaction/DespatchScan/typeinput.php?switchtype="+val);
}

function savetoreceive(type){
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
						url:  "apps/Transaction/DespatchScan/simpan.php",
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

function viewdata(a){
		if(a == 'v') {
			javascript: window.location.href="content.php?option=View&task=despatch&act=ugr_view";
		} else {
			javascript: window.location.href="content.php?option=Transaction&task=despatch&act=ugr_transaction";
		}
		
	}

</script>