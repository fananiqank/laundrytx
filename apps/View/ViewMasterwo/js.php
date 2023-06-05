<script>

	(function( $ ) {

	'use strict';

		var datatableInit = function() {
			var $table = $('#datatable-ajax');
			$table.dataTable({
				"processing": true,
	         	"serverSide": true,
	        	"ajax": "apps/View/ViewMasterwo/data.php",
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


	function pindahData2(denim,buyer,style,cmt,color,tgl,tgl2,statusseq){
		//alert(denim);
 		if (tgl != ''){
 			var tga = tgl.split('/');
 			var istga = tga[2]+"-"+tga[0]+"-"+tga[1];
 		} else {
 			var istga = 'A';
 		}
 		if (tgl2 != ''){
 			var tgb = tgl2.split('/');
 			var istgb = tgb[2]+"-"+tgb[0]+"-"+tgb[1];
 		} else {
 			var istgb = 'A';
 		}
 		//Buyer
 		trimbuyer = buyer.trim();
		expbuyer = trimbuyer.split('#');
			if (expbuyer.length > 1) {
				expbuyer1 = expbuyer.join('K-');
			} else {
				expbuyer1 = trimbuyer;
			}
 		expbuyer2 = expbuyer1.split(' ');
	 		if (expbuyer2.length > 1){
	 			expbuyer3 = expbuyer2.join('_');  
	 		} else {
	 			expbuyer3 = expbuyer1;
	 		}
 		expbuyer4 = expbuyer3.split('/');
	 		if (expbuyer4.length > 1){
	 			buyer = expbuyer4.join('G-');  
	 		} else {
	 			buyer = expbuyer3;
	 		}

	 	//CMT
 		trimcmt = cmt.trim();
		expcmt = trimcmt.split('/');
 		cmt = expcmt.join('-');

 		//Style
 		trimstyle = style.trim();
		expstyle = trimstyle.split('#');
			if (expstyle.length > 1) {
				expstyle1 = expstyle.join('K-');
			} else {
				expstyle1 = trimstyle;
			}
 		expstyle2 = expstyle1.split(' ');
	 		if (expstyle2.length > 1){
	 			expstyle3 = expstyle2.join('_');  
	 		} else {
	 			expstyle3 = expstyle1;
	 		}
 		expstyle4 = expstyle3.split('/');
	 		if (expstyle4.length > 1){
	 			style = expstyle4.join('G-');  
	 		} else {
	 			style = expstyle3;
	 		}

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

	 	// if (cmt == ''){
	 	// 	swal({
			// 	  icon: 'warning',
			// 	  title: 'Cmt Is Empty',
			// 	  text: 'Sequence Process Not Found',
			// 	  footer: '<a href>Why do I have this issue?</a>'
			// 	});
	 	// } else {
	 		var hal = $('#hal').val();
			$('#loader-on').click();
			$('#cmtnumb').show();
			$('#tampilwip').load("apps/Transaction/Sequence/tampilwip.php?de="+denim+"&b="+buyer+"&s="+style+"&cm="+cmt+"&co="+color+"&tgl="+istga+"&tgl2="+istgb+"&statusseq="+statusseq);
			$('#no_buyer').val(buyer);
			$('#no_style').val(style);
			$('#no_cmt').val(cmt);
			$('#no_color').val(color);
			$('#tanggal1').val(istga);
			$('#tanggal2').val(istgb);
			$('#status_seq').val(statusseq);
			$('#loader-off').click();
		//}

		//window.location="content.php?p="+hal+"&b="+buyer+"&s="+style+"&cm="+cmt+"&co="+color+"&tgl="+istga+"&tgl2="+istgb;
	}

	
</script>