<script>
	$('#usercode').focus();
	$("#usercode").keyup(function(event){
    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
       $("#lot_no").focus();
    } });
    
	$("#lot_no").keyup(function(event){
    if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
        var lot = $("#lot_no").val();
        var user = $("#userid").val();
        var usercode = $("#usercode").val();
        var master_type_process_id = $("#mastertypeid").val();
        cekuser(lot,user,usercode,master_type_process_id);
    } });

	function cekuser(lot,user,usercode,master_type_process_id){
		var explot = lot.split('-');
		var lotres = explot[0].substr(-4,1);
		if (lot == ''){
			swall('Lot Number Empty','Lot Number Must be Filled','error',1000);
			document.getElementById('lot_no').focus();
		} else if (usercode == ''){
			swall('User Empty','User Must be Filled','error',1000);
			document.getElementById('lot_no').focus();
		} else {
			//alert(lotres);	
			$('#tampilcmt').load("apps/Process/Split/tampilcmt.php?lot="+lot+"&user="+user+"&typelot="+lotres);	
		
		}
		
	}

	function cekpoint(val){
		if(parseInt(val) < 2){
			swall('Min. 2 Lot','min. split for 2 Lot number','warning',2000);	
			$('#split_no').val(2);
		}
	}

	function tampilsplit(id,val,type){
		$('#tampilsplit').load("apps/Process/Split/tampilsplit.php?id="+id+"&val="+val+"&type="+type);
		//reloadCreatelotsplit(val);
	}

	function cekqty(){
		var jumlahsplit = $('#val').val();
		var jumlahqty = $('#qtydb').val();
		var sum = 0;
		for (j=1;j<=jumlahsplit;j++){
			if ($('#qtysplit_'+j).val() == ''){
				var qty = "0";
			} else { 	
				var qty = $('#qtysplit_'+j).val();
			}

			sum += parseInt(qty);
			if (parseInt(sum) > parseInt(jumlahqty)){
				swall('Over Qty','Qty Split Over than Qty Lot','error',3000);
				for (k=1;k<=jumlahsplit;k++){
					$('#qtysplit_'+k).val('0');
				}
				$('#totalsplit').val('0');
			} else {
				$('#totalsplit').val(sum);
			}
		}

	}

	function cekkg(){
		var jumlahsplit = $('#val').val();
		//var jumlahqty = $('#qtydb').val();
		var sam = 0;
		for (j=1;j<=jumlahsplit;j++){
			if ($('#kg_'+j).val() == ''){
				var kg = "0";
			} else { 	
				var kg = $('#kg_'+j).val();
			}
			sam += parseInt(kg);
			$('#totalkg').val(sam);
			
		}

	}

	function savesplit(){

		if (parseInt($('#totalsplit').val()) < parseInt($('#qtydb').val())){
			swall('Qty Not Balance','Qty Split Less than Qty Lot','error',2000);
		} else {
			swal({
				  title: "Are You Sure Split Lot?",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willSave) => {
				  if (willSave) {
					var data = $('.form-user').serialize();
					$.ajax({
							type: 'POST',
							url:  "apps/Process/Split/simpan.php",
							data: data,
							success: function(lot) {
								explot = lot.split("TN");
								lotno = explot[0].split("|");
								totalno = explot[1];
									swal({
										  icon: 'success',
										  title: 'Print Split Lot',
										  text: 'Saved',
										  footer: '<a href>Why do I have this issue?</a>'
									}).then((willoke) => {
										for(i=0;i<totalno;i++){
											javascript: window.open("lib/pdf-qrcode.php?c="+lotno[i], "_blank", "width=700,height=450");
										}
										window.location.reload();	
									});
								
																							
						}
					});
				  }
				});
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
				swall('Qty Lot Over','Qty Lot More than Sisa Qty Lot','info',2000);				
				$('#pcs').val('');
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