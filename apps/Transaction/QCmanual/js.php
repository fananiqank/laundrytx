<script>

$("#scanner").keyup(function(event){
    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
        $('#inputtype').click(); 
    } });

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
						url:  "apps/Transaction/QCmanual/simpan.php",
						data: data,
						success: function() {
							$('#confirm').val('');
							swal("Saved!", {
						      icon: "success",
						    });
							$('#tampcontent').load("apps/Transaction/QCmanual/tampilcontent.php?lot="+lot+"&reload=1");
							$('#cart').load("apps/Transaction/QCmanual/cartqc.php?type="+type);
						}
				});
			}
		});	
	}
	
}

function hapusqckeranjang(lot,type_qc){
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
						url:  "apps/Transaction/QCmanual/simpan.php?lot="+lot+"&type_qc="+type_qc,
						data: data,
						success: function() {
							$('#confirm').val('');
							swall("Deleted","","success",2000);
							$('#tampilmodcart').load("apps/Transaction/QCmanual/sourcedatamodqc.php?lot="+lot+"&type_qc="+type_qc);
							$('#sumbit').hide();
							$('#tampcontent').load("apps/Transaction/QCmanual/tampilcontent.php?lot="+lot+"&reload=1");
							$('#cart').load("apps/Transaction/QCmanual/cartqc.php?type="+type_qc);
						}
				});
			}
		});	
}

//content scan pro
	function onEnterUser(event,val){
	 	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
		        $('#lot_number').focus(); 
		} 
	}

	function onEnterLot(event,val){
	 	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
		        $('#tampcontent').load('apps/Transaction/QCmanual/tampilcontent.php?lot='+val); 
		} 
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
								url:  "apps/Transaction/QCmanual/simpan.php",
								data: data,
								success: function(lot) {
									$('#conf').val('');
									explot = lot.split('|'); //explode all lot
									thislot = explot[0];
									
									if (explot[1] != 'none'){
										lotscrap = explot[1].split("_"); //lot scrap
										lotscrapshow = lotscrap[0];
										lotscrapprint = lotscrap[1];

									} 
									if (explot[2] != 'none'){
										lotrework = explot[2].split("_"); //lot rework
										lotreworkshow = lotrework[0];
										lotreworkprint = lotrework[1];
									}
									swal({
										  icon: 'success',
										  title: 'Lot No : '+thislot,
										  text: 'Saved',
										  footer: '<a href>Why do I have this issue?</a>'
									}).then((willoke) => {
										if (lotscrapprint != '' && lotreworkprint == ''){
											javascript: window.open("lib/pdf-qrcode.php?c="+lotscrapprint, "_blank", "width=700,height=450");
										} else if (lotscrapprint != '' && lotreworkprint == ''){
											javascript: window.open("lib/pdf-qrcode.php?c="+lotreworkprint, "_blank", "width=700,height=450");
										} else if (lotscrapprint != '' && lotreworkprint != ''){
											javascript: window.open("lib/pdf-qrcode.php?c="+lotscrapprint, "_blank", "width=700,height=450");
											javascript: window.open("lib/pdf-qrcode.php?c="+lotreworkprint, "_blank", "width=700,height=450");
										}
										window.location.reload();
									});
								}
						});
				}
			});
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