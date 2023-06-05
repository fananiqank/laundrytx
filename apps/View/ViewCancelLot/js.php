<script>
cm = $('#nocmt').val();
co = $('#nocolor').val();
xty = $('#exftydate').val();

trimcmt = cm.trim();
expcmt = trimcmt.split('/');
cmts = expcmt.join('-');

trimcolor = co.trim();
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
			 			expcolor5 = expcolor4.join('G-');  
			 		} else {
			 			expcolor5 = expcolor3;
			 		}
expcolor6 = expcolor5.split('&');
 		if (expcolor6.length > 1){
 			color = expcolor6.join('Y|');  
 		} else {
 			color = expcolor5;
 		}
	 		
// (function( $ ) {

// 	'use strict';

// 	var datatableInit = function() {
// 		var $table = $('#datatable-ajax');
// 		$table.dataTable({
// 			"processing": true,
//          	"serverSide": true,
//         	"ajax": "apps/View/ViewCancelLot/data.php?cm="+cmts+"&co="+color+"&xty="+xty,
//         	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
// 			   var index = iDisplayIndex +1;
// 			   $('td:eq(0)',nRow).html(index);
// 			   return nRow;
// 			},
// 			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
// 			"order": [ 0, 'desc' ],
//         });

// 	};
	
// 	$(function() {
// 		datatableInit();
// 	});

//  }).apply( this, [ jQuery ]);
	$(document).ready(function(){
       // tampil_data_barang();   //pemanggilan fungsi tampil barang.
        var spintable = $('#datatable-ajax').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": "apps/View/ViewCancelLot/data.php?cm="+cmts+"&co="+color+"&xty="+xty,
				"lengthMenu": [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"]
				],
				"order": [ 0, 'desc' ]
			});
      //  testrecursive();
     //    function testrecursive(){
    	// 	setTimeout(() => {  spintable.ajax.reload(); testrecursive(); console.log("World!"); }, 10000);    
    	// }

	});

 	$(function() {  
		    	//$('#nocmt').focus();
		        $("#nocmt").autocomplete({  
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/View/ViewCancelLot/sourcedata.php",
			                { d:'1', term:request.term}, 
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
			                "apps/View/ViewCancelLot/sourcedata.php",
			                { d:'2', term:request.term, scmt:$('#nocmt').val(), xty:$('#exftydate').val()}, 
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
		    	
		        $("#exftydate").autocomplete({
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/View/ViewCancelLot/sourcedata.php",
			                { d:'3', term:request.term, scmt:$('#nocmt').val(), scolor:$('#nocolor').val()}, 
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

	function pindahData2(cmt,colors,exftydate){

	 	//CMT
	 	if (cmt == '') {
	 		swal({
				icon: 'error',
				title: 'WO No Not Found',
				text: 'Please Fill Wo No Number',
				footer: '<a href>Why do I have this issue?</a>'
			});
	 	} else if (colors == '') {
	 		swal({
				icon: 'error',
				title: 'Colors Not Found',
				text: 'Please Fill Colors',
				footer: '<a href>Why do I have this issue?</a>'
			});
	 	} else if (exftydate == '') {
	 		swal({
				icon: 'error',
				title: 'Ex Fty Date Not Found',
				text: 'Please Fill Ex Fty Date',
				footer: '<a href>Why do I have this issue?</a>'
			});
	 	} else {
	 		trimcmt = cmt.trim();
			expcmt = trimcmt.split('/');
	 		cmts = expcmt.join('-');
		 	//Color
	 		trimcolor = colors.trim();
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
			 			expcolor5 = expcolor4.join('G-');  
			 		} else {
			 			expcolor5 = expcolor3;
			 		}
	 		expcolor6 = expcolor5.split('&');
			 		if (expcolor6.length > 1){
			 			color = expcolor6.join('Y|');  
			 		} else {
			 			color = expcolor5;
			 		}

	 		 var hal = $('#getp').val();
			 window.location="content.php?"+hal+"&cm="+cmts+"&co="+color+"&xty="+exftydate;
		}
	}

	function cancelprocess (lot){
		if($('#usercode').val() == ''){
			swal({
				  icon: 'error',
				  title: 'Please Fill User!',
				  timer: '3000'
			});
			$("#usercode").focus();
		} else {
			var data = $('.form-user').serialize();
			$.ajax({
				type: 'POST',
				url:  "apps/View/ViewRec/simpan.php",
				data: data,
				success: function(lot) {
					swal({
						  icon: 'Warning',
						  title: 'Are You Sure?',
						  text: 'Cancel Lot',
						  footer: '<a href>Why do I have this issue?</a>'
					}).then((willoke) => {
						window.location.reload();
					});
				} 
					
			});
		}
	}
</script>