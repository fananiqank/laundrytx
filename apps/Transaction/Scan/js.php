<script>
	function swall(title, text, icon, timer) {
		swal({
			title: title,
			text: text,
			icon: icon,
			timer: timer
		});
	}
	// $("#usercode").keyup(function(event){
	//     if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	//         $('#scanner').focus(); 
	//     } });

	//$("#scanner").keyup(function(event){
	//  if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	//$('#inputtype').click(); 
	//	scaninput(type);
	//  } });
	function onEnteruserin(event) {
		if (event.keyCode == 13) { // => Karakter enter dikenali sebagai angka 13
			$('#scanner').focus();
		}
	}

	function onEnter(event, type) {
		if (event.keyCode == 13) { // => Karakter enter dikenali sebagai angka 13
			scaninput(type);
		};
	}

	function onEnterqc(event, type) {
		if (event.keyCode == 13) { // => Karakter enter dikenali sebagai angka 13
			scaninputqc(type);
		};
	}

	function scaninput(type) {
		var usercode = $('#usercode').val();

		var scanner = $('#scanner').val();
		var splitscan = scanner.split('_');
		//wo yang sudah ter scan
		var wodb1 = $('#wo_no_scan').val();
		var wodb = wodb1.trim(); //wo dari db scan status 0
		var colordb = $('#garment_colors_scan').val(); //color dari db scan status 0
		//wo scan
		var woscan1 = splitscan[0];
		var firstwo = woscan1.substring(0, 3);
		var endwo = woscan1.substring(3);
		var woscan = firstwo + "EP/W/" + endwo; //wo dari yang sedang di scan
		//colors
		var colorscan = splitscan[2]; //color dari yang sedang di scan

		var cutqty = parseInt($('#cutqtywo').val()); //Cutting Qty dari wo color yang sedang discan
		var totalqtyrec = parseInt($('#totalqtyrec').val()); //total qty dari yang sudah discan dan yang sudah di receive
		var kode = $('#kodeid').val();
		var splitid = kode.split('_');

		var id = splitid[0];
		var wono = splitid[1];


		if (usercode != '') {
			if (wodb != '') {
				if (wodb == woscan) {
					if (cutqty > totalqtyrec) {
						$('#scan_type').val(type);
						var data = $('.form-user').serialize();
						$.ajax({
							type: 'POST',
							url: "apps/Transaction/Scan/simpan.php",
							data: data,
							success: function(ui) {
								splitui = ui.split('|');
								valreturn = splitui[0];
								valscan = splitui[1];
								valid = splitui[2];
								if (valreturn == 0) {
									swall('Data Not Found', 'Check again your QrCode :\n ' + valscan, 'warning', 2000);
								} else if (valreturn == 2) {
									swall('Data Not Scan on Output ' + valid, 'Check again your QrCode :\n ' + valscan, 'warning', 2000);
								} else if (valreturn == 3) {
									swall('Data Exist', 'Check again your QrCode :\n ' + valscan, 'warning', 2000);
								} else if (valreturn == 4) {
									swall('Colors Not Same', 'Check again your QrCode :\n ' + valscan, 'warning', 2000);
								} else if (valreturn == 5) {
									swall('Sequence Not Found', 'Please Create Sequence for this Data', 'error', 3000);
								} else {
									if (type == 1) {
										$('#tampilscan').load("apps/Transaction/Scan/sourcedatascan.php");
									} else {
										$('#tampilscan').load("apps/Transaction/Scan/sourcedatascandry.php");
									}
								}
								$('#cart').load("apps/Transaction/Scan/cart.php?type=" + type);
								$('#scanner').val('');
								$('#scan_type').val('');
								$('#scanner').focus();

							}
						});
					} else {
						swall("Maximum Cut Qty", "Receive more than Cut Qty\n Please Contact Supervisor", "error", 2000);
						$('#scanner').val('');
						$('#scanner').focus();
					}
				} else {
					swall("WO No Not Same", "Please Cek WO No On Garment", "error", 2000);
					$('#scanner').val('');
					$('#scanner').focus();
				}
			} else {
				//alert('a');
				if (kode == '' && wono != '') {
					swall("WO No Colors Not Planning", "Create Planning Proces", "error", 3000);
					$('#scanner').val('');
					$('#scanner').focus();
				} else {
					$('#scan_type').val(type);
					var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url: "apps/Transaction/Scan/simpan.php",
						data: data,
						success: function(ui) {
							splitui = ui.split('|');
							valreturn = splitui[0];
							valscan = splitui[1];
							valid = splitui[2];
							if (valreturn == 0) {
								swall('Data Not Found', 'Check again your QrCode :\n ' + valscan, 'warning', 2000);
							} else if (valreturn == 2) {
								swall('Data Not Scan on Output ' + valid, 'Check again your QrCode :\n ' + valscan, 'warning', 2000);
							} else if (valreturn == 3) {
								swall('Data Exist', 'Check again your QrCode :\n ' + valscan, 'warning', 2000);
							} else if (valreturn == 4) {
								swall('Colors Not Same', 'Check again your QrCode :\n ' + valscan, 'warning', 2000);
							} else if (valreturn == 5) {
								swall('Sequence Not Found', 'Please Create Sequence for this Data', 'error', 3000);
							} else {
								if (type == 1) {
									$('#tampilscan').load("apps/Transaction/Scan/sourcedatascan.php");
								} else {
									$('#tampilscan').load("apps/Transaction/Scan/sourcedatascandry.php");
								}
							}
							$('#cart').load("apps/Transaction/Scan/cart.php?type=" + type);
							$('#scanner').val('');
							$('#scan_type').val('');
							$('#scanner').focus();
						}
					});
				}
			}
		} else {
			swall('Fill User', 'Please Scan Your User', 'error', 2000);
			$('#usercode').focus();
		}
	}

	function switchtype(val) {
		$('#tampiltypeinput').load("apps/Transaction/Scan/typeinput.php?switchtype=" + val);
	}

	function savetoreceive(type) {
		if ($('#balance').val() <= 0) {
			if ($('#idlast').val() == 1) {
				if ($('#remark').val() != '') {
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
								url: "apps/Transaction/Scan/simpan.php",
								data: data,
								success: function(lot) {
									$('#confirm').val('');
									swal({
										icon: 'success',
										title: 'Lot No : ' + lot,
										text: 'Saved',
									}).then((willoke) => {
										if (willoke) {
											javascript: window.open("lib/pdf-qrcode.php?c=" + lot, "_blank", "width=700,height=450");
											//window.location.reload();
											$('#close').click(function() {
												$('#funModalrec').modal('hide');
											});
											$('#tampilscan').load("apps/Transaction/Scan/sourcedatascan.php");
											$('#cart').load("apps/Transaction/Scan/cart.php");
										}
									});
								}
							});
						}
					});
				} else {
					swall('Fill Note', 'Receive Qty Less than Cutting Qty', 'error', 2000);
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
							url: "apps/Transaction/Scan/simpan.php",
							data: data,
							success: function(lot) {
								$('#confirm').val('');
								swal({
									icon: 'success',
									title: 'Lot No : ' + lot,
									text: 'Saved',
								}).then((willoke) => {
									if (willoke) {
										// javascript: window.open("content.php?option=phpqrcode&task=print_qr_rec&act=ugr_laundry_print?no="+lot, "_blank", "width=700,height=450");
										javascript: window.open("lib/pdf-qrcode.php?c=" + lot, "_blank", "width=700,height=450");
										//window.location.reload();
										document.getElementById("tutup").click();
										$('#tampilscan').load("apps/Transaction/Scan/sourcedatascan.php");
										$('#cart').load("apps/Transaction/Scan/cart.php");
									}
								});
							}
						});
					}
				});
			}
		} else {
			swall('Can not Submit!!', 'Receive Qty More than Cutting Qty', 'error', 2000);
		}

	}

	function savetoreceiveqc(type) {
		if ($('#balance').val() <= 0) {
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
								url: "apps/Transaction/Scan/simpan_pro.php",
								data: data,
								success: function(lot) {
									$('#confirm').val('');
									swal({
										icon: 'success',
										title: 'Lot No : ' + lot,
										text: 'Saved',
									}).then((willoke) => {
										if (willoke) {
											window.location.reload();
											$('#close').click(function() {
												$('#funModalrec').modal('hide');
											});
										}
									});
								}
							});
						}
				});
				
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
				$('#sumbit').show();
				$('#idlast').val(0);
			}
		} else {
			$('#idlast').val(0);
		}
	}

	function hitungcut(val, id) {
		$('#cutqty').val();
		$('#willrec').val();
		var jumbalance = parseInt($('#willrec').val()) - parseInt($('#cutqty').val());
		$('#balance').val(jumbalance);
	}

	//content scan pro
	function onEnteruser(event, val) {
		if (event.keyCode == 13) { // => Karakter enter dikenali sebagai angka 13
			$('#lot_number').focus();
		}
	}

	function onEnterlot(event, val) {
		if (event.keyCode == 13) { // => Karakter enter dikenali sebagai angka 13
			var lot = val.substring(0,1);
			if (lot =='L') {
				$('#typescan').focus();
				$('#mod').show();
				$('#cart').load('apps/Transaction/Scan/cart_pro.php?lot=' + val);
				$('#cart').show();
				$('#tampilinputscan').load('apps/Transaction/Scan/inputscan.php?lot=' + val);
			}
			else {
				swall("Lot Number Incorrect!", "Check Lot Number", "error", 1000);
			}
		}
	}

	function onEnterPanel(event, val) {
		if (event.keyCode == 13) { // => Karakter enter dikenali sebagai angka 13
			$('#reject_type').focus();
		}
	}

	function onEnterReject(event, val) {
		if (event.keyCode == 13) { // => Karakter enter dikenali sebagai angka 13
			$('#scanner').focus();
		}
	}

	function ftypescan(val) {
		$('#typescan_value').val(val);
		if (val == 2) {
			$('#desinfect').show();
			$('#panel_type').focus();
		} else if (val == 3) {
			$('#desinfect').show();
			$('#panel_type').focus();
		} else {
			$('#defect').val('');
			$('#desinfect').hide();
			$('#defect_value').val('');
			$('#scanner').focus();
		}
	}

	function scaninputqc(type) {
		var scanner = $('#scanner').val();
		var splitscan = scanner.split('_');
		var wodb1 = $('#wo_no_scan').val();
		var wodb = wodb1.trim(); //wo dari db scan status 0
		var colordb = $('#garment_colors_scan').val(); //color dari db scan status 0
		//wo scan
		var woscan1 = splitscan[0];
		var firstwo = woscan1.substring(0, 3);
		var endwo = woscan1.substring(3);
		var woscan = firstwo + "EP/W/" + endwo; //wo dari yang sedang di scan

		var colorscan = splitscan[1]; //color dari yang sedang di scan
		var cutqty = parseInt($('#cutqtywo').val()); //Cutting Qty dari wo color yang sedang discan
		var totalqtyrec = parseInt($('#totalqtyrec').val()); //total qty dari yang sudah discan dan yang sudah di receive
		var typescanvalue = $('#typescan_value').val();
		var totalqtyscan = parseInt($('#totalqtyscan').val());
		var qtylast = $('#qtylast').val();
		var lot = $('#lot_number').val();

		if (typescanvalue == '') {
			swall("Choose Type Scan!!", "Please Choose one of Type Scan", "error", 1000);
			$('#scanner').val('');
			$('#scan_type').val('');
			$('#scanner').focus();
		}
		//jika Lot number belum saatnya QC
		else if (qtylast == 'NONE') {
			swall("Not This Process", "the lot number has not arrived at the qc process", "error", 2000);
			$('#scanner').val('');
			$('#scan_type').val('');
			$('#scanner').focus();
		}
		//jika jumlah yang di scan melebihi qty pada lot number tersebut
		else if (totalqtyscan >= qtylast) {
			swall("Over Qty Lot!!", "", "error", 1000);
			$('#scanner').val('');
			$('#scan_type').val('');
			$('#scanner').focus();
		}
		//jika type yang digunakan adalah reject harus pilih defect
		else if (typescanvalue != '1' && ($('#panel_type').val() == '' || $('#reject_type').val() == '')) {
			swall("Location or Reject Empty!!", "Please input location & reject", "error", 1000);
			$('#scanner').val('');
			$('#scan_type').val('');
			$('#typescan_value').val(typescanvalue);
			$('#typescan').val(typescanvalue);
			$('#panel_type').focus();
		} else {
			if (wodb != '') {
				if (wodb == woscan) {
					if (cutqty > totalqtyrec) {
						$('#scan_type').val(type);
						var data = $('.form-user').serialize();
						$.ajax({
							type: 'POST',
							url: "apps/Transaction/Scan/simpan_pro.php",
							data: data,
							success: function(ui) {
								splitui = ui.split('|');
								valreturn = splitui[0];
								valscan = splitui[1];
								valid = splitui[2];
								if (valreturn == 0) {
									swall('Data Not Found', 'Check again your QrCode :\n ' + valscan, 'warning', 1000);
									$('#scanner').focus();
								} else if (valreturn == 2) {
									swall('Data Not Scan on Output ' + valid, 'Check again your QrCode :\n ' + valscan, 'warning', 3000);
									$('#scanner').focus();
								} else if (valreturn == 3) {
									swall('The Data is already in the QC', 'Check again your QrCode :\n ' + valscan, 'warning', 3000);
									$('#scanner').focus();
								} else if (valreturn == 4) {
									swall('Data Exist On Cart', 'Check again your QrCode :\n ' + valscan, 'warning', 3000);
									$('#scanner').focus();
								} else {
									if (type == 1) {
										$('#tampilscan').load("apps/Transaction/Scan/sourcedatascan.php");
									} else {
										$('#tampilscan').load("apps/Transaction/Scan/sourcedatascan_pro.php?type=" + type);
									}
								}
								$('#cart').load("apps/Transaction/Scan/cart_pro.php?type=" + type + "&lot=" + lot);
								$('#scanner').val('');
								$('#scan_type').val('');
								$('#scanner').focus();
								$('#typescan_value').val(typescanvalue);
								$('#typescan').val(typescanvalue);
							}
						});
					} else {
						swall("Maximum Cut Qty", "Receive more than Cut Qty\n Please Contact Supervisor", "error", 3000);
						$('#scanner').val('');
						$('#scanner').focus();
					}
				} else {
					swall("WO No Not Same", "Please Cek WO No & Colors & Ex_fty_Date On Garment ", "error", 2000);
					$('#scanner').val('');
					$('#scanner').focus();
				}
			} else {

				$('#scan_type').val(type);
				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url: "apps/Transaction/Scan/simpan_pro.php",
					data: data,
					success: function(ui) {
						splitui = ui.split('|');
						valreturn = splitui[0];
						valscan = splitui[1];
						valid = splitui[2];
						if (valreturn == 0) {
							swall('Data Not Found', 'Check again your QrCode :\n ' + valscan, 'warning', 1000);
							$('#scanner').focus();
						} else if (valreturn == 2) {
							swall('Data Not Scan on Output ' + valid, 'Check again your QrCode :\n ' + valscan, 'warning', 3000);
							$('#scanner').focus();
						} else if (valreturn == 3) {
							swall('The Data is already in the QC', 'Check again your QrCode :\n ' + valscan, 'warning', 3000, );
							$('#scanner').focus();
						} else if (valreturn == 4) {
							swall('Data Exist On Cart', 'Check again your QrCode :\n ' + valscan, 'warning', 3000);
							$('#scanner').focus();
						} else {

							if (type == 1) {
								$('#tampilscan').load("apps/Transaction/Scan/sourcedatascan.php");
							} else {
								$('#tampilscan').load("apps/Transaction/Scan/sourcedatascan_pro.php?type=" + type);
							}
						}
						$('#cart').load("apps/Transaction/Scan/cart_pro.php?type=" + type + "&lot=" + lot);
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
	// end content scan pro
</script>