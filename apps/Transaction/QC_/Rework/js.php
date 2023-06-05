<script>

	(function( $ ) {

	'use strict';

		var datatableInit = function() {
			var $table = $('#datatable-ajax2');
			$table.dataTable({
				"processing": true,
	         	"serverSide": true,
	        	"ajax": "apps/Transaction/Rework/data.php",
	        	"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	        	"order": [ 0, 'desc' ],
   			// "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			//    var index = iDisplayIndex +1;
			//    $('td:eq(0)',nRow).html(index);
			//    return nRow;
			// } 
        	});
		};
	
		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);


	function pindahData2(cmt,color,exdate,typelot,shade){
	
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
	 		swall("WO No Empty","Please Fill WO No","error",3000);
	 	}	
	 	else if (color == ''){
	 		swall("Colors Empty","Please Fill Colors","error",3000);
	 	}	
	 	else if (exdate == ''){
	 		swall("Ex Fty Date Empty","Please Fill Ex Fty Date","error",3000);
	 	}	
	 	else if (shade == ''){
	 		swall("Shade Empty","Please Choose Shade Type","error",3000);
	 	} 	
	 	else {
	 		var hal = $('#hal').val();
			$('#loader-on').click();
			$('#cmtnumb').show();
			$('#tampilwip').load("apps/Transaction/Rework/tampilwip.php?cm="+cmt+"&co="+color+"&shade="+shade+"&exdate="+exdate+"&typelot="+typelot);
			$('#no_cmt').val(cmt);
			$('#no_color').val(color);
			$('#ex_fty_date').val(exdate);
			$('#typelot').val(typelot);
			$('#shade').val(shade);
			$('#loader-off').click();
		}
	}

	$(function() {  
		$("#cmt_no").autocomplete({
		 	source: function(request, response) {
			    $.getJSON(
			        "apps/Transaction/Rework/sourcedata.php",
			        { d:'2', term:request.term}, 
			        response
			    );
			},
		    minLength:2, 
		    select: function (event, ui) {
		    	if (ui.item != undefined) {
		        	$(this).val(ui.item.value);	
		    	} 
		    	return false;
			}
		});
	});
	
	$(function() {  
		$("#color_no").autocomplete({
		   //source: "apps/Transaction/Sequence/sourcedata.php?d=4&color="+$('#buyer_no').val(),  
		    source: function(request, response) {
			    $.getJSON(
			        "apps/Transaction/Rework/sourcedata.php",
			        { d:'4', term:request.term, sdenim: $('#denim_no').val(), sbuyer:$('#buyer_no').val(), scmt:$('#cmt_no').val(), sstyle:$('#style_no').val(),sstatus:$('#status_seq').val()}, 
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

	$(function() {  
		        $("#ex_fty_date").autocomplete({
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Transaction/Rework/sourcedata.php",
			                { d:'5', term:request.term, scmt:$('#cmt_no').val(), scolor:$('#color_no').val(), ssiz:$('#nosizes').val(), sins:$('#noinseams').val() }, 
			                response
			            );
			        },  
		           	minLength:0, 
		           	select: function (event, ui) {
		           	if (ui.item != undefined) {
		                $(this).val(ui.item.value);	
				        //$( "#idcolor" ).val( ui.item.id );
				        $( "#ex_fty_date_asli" ).val( ui.item.exftydateasli );
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
		if($('#ceklot').val() == 1){
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
								if (ui == 1){
								//	window.location.reload();
								} else {
									swall("Can not Save","Check again your Lot Rework and Sequence Process","error",3000);
								}
							}
						});
					}
				});
		} else {
			swall("Choose Lot Rework","Please Choose Lot Rework","warning",2000);
		} 
	}

	function viewdata(a){
		$('#view').val(a);
		if(a == 'v') {
			javascript: window.location.href="content.php?option=Transaction&task=rework"+"_"+a+"&act=ugr_transaction";
		} else {
			javascript: window.location.href="content.php?option=Transaction&task=rework"+"&act=ugr_transaction";
		}
		
	}

	function swall (title,text, icon, timer){
		swal({
				title: title,
				text: text,
				icon: icon,
				time: timer,
			});
	}
</script>