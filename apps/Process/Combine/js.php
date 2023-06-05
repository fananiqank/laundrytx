<script>
	$('#usercode').focus();
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
	        cekuser(lot,user,usercode,master_type_process_id);
 	   } 
	}

	function cekuser(lot,user,usercode,master_type_process_id){
		var explot = lot.split('-');
		var lotres = explot[0].substr(-4,1);
		if (lot == ''){
			swall('Lot Number Empty', 'Lot Number Must be Filled','error',1000);
			$('#lot_no').focus();
		} else if (lotres == 'A'){
			swall('Lot Number Can not Combine','Only Lot Number not Lot Receive','error',2000);
			$('#lot_no').val('');
			$('#lot_no').focus();
		}
		else {
			$('#action').val("input");
			var data = $('.form-user').serialize();
				$.ajax({
						type: 'POST',
						url:  "apps/Process/Combine/cekdata.php?cekdata=1",
						data: data,
						success: function(ui) {
							if (ui == 1){
								$.ajax({
										type: 'POST',
										url:  "apps/Process/Combine/simpan.php",
										data: data,
										success: function() {
											//window.location.reload();
											 $('#tampilcombine').load("apps/Process/Combine/tampilcombine.php");
											 $('#lot_no').val('');
											
									}
								});
							} 
							else if (ui == 3){
								swall('Lot Number is Break','','warning',2000);
								$('#lot_no').val('');
							} 
							else if (ui == 4){
								swall('WO No Colors Ex Fty date Not Same','','warning',2000);
								$('#lot_no').val('');
							} 
							else if (ui == 5){
								swall('Type Lot Not Same','','warning',2000);
								$('#lot_no').val('');
							} 
							else if (ui == 6){
								swall('Next Step Not Same','','warning',2000);
								$('#lot_no').val('');
							} 
							else if (ui == 7){
								swall('Lot Number is Done','Sequence Lot is Done','warning',2000);
								$('#lot_no').val('');
							} 
							else if (ui == 8){
								swall('Next Process Can not Combine','check your next process and Lot after IN Process','warning',2000);
								$('#lot_no').val('');
							} 
							else if (ui == 9){
								swall('Lot Can not Combine','QA Sample | Pre Bulk Can not Combine','warning',2000);
								$('#lot_no').val('');
							} 
							else {
								swall('Lot Number already exist','','error',2000);
								$('#lot_no').val('');
							}	
							$('#action').val('');
							document.getElementById('lot_no').focus();
					}
				});
		}
		//alert(lotres);
	}

	function hapuscombine(id){
		swal({
				  title: "Are You Sure Delete?",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
		}).then((willSave) => {
			if (willSave) {
				$('#action').val("delete");
				var data = $('.form-user').serialize();
					$.ajax({
							type: 'POST',
							url:  "apps/Process/Combine/simpan.php?id="+id,
							data: data,
								success: function() {
									swal({
									  icon: 'success',
									  title: 'Deleted',
									});	
									//window.location.reload();
									$('#tampilcombine').load("apps/Process/Combine/tampilcombine.php");
									$('#action').val('');
								}
					});
			}
		});
	}

	function savecombine(lotnew){
		if ($('#totalcount').val() <= 1){
				swall("Only One Lot","Combine Lot Must be more than One Lot", "error",3000);
		} else {
				swal({
						  title: "Are You Sure Combine Lot?",
						  icon: "warning",
						  buttons: true,
						  dangerMode: true,
				}).then((willSave) => {
					if (willSave) {
						$('#action').val("savecombine");
						var data = $('.form-user').serialize();
							$.ajax({
									type: 'POST',
									url:  "apps/Process/Combine/simpan.php",
									data: data,
										success: function() {
											lotsplit = lotnew.split("|");
											swal({
												  icon: 'success',
												  title: 'Lot No : '+ lotsplit[0],
												  text: 'Saved',
												  footer: '<a href>Why do I have this issue?</a>'
											}).then((willoke) => {
												javascript: window.open("lib/pdf-qrcode.php?c="+ lotsplit[1], "_blank", "width=700,height=450");
												$('#tampilcombine').load("apps/Process/Combine/tampilcombine.php");
												$('#action').val('');
											});
											
										}
							});
					}
				});
		}
	}

//buka readonly hanya untuk kolom qty paling akhir
	document.getElementById("qtylot_"+$('#totalcount').val()).readOnly = false;
//show kolom balance hanya untuk kolom balance paling akhir
	document.getElementById("balance_"+$('#totalcount').val()).style.display = "inline";
	
	function cekkg(){
		const jumlahkg = $('#totalcount').val();
		let sam = 0;
		//alert(jumlahsplit);
		for (j=1;j<=jumlahkg;j++){
			if ($('#kg_'+j).val() == ''){
				var kg = "0";
			} else { 	
				var kg = $('#kg_'+j).val();
			}
			sam += parseInt(kg);
			$('#totalkg').val(sam);
		}
	}

	function cekqtylot(){
		const jumlahqty = $('#totalcount').val();
		let sam = 0;
		//alert(jumlahsplit);
		for (j=1;j<=jumlahqty;j++){
			if (Number($('#qtylot_'+j).val()) > Number($('#qtylotori_'+j).val())){
				swal({
						  icon: "error",
						  title: "Qty lot Over",
						  timer: 2000,
				});
				window.location.reload();
			}
			else if ($('#qtylot_'+j).val() == ''){
				var qtylot = "0";
			} else { 	
				var qtylot = $('#qtylot_'+j).val();
			}
			sam += parseInt(qtylot);
			$('#totalcombine').val(sam);

		}
		let hasil = parseInt($('#totalqtyori').val())-parseInt($('#totalcombine').val());
		$("#balance_"+jumlahqty).val(hasil);
		$("#totalbalance").val(hasil);
	}

	function edit(lotno){
		$('#tampilcombinehold').load("apps/Process/Combine/sourcemodcombinehold.php?reload=1&lot="+lotno);
	}

	function editcombinehold(lotno){
		swal({
			  title: "Are You Sure Last Lot?",
			  text : "Delete Combine Hold and This Last Lot",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			}).then((willSave) => {
				if (willSave) {
					$("#action_mod").val("editcombinehold");
					var data = $('.form-user').serialize();
					$.ajax({
							type: 'POST',
							url:  "apps/Process/Combine/simpan.php",
							data: data,
								success: function() {
									swall('saved','','success',2000);
									$('#mastercombinetable').load("apps/Process/Combine/sourcemodcombineholdmaster.php?reload=1&lot="+lotno);
									$('#tampilcombinehold').load("apps/Process/Combine/sourcemodcombinehold.php?reload=2");
									$('#action_mod').val('');							
								}
					});
				}
			});
	}

	function check (id){
		if(id.checked){
			$('#save').hide();
		} else {
			$('#save').show();
		}
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