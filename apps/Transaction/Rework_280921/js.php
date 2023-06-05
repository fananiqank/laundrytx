<script>

	function pindahData2(cmt,color,exdate,typelot,usercode){
		//tgl ex fty date
		// if (tgl != ''){
 	// 		var tga = tgl.split('/');
 	// 		var istga = tga[2]+"-"+tga[0]+"-"+tga[1];
 	// 	} else {
 	// 		var istga = 'A';
 	// 	}

 	// 	if (tgl2 != ''){
 	// 		var tgb = tgl2.split('/');
 	// 		var istgb = tgb[2]+"-"+tgb[0]+"-"+tgb[1];
 	// 	} else {
 	// 		var istgb = 'A';
 	// 	}


	 	//CMT
 		trimcmt = cmt.trim();
		expcmt = trimcmt.split('/');
 		cmt = expcmt.join('-');

	 	//Color
 		trimcolor = color.trim();
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
	 			color = expcolor4.join('G-');  
	 		} else {
	 			color = expcolor3;
	 		}
	 		
	 	if (cmt == ''){
	 		swal({
				title: "WO No Empty",
				text: "Please Fill WO No",
				icon: "error",
				timer: 3000,
			})
	 	}	
	 	else if (color == ''){
	 		swal({
				title: "Colors Empty",
				text: "Please Fill Colors",
				icon: "error",
				timer: 3000,
			})
	 	}	
	 	else if (exdate == ''){
	 		swal({
				title: "Ex Fty Date Empty",
				text: "Please Fill Ex Fty Date",
				icon: "error",
				timer: 3000,
			})
	 	}	
	 	else if (usercode == ''){
	 		swal({
				title: "User Empty",
				text: "Please Input User",
				icon: "error",
				timer: 3000,
			})
	 	} 	

	 	else {
	 		var hal = $('#hal').val();
			$('#loader-on').click();
			$('#cmtnumb').show();
			$('#tampilwip').load("apps/Transaction/Rework/tampilwip.php?cm="+cmt+"&co="+color+"&exdate="+exdate+"&typelot="+typelot+"&usercode="+usercode);
			$('#wo_no').val(cmt);
			$('#color_no').val(color);
			$('#ex_fty_date').val(exdate);
			$('#typelot').val(typelot);
			$('#usercode').val(usercode);
			$('#loader-off').click();
		}
	}

	$(function() {  
		$("#wo_no_show").autocomplete({
		 	source: function(request, response) {
			    $.getJSON(
			        "apps/Transaction/Rework/sourcedata.php",
			        { d:'1', term:request.term}, 
			        response
			    );
			},
		    minLength:2, 
		    select: function (event, ui) {
		    	if (ui.item != undefined) {
		        	$(this).val(ui.item.value);	
		        	$('#wo_no').val(ui.item.woasli);
		    	} 
		    	return false;
			}
		});
	});
	
	$(function() {  
		$("#color_no_show").autocomplete({
		   //source: "apps/Transaction/Sequence/sourcedata.php?d=4&color="+$('#buyer_no').val(),  
		   source: function(request, response) {
			    $.getJSON(
			        "apps/Transaction/Rework/sourcedata.php",
			        { d:'4', term:request.term, scmt:$('#wo_no_show').val()}, 
			        response
			    );
			},
		    minLength:0, 
		    select: function (event, ui) {
		    	if (ui.item != undefined) {
		        	$(this).val(ui.item.value);	
		        	$('#color_no').val(ui.item.colorasli);
		    	} 
		    	return false;
			}
		}).focus(function () {
		   $(this).autocomplete("search", "");
		});        
	});

	$(function() {  
		        $("#ex_fty_date").autocomplete({
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Transaction/Rework/sourcedata.php",
			                { d:'5', term:request.term, scmt:$('#wo_no_show').val(), scolor:$('#color_no_show').val()}, 
			                response
			            );
			        }, 
		           	minLength:0, 
		           	select: function (event, ui) {
		            if (ui.item != undefined) {
		                $(this).val(ui.item.value);	
		                $('#ex_fty_date_asli').val(ui.item.exftydateasli);	
		            	} 
		            	return false;
	        		}
		        }).focus(function () {
				    $(this).autocomplete("search", "");
			});        
	});


	function cekapp2(a,b){
		if(a.checked){
		  	$("#lotapp_"+b).val(1);
		} else {
			$("#lotapp_"+b).val(0);
		}
	}

	function cekwip(id){
    	// Check
    	var jm = $('#jmlwip').val();
    	for(i=1;i<=jm;i++){
	    	if(id.checked){
		    	$("#lotid_"+i).prop("checked", true);
		    	$("#totalpcs").val($("#jmlqty").val());
		    } else {
		    	$("#lotid_"+i).prop("checked", false);
		    	$("#totalpcs").val(0);
		    }
		
		}
	}

	function cekqty(cek){
    	// Check
    	$("#checkwip").prop("checked", false);
    	var jmwo = $('#jmlwip').val();
    	var sum = 0;
    	var sem = $(":checkbox:checked").length;

    	// total yang di centang
    	for (j=1; j <= sem; j++){    	
			var qty = $('#lotqty_'+j).val();
			sum += parseInt(qty);
		}
	
    	$('#totalpcs').val(sum);
	}

	function viewdata(a){
		if (a == 'a'){
			javascript: window.location.href="content.php?p="+$('#getpage').val();
		} else {
		//$('#tampilcontent').load('apps/Transaction/Rework/view_lot.php');
			javascript: window.location.href="content.php?p="+$('#getpage').val()+"_"+a;
		}
	}
	
	function seqsubmit(a,cmt,color,exdate) {
		//CMT
 		trimcmt = cmt.trim();
		expcmt = trimcmt.split('/');
 		cmt = expcmt.join('-');

	 	//Color
 		trimcolor = color.trim();
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
	 			color = expcolor4.join('G-');  
	 		} else {
	 			color = expcolor3;
	 		}
	 		
		if (a != '') {
			$('#lotnoshow').load("apps/Transaction/Rework/lotnumber_show.php?cmt="+cmt+"&exdate="+exdate+"&color="+color);
			$('#simpan_all').show();
		} else {
			$('#simpan_all').hide();
		}


	}

	function simpan(){
		
		if($('#ceklot').val() == 1 && $('#totalpcs').val() != ''){
			swal({
				  title: "Are you sure?",
				  text: "Save This Data",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true
				}).then((willSave) => {
					if (willSave) {
						var data = $('.form-user').serialize();
						$.ajax({
							type: 'POST',
							url:  "apps/Transaction/Rework/simpan.php",
							data: data,
							success: function(ui) {
								explot = ui.split('_');
								swal({
									  icon: 'success',
									  title: 'Lot No : '+explot[0],
									  text: 'Saved',
									  footer: '<a href>Why do I have this issue?</a>'
								}).then((willoke) => {
									javascript: window.open("lib/pdf-qrcode.php?c="+explot[1], "_blank", "width=700,height=450");
									window.location.reload();
								});
							}
						});
					}
				});
		} else {
			swal({
				  title: "Choose Lot Rework",
				  text: "Please Choose Lot Rework",
				  icon: "warning",
				  timer: 2000,
				})
		} 
	}
</script>