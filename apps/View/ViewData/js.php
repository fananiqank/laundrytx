<script>
//alert("jooosss");
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
	//         	"ajax": "apps/View/ViewData/data.php?cm="+cmts+"&co="+color+"&xty="+xty+"&saw="+saw,
	//         	"fnRowCallback": function( nRow, aData, iDisplayIndex) {
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
					"ajax": "apps/View/ViewData/data.php?cm="+cmts+"&co="+color+"&xty="+xty+"&saw="+saw,
					"fnRowCallback": function(nRow, aData, iDisplayIndex) {
						var index = iDisplayIndex + 1;
						$('td:eq(0)', nRow).html(index);
						return nRow;
					},
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
	
	function pindahData2(cmt,colors,exftydate,saw){

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
		 	//CMT
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
	
	
	$(function() {  
		$("#nocmt").autocomplete({
		 	source: function(request, response) {
			    $.getJSON(
			        "apps/View/ViewData/sourcedata.php",
			        { d:'1', term:request.term}, 
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
		$("#nocolor").autocomplete({
		   //source: "apps/Transaction/Sequence/sourcedata.php?d=4&color="+$('#buyer_no').val(),  
		    source: function(request, response) {
			    $.getJSON(
			        "apps/View/ViewData/sourcedata.php",
			        { d:'2', term:request.term, scmt:$('#nocmt').val()}, 
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
		    	
		        $("#exftydate").autocomplete({
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/View/ViewData/sourcedata.php",
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

	function detail(id){
		$('#halcontent').html('<img src="./assets/images/spinner.gif">');
    	$('#halcontent').load("apps/View/ViewData/view_detail.php?id="+id);
    	$('#back').load("apps/View/ViewData/back.php?id="+id);
    	$('#demo').hide();
    	$('#view').val('v');
    	$('#halreload').val('0');
    
	}

	function back(id){
		//$('#datatable-ajax').DataTable().ajax.reload();
		$('#halcontent').load("apps/View/ViewData/view_data.php?halreload=1");
		$('#halreload').val('1');
		$('#back').load("apps/View/ViewData/back.php?id=&halreload=1&cm="+cmts+"&co="+color+"&xty="+xty);
		    	//window.location.reload();
	}
	   
	function tooltip(id){
		var will = $('#willrec_'+id).val();
		var cut = $('#cutqty_'+id).val();
		var jum = parseInt(cut)-parseInt(cut);
	
		if (parseInt(will) > parseInt(cut)){
			swal({
			  icon: 'error',
			  title: 'Can not Submit!!',
			  text: 'Receive Qty More than Cutting Qty',
			  footer: '<a href>Why do I have this issue?</a>'
			});
			$('#sumbit').hide();
			$('#lastrec_'+id).prop("checked", false);
			$('#idcek').val(2);
		} else if(parseInt(will) < parseInt(cut)) {
			swal({
			  icon: 'info',
			  title: 'Fill Note',
			  text: 'Receive Qty Less than Cutting Qty',
			  footer: '<a href>Why do I have this issue?</a>'
			});
			$('#sumbit').show();
			$('#idcek').val(1);
		} else {
			$('#sumbit').show();
			$('#idcek').val(0);
		}
		
	}

	function cekdetail(id,lot,type){
		$('#detail').html('<img src="./assets/images/spinner.gif">');
		$('#detail').load("apps/View/ViewData/view_detail_lot.php?id="+id+"&lot="+lot+"&type="+type);
	}

	function caridata(cari,id){
		if (cari == ''){
			cari = "Z";
		} 
		$('#isidetail').load("apps/View/ViewData/view_detail_isi.php?id="+id+"&c="+cari);
	}

	function caridatalot(cari){
		if (cari == ''){
			cari = "0";
		} 
		var lot = $('#lotno').val();
		var type = $('#type').val();
		$('#isidetaillot').load("apps/View/ViewData/view_detail_lot_isi.php?id="+$('#proc_id').val()+"&lot="+lot+"&type="+type+"&womasterprocid="+$('#womasterprocid').val()+"&c="+cari);

	}
	
</script>