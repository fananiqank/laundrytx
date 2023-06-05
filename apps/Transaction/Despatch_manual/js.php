<script>
	$("#lot_no").keyup(function(event){
    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
        var lot = $("#lot_no").val();
        var user = $("#userid").val();
        var master_type_process_id = $("#mastertypeid").val();
        cekuser(lot,user,master_type_process_id);
    } });

	function cekuser(lot,user,master_type_process_id){
		var explot = lot.split('-');
		var lotres = explot[0].substr(-4,1);
		//alert(lotres);
		if (lot == ''){
			swall('Lot Number Empty','Lot Number Must be Filled','error', 2000);
		} else {
		//alert(lotres);	
		$('#tampildespatch').load("apps/Transaction/Despatch/sourcedatadespatch.php?lot="+lot+"&user="+user+"&typelot="+lotres);
		$('#cart').load("apps/Transaction/Despatch/cart.php?lot="+lot);
		}
	}

	function viewdata(a){
		if(a == 'v') {
			javascript: window.location.href="content.php?option=View&task=despatch&act=ugr_view";
		} else {
			javascript: window.location.href="content.php?option=Transaction&task=despatch&act=ugr_transaction";
		}
		
	}
	
	function onEnterUser(event,val){
	 	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
		        $('#lot_no').focus(); 
		} 
	}

	function onEnterinseams(event,val){
	 	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
		        $('#sizes').focus(); 
		} 
	}

	function onEntersizes(event,val){
	 	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
		        $('#qty_det').focus(); 
		} 
	}

	
	function conf(a){
		if ($('#inseams').val() == '' || $('#sizes').val() == '' || $('#qty_det').val() == '') {
			swall('Data Not Complete','Check Inseams / Sizes / Qty Detail','info','3000');
		} else {
			$('#conf').val(9);
			var out = parseInt($('#qty_all').val())-parseInt($('#qty_det').val());
			var content = document.createElement('div');
	    	content.innerHTML = '<strong>Qty Good          : <strong>'+$('#qty_det').val()+'<strong> | '+
	    						'\nQty Balance: '+out;					
			swal({
				  title: "Are You Sure?",
				  content: content,
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    swal("Saved!", {
				      icon: "success",
				    });
				    lot = $('#lotno').val();
				 	cmts = $('#no_cmt').val();
				 	inseams = $('#no_inseams').val();
				 	sizes = $('#no_sizes').val();
				 	saw = $('#saw').val();
					var data = $('.form-user').serialize();
						$.ajax({
								type: 'POST',
								url:  "apps/Transaction/Despatch/simpan.php?id="+a,
								data: data,
								success: function() {
									$('#loading-on').click();
									//$('#datatable-ajax').DataTable().ajax.reload();;
									$('#tampildespatch').load("apps/Transaction/Despatch/sourcedatadespatch.php?lot="+lot);
									$('#cekwip').load("apps/Transaction/Despatch/cekwip.php?lot="+lot);
									$('#cart').load("apps/Transaction/Despatch/cart.php?lot="+lot);
									$('#conf').val('');
									$('#loading-off2').click();
								}
						});
				  } 
				});
    	}
	}

	function hapuscart (a,lot){
			$('#hpsmodcart').val("hpscart");
			var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/Despatch/simpan.php?id="+a,
						data: data,
						success: function() {
							$('#tampilmodcart').load("apps/Transaction/Despatch/sourcedatamodcart.php?lot="+lot+"&reload=1");
							$('#tampildespatch').load("apps/Transaction/Despatch/sourcedatadespatch.php?lot="+lot+"&reload=1");
							$('#cart').load("apps/Transaction/Despatch/cart.php?lot="+lot);
							$('#hpsmodcart').val('');
							$('#cekwip').load("apps/Transaction/Despatch/cekwip.php?lot="+lot+"&reload=1");
						}
					});
	}

	function savedespatch(idlogin){
		swal({
				title: "Are You Sure?",
				content: content,
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
			if (willDelete) {
				    
				$('#confmodcart').val('simpan');
				var data = $('.form-user').serialize();
				$.ajax({
						type: 'POST',
						url:  "apps/Transaction/Despatch/simpan.php?idlog="+idlogin,
						data: data,
						success: function() {
							swal("Saved!", {
						      icon: "success",
						    });
							javascript: window.location.href="content.php?p="+$('#getpmodcart').val();
						}
				});
			} 
		});
	}
	
	$(function() {  
		    	//$('#nocmt').focus();
		        $("#nocmt").autocomplete({  
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Transaction/Despatch/sourcedata.php",
			                { d:'1', term:request.term, scolor:$('#nocolor').val(), sins:$('#noinseams').val(), ssiz:$('#nosizes').val() }, 
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
			                "apps/Transaction/Despatch/sourcedata.php",
			                { d:'2', term:request.term, scmt:$('#nocmt').val(), sins:$('#noinseams').val(), ssiz:$('#nosizes').val() }, 
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
		    	
		        $("#noinseams").autocomplete({
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Transaction/Despatch/sourcedata.php",
			                { d:'3', term:request.term, scmt:$('#nocmt').val(), scolor:$('#nocolor').val(), ssiz:$('#nosizes').val() }, 
			                response
			            );
			        }, 
		           	minLength:0, 
		           	select: function (event, ui) {
		           	//alert($('#idcmt').val());
		            if (ui.item != undefined) {
		            	//alert('as');
		            	//alert(ui.item.id_value);
		                $(this).val(ui.item.value);	
		                //$('#selected_id').val(ui.item.id);
				        $( "#idcolor" ).val( ui.item.id );
				       	//depar(ui.item.id_departemen);
		            	} 
		            	return false;
	        		}
		        }).focus(function () {
				    $(this).autocomplete("search", "");
			});     
		        
	});

	$(function() {  
		    	
		        $("#nosizes").autocomplete({
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Transaction/Despatch/sourcedata.php",
			                { d:'4', term:request.term, scmt:$('#nocmt').val(), sins:$('#noinseams').val(), scolor:$('#nocolor').val() }, 
			                response
			            );
			        }, 
		           	minLength:0, 
		           	select: function (event, ui) {
		           	//alert($('#idcmt').val());
		            if (ui.item != undefined) {
		            	//alert('as');
		            	//alert(ui.item.id_value);
		                $(this).val(ui.item.value);	
		                //$('#selected_id').val(ui.item.id);
				        $( "#idcolor" ).val( ui.item.id );
				       	//depar(ui.item.id_departemen);
		            	} 
		            	return false;
	        		}
		        }).focus(function () {
				    $(this).autocomplete("search", "");
			});     
		        
	});

	$(function() {  
		    	var will = $('#willrec_'+id).val();
				var cut = $('#cutqty_'+id).val();
				var jum = parseInt(cut)-parseInt(cut);
		        $('#idcek').val(1);
	});

	
	function cekwip(id){
    	// Check
    	var jm = $('#jmlwip').val();
    	for(i=1;i<=jm;i++){
    		//alert(id.checked);
	    	if(id.checked){
		    	$("#cmtno_"+i).prop("checked", true);
		    } else {
		    	$("#cmtno_"+i).prop("checked", false);
		    }
		}
	}
	   

	function cekwo(id){
	    var jm = $('#jmlwo').val();
    	for(i=1;i<=jm;i++){
    		//alert(id.checked);
	    	if(id.checked){
		    	$("#wono_"+i).prop("checked", true);
		    } else {
		    	$("#wono_"+i).prop("checked", false);
		    }
		}
	   
	}
	function cekapp(id){
	    var jm = $('#jmlappwo').val();
    	for(i=1;i<=jm;i++){
    		//alert(id.checked);
	    	if(id.checked){
		    	$("#appwo_"+i).prop("checked", true);
		    	$("#appwo2_"+i).val(1);
		    } else {
		    	$("#appwo_"+i).prop("checked", false);
		    	$("#appwo2_"+i).val(0);
		    }
		}
	   
	}

	function cekapp2(a,b){
		if(a.checked){
		  	$("#appwo2_"+b).val(1);
		} else {
			$("#appwo2_"+b).val(0);
		}
	}
	function savewo(a){
		$('#cmtwo').val(a);
		var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Transaction/sequence/simpan.php",
					data: data,
					success: function() {
						$('#tampilwo').load("apps/Transaction/sequence/tampilwo.php");
						$('#tampilwip').load("apps/Transaction/sequence/tampilwip.php?b="+$('#no_buyer').val()+"&s="+$('#no_style').val()+"&cm="+$('#no_cmt').val()+"&co="+$('#no_color').val()+"&tgl="+$('#tanggal1').val()+"&tgl2="+$('#tanggal2').val());
						$('#checkwip').prop("checked", false);
						$('#checkwo').prop("checked", false);
						$('#tampilapp').load("apps/Transaction/sequence/tampilapp.php?b="+$('#no_buyer').val()+"&s="+$('#no_style').val()+"&cm="+$('#no_cmt').val()+"&co="+$('#no_color').val()+"&tgl="+$('#tanggal1').val()+"&tgl2="+$('#tanggal2').val());
					}
				});
	}
	function hapusker(a){
		$('#cmtwo').val(a);
		var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Transaction/sequence/simpan.php",
					data: data,
					success: function() {
						$('#tampilwo').load("apps/Transaction/sequence/tampilwo.php");
						$('#tampilwip').load("apps/Transaction/sequence/tampilwip.php?b="+$('#no_buyer').val()+"&s="+$('#no_style').val()+"&cm="+$('#no_cmt').val()+"&co="+$('#no_color').val()+"&tgl="+$('#tanggal1').val()+"&tgl2="+$('#tanggal2').val());
						$('#tampilapp').load("apps/Transaction/sequence/tampilapp.php");
						$('#checkwip').prop("checked", false);
						$('#checkwo').prop("checked", false);
					}
				});
	}
	function hapusproses(a,b,c){
		$('#cmtwo2').val(3);
		var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Transaction/sequence/simpan.php?d="+a+"&e="+b+"&f="+c,
					data: data,
					success: function() {
						$('#tampildata').load("apps/Transaction/sequence/tampil.php");
							$('#turun').val('');
							$('#idnya').val('');
					}
				});
	}
	
	function hapus(id){
		$('#id').val(id);
		$('#aksi').val('hapus');
		javascript: document.getElementById('form_index').submit();
		
	}

	function hitungcut(val,id){
		$('#cutqty_'+id).val();
		$('#willrec_'+id).val();
		var jumbalance = parseInt($('#willrec_'+id).val())-parseInt($('#cutqty_'+id).val());
		$('#balance_'+id).val(jumbalance);
	}
	
	function hitungin(qty_det,qty_all){
		//alert($('#qtysend_'+id).val());
		if (qty_det > qty_all){
			swal({
				  icon: 'error',
				  title: 'Qty Over',
				  text: 'Qty Detail More than Qty All',
				  timer: '2000',
				});
			$('#qty_det').val('');
		} 

	}

	function tooltip(val,id){
		
		var will = $('#willrec_'+id).val();
		var cut = $('#cutqty_'+id).val();
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
				$('#lastrec_'+id).prop("checked", false);
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
			} else {
				$('#sumbit').show();
				$('#idlast').val(0);
			}
		} else {
			$('#idlast').val(0);
		}
		//javascript: document.getElementById('form_index').submit();
		//javascript: window.location.href="index.php?x=berita_e&id="+id;
		//alert(id);
		
	}

	
	function call(id){
		var expl=id.split('_');
		call2(expl[1])
	}
	function call2(st){
		//alert(st);
		if(st==1){
			$('#st').show();	
		}
		if(st==0){
			$('#st').hide();	
		}
	}
	function validate_frm()
		{
			
		try{
			x = document.formku;
			
			
			if (x.password2.value != x.password.value)
			{
				alert('Password Tidak sama!');
				x.password2.value='';
				x.password2.focus();
				return(false);
			}
			return(true);
			}catch(e){
				alert('Error '+ e.description);
			}
		}


	function saveedit() {
		$('#codeproc').val("edit");
	}
	function hapusroledtl (a,b,c,d){
			$('#hpsdtl').val(a);
			$('#codeproc').val("hapusdtl");
			var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/sequence/simpan.php?id="+b+"&seq="+d,
						data: data,
						success: function() {
							$('#tampildata').load("apps/Transaction/sequence/tampil.php");
							$('#proc').load("apps/Transaction/sequence/sourceprocessedit2.php?id="+b+"&j="+c+"&seq="+d);
								$('#hpsdtl').val('');
								//$('#idnya').val('');
								$('#codeproc').val("");
						}
					});
	}
	
	

</script>