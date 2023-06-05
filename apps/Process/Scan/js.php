<script>

$("#scanner").keyup(function(event){
    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
        $('#inputtype').click(); 
    } });

function scaninput2(type){
	var scanner = $('#scanner').val();
	var splitscan = scanner.split('_');
	var wodb1 = $('#wo_no_scan').val();
	var wodb = wodb1.trim(); 						//wo dari db scan status 0
	var colordb = $('#garment_colors_scan').val();  //color dari db scan status 0
	var woscan = splitscan[2];						//wo dari yang sedang di scan
	var colorscan = splitscan[1]; 					//color dari yang sedang di scan
	var cutqty = parseInt($('#cutqtywo').val());    //Cutting Qty dari wo color yang sedang discan
	var totalqtyrec = parseInt($('#totalqtyrec').val()); //total qty dari yang sudah discan dan yang sudah di receive
	var typescanvalue = $('#typescan_value').val();
	var totalqtyscan = parseInt($('#totalqtyscan').val());
	var qtylast = $('#qtylast').val();
	
	if(wodb != ''){
		if(wodb == woscan && colordb == colorscan){
			if (cutqty > totalqtyrec){
				$('#scan_type').val(type);
				var data = $('.form-user').serialize();
				$.ajax({
						type: 'POST',
						url:  "apps/Process/Scan/simpan_pro.php",
						data: data,
						success: function() {
							$('#tampilscan').load("apps/Process/Scan/sourcedatascan_pro.php");
							$('#cart').load("apps/Process/Scan/cart_pro.php?type="+type);
							$('#scanner').val('');
							$('#scan_type').val('');
							$('#scanner').focus();
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
					url:  "apps/Process/Scan/simpan_pro.php",
					data: data,
					success: function() {
						$('#tampilscan').load("apps/Process/Scan/sourcedatascan_pro.php");
						$('#cart').load("apps/Process/Scan/cart_pro.php?type="+type);
						$('#scanner').val('');
						$('#scan_type').val('');
						$('#scanner').focus();
					}
			});
	}
}

function switchtype(val){
	$('#tampiltypeinput').load("apps/Process/Scan/typeinput.php?switchtype="+val);
}

function savetoreceive(type){
	if (type == '2') {
		$('#confirm').val(type);
		var data = $('.form-user').serialize();
			$.ajax({
					type: 'POST',
					url:  "apps/Process/Scan/simpan_pro.php",
					data: data,
					success: function() {
							$('#confirm').val('');
							alert('Data Saved');
							javascript: window.location.href="content.php?p="+$('#getpmod').val();
					}
			});
	}
	else {
		if ($('#balance').val() < 0) {
			if($('#idlast').val() == 1){
				if ($('#remark').val() != ''){
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
									url:  "apps/Process/Scan/simpan_pro.php",
									data: data,
									success: function() {
										$('#confirm').val('');
										alert('Data Saved');
										javascript: window.location.href="content.php?p="+$('#getpmod').val();
									}
							});
						}
					});
				} else {
					swal({
						icon: 'info',
						title: 'Fill Note',
						text: 'Receive Qty Less than Cutting Qty',
						footer: '<a href>Why do I have this issue?</a>'
					});
				}

			} else {
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
									url:  "apps/Process/Scan/simpan_pro.php",
									data: data,
									success: function() {
										$('#confirm').val('');
										alert('Data Saved');
										javascript: window.location.href="content.php?p="+$('#getpmod').val();
									}
							});
						}
					});
			}
		} else {
			swal({
				icon: 'error',
				title: 'Can not Submit!!',
				text: 'Receive Qty More than Cutting Qty',
				footer: '<a href>Why do I have this issue?</a>'
			});
		}
	}
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

</script>