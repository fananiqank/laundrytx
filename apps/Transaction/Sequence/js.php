<script>

if ($('#viewapp').val() == 'a') {
	(function( $ ) {

	'use strict';

		var datatableInit = function() {
			var $table = $('#datatable-ajax2');
			$table.dataTable({
				"processing": true,
	         	"serverSide": true,
	        	"ajax": "apps/Transaction/Sequence/dataneed.php",
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

} else {

	(function( $ ) {

	'use strict';

		var datatableInit = function() {
			var $table = $('#datatable-ajax');
			$table.dataTable({
				"processing": true,
	         	"serverSide": true,
	        	"ajax": "apps/Transaction/Sequence/data.php",
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
}

// if ($('#viewapp').val() == 'a') {
// 	$(document).ready(function(){
//        // tampil_data_barang();   //pemanggilan fungsi tampil barang.
//         var spintable = $('#datatable-ajax2').DataTable({
// 				"processing": true,
// 				"serverSide": true,
// 				"ajax": "apps/Transaction/Sequence/dataneed.php",
// 				"fnRowCallback": function(nRow, aData, iDisplayIndex) {
// 					var index = iDisplayIndex + 1;
// 					$('td:eq(0)', nRow).html(index);
// 					return nRow;
// 				},
// 				"lengthMenu": [
// 					[10, 25, 50, 100, -1],
// 					[10, 25, 50, 100, "All"]
// 				],
// 				"order": [ 0, 'desc' ]
// 			});
//       // testrecursive();
//      	// function testrecursive(){
//     	// 	setTimeout(() => {  spintable.ajax.reload(); testrecursive(); console.log("World!"); }, 10000);    
//     	// }
// 	});
// } else {
// 	$(document).ready(function(){
//        // tampil_data_barang();   //pemanggilan fungsi tampil barang.
//         var spintable = $('#datatable-ajax').DataTable({
// 				"processing": true,
// 				"serverSide": true,
// 				"ajax": "apps/Transaction/Sequence/data.php",
// 				"fnRowCallback": function(nRow, aData, iDisplayIndex) {
// 					var index = iDisplayIndex + 1;
// 					$('td:eq(0)', nRow).html(index);
// 					return nRow;
// 				},
// 				"lengthMenu": [
// 					[10, 25, 50, 100, -1],
// 					[10, 25, 50, 100, "All"]
// 				],
// 				"order": [ 0, 'desc' ]
// 			});
//       // testrecursive();
//      	// function testrecursive(){
//     	// 	setTimeout(() => {  spintable.ajax.reload(); testrecursive(); console.log("World!"); }, 10000);    
//     	// }
// 	});
// }
	function pindahData2(denim,buyer,style,cmt,color,tgl,tgl2,statusseq,color_wash){
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

	 	//Color WASH
 		trimcolorwash = color_wash.trim();
		expcolorwash = trimcolorwash.split('#');
			if (expcolorwash.length > 1) {
				expcolorwash1 = expcolorwash.join('K-');
			} else {
				expcolorwash1 = trimcolorwash;
			}
 		expcolorwash2 = expcolorwash1.split(' ');
	 		if (expcolorwash2.length > 1){
	 			expcolorwash3 = expcolorwash2.join('_');  
	 		} else {
	 			expcolorwash3 = expcolorwash1;
	 		}
 		expcolorwash4 = expcolorwash3.split('/');
	 		if (expcolorwash4.length > 1){
	 			color_wash = expcolorwash4.join('G-');  
	 		} else {
	 			color_wash = expcolorwash3;
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
			$('#tampilwip').html('<img src="./assets/images/spinner.gif">');
			$('#tampilwip').load("apps/Transaction/Sequence/tampilwip.php?de="+denim+"&b="+buyer+"&s="+style+"&cm="+cmt+"&co="+color+"&tgl="+istga+"&tgl2="+istgb+"&statusseq="+statusseq+"&search=1"+"&colorwash="+color_wash);
			$('#no_buyer').val(buyer);
			$('#no_style').val(style);
			$('#no_cmt').val(cmt);
			$('#no_color').val(color);
			$('#tanggal1').val(istga);
			$('#tanggal2').val(istgb);
			$('#status_seq').val(statusseq);
			$('#color_wash').val(color_wash);
			$('#loader-off').click();
		//}

		//window.location="content.php?p="+hal+"&b="+buyer+"&s="+style+"&cm="+cmt+"&co="+color+"&tgl="+istga+"&tgl2="+istgb;
	}

	$(function() {  
		$("#buyer_name").autocomplete({
		 	source: function(request, response) {
			    $.getJSON(
			        "apps/Transaction/Sequence/sourcedata.php",
			        { d:'1', term:request.term, sdenim: $('#denim_no').val(), scmt:$('#cmt_no').val(), sstyle:$('#style_no').val(), scolor:$('#color_no').val(), sstatus:$('#status_seq').val()}, 
			        response
			    );
			},
		   	minLength:2, 
		   	select: function (event, ui) {
		    	if (ui.item != undefined) {
		        	$(this).val(ui.item.value);	
					$( "#buyer_no" ).val( ui.item.id );
		    	} 
		    	return false;
		    }
		});
	});
	$(function() {  
		$("#cmt_no").autocomplete({
		 	source: function(request, response) {
			    $.getJSON(
			        "apps/Transaction/Sequence/sourcedata.php",
			        { d:'2', term:request.term, sdenim: $('#denim_no').val(), sbuyer:$('#buyer_no').val(), sstyle:$('#style_no').val(), sstatus:$('#status_seq').val()}, 
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
		$("#style_no").autocomplete({
		 	source: function(request, response) {
			    $.getJSON(
			        "apps/Transaction/Sequence/sourcedata.php",
			        { d:'3', term:request.term, sdenim: $('#denim_no').val(), sbuyer:$('#buyer_no').val(), scmt:$('#cmt_no').val(),sstatus:$('#status_seq').val()}, 
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
		$("#color_no").autocomplete({
		   //source: "apps/Transaction/Sequence/sourcedata.php?d=4&color="+$('#buyer_no').val(),  
		    source: function(request, response) {
			    $.getJSON(
			        "apps/Transaction/Sequence/sourcedata.php",
			        { d:'4', term:request.term, sdenim: $('#denim_no').val(), sbuyer:$('#buyer_no').val(), scmt:$('#cmt_no').val(), sstyle:$('#style_no').val(),sstatus:$('#status_seq').val()}, 
			        response
			    );
			},
		    minLength:0, 
		    select: function (event, ui) {
		    	if (ui.item != undefined) {
		        	$(this).val(ui.item.value);	
		        	$( "#color_wash" ).val( ui.item.color_wash );
		    	} 
		    	return false;
			}
		}).focus(function () {
		   $(this).autocomplete("search", "");
		});        
	});

	
	function viewdata(a){
		$('#view').val(a);
		javascript: window.location.href="content.php?option=Transaction&task=sequence"+"_"+a+"&act=ugr_transaction";
	}

	function denim(id){
		if(id != ''){
			$('#groupdenim').show();
		} 
	}

	
	function up(id,seq,sub){
		$('#naik').val(seq);
		$('#idnya').val(id);
		$('#sub').val(sub);
			var data = $('.form-user').serialize();
			$.ajax({
				type: 'POST',
				url:  "apps/Transaction/Sequence/simpan.php",
				data: data,
				success: function() {
					$('#tampildata').load("apps/Transaction/Sequence/tampil.php?d="+$('#isid').val());
					$('#naik').val('');
					$('#idnya').val('');
				}
			});
				
			
		//$('#id').val(id);
		//javascript: document.getElementById('form_index').submit();
		//javascript: window.location.href="index.php?x=berita_e&id="+id;
		//alert(id);
		
	}
	function down(id,seq,sub){
	$('#turun').val(seq);
	$('#idnya').val(id);
	$('#sub').val(sub);
		var data = $('.form-user').serialize();
		$.ajax({
				type: 'POST',
				url:  "apps/Transaction/Sequence/simpan.php",
				data: data,
				success: function() {
				$('#tampildata').load("apps/Transaction/Sequence/tampil.php?d="+$('#isid').val());
				$('#turun').val('');
				$('#idnya').val('');
							}
		});		
	}

	function upedit(id,seq,sub){
		$('#naik').val(seq);
		$('#idnyaedit').val(id);
		$('#sub').val(sub);
		$('#editseqwo').val(1);
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url:  "apps/Transaction/Sequence/simpan.php?ed=1",
			data: data,
			success: function() {
				$('#loader-on').click();
				$('#tampildata').load("apps/Transaction/Sequence/tampileditwo.php?d="+$('#isid').val()+"&idm="+$('#idm').val());
				$('#naik').val('');
				$('#idnyaedit').val('');
				$('#loader-off2').click();

			}
		});
	}

	function downedit(id,seq,sub){
	$('#turun').val(seq);
	$('#idnyaedit').val(id);
	$('#sub').val(sub);
	$('#editseqwo').val(1);
	var data = $('.form-user').serialize();
	$.ajax({
		type: 'POST',
		url:  "apps/Transaction/Sequence/simpan.php?ed=2",
		data: data,
		success: function() {
			$('#tampildata').load("apps/Transaction/Sequence/tampileditwo.php?d="+$('#isid').val()+"&idm="+$('#idm').val());
			$('#turun').val('');
			$('#idnyaedit').val('');
		}
	});		
	}

	function cekwip(id){
    	// Check
    	var jm = $('#jmlwip').val();
    	for(i=1;i<=jm;i++){
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
	function savewo(a,b){
		//alert($('#typesource_').val());
		$('#cmtwo').val(a);
		$('#submit-control').hide();
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url:  "apps/Transaction/Sequence/simpan.php?typesource="+$('#typesource').val(),
			data: data,
			success: function(ui) {
				uisplit = ui.split('_');
				uiexec = uisplit[0];
//jika success tersimpan
				if(uiexec == '1'){
					swal({
						 icon: 'success',
						 title: 'saved',
						 text: '',
						 footer: '<a href>Why do I have this issue?</a>'
				  		  
					})
					.then((willDelete) => {
					  if (willDelete) {
						$('#loader-on').click();
						$('#tampilwo').load("apps/Transaction/Sequence/tampilwo.php");
						$('#addbutton').load("apps/Transaction/Sequence/addbutton.php?typesourceadd="+$('#typesource').val());
						$('#tampilwip').load("apps/Transaction/Sequence/tampilwip.php?de="+$('#denim_no').val()+"&b="+$('#no_buyer').val()+"&s="+$('#no_style').val()+"&cm="+$('#no_cmt').val()+"&co="+$('#no_color').val()+"&tgl="+$('#tanggal1').val()+"&tgl2="+$('#tanggal2').val()+"&statusseq="+$('#status_seq').val()+"&reloseq=1");
						$('#checkwip').prop("checked", false);
						$('#checkwo').prop("checked", false);
						$('#tampilapp').load("apps/Transaction/Sequence/tampilapp.php?de="+$('#denim_no').val()+"&b="+$('#no_buyer').val()+"&s="+$('#no_style').val()+"&cm="+$('#no_cmt').val()+"&co="+$('#no_color').val()+"&tgl="+$('#tanggal1').val()+"&tgl2="+$('#tanggal2').val()+"&statusseq="+$('#status_seq').val());
						$('#loader-off2').click();
						$('#cmtwo').val('');
						$('#cmtwo2').val('');
						$('#codeproc').val('');
						$('#submit-control').show();
					  }
					}); 
				} 
// jika tidak di pilih sama sekali wo nya
				else if(uiexec == '3'){
					swall('Please Check WO','','error',2000);
					$('#submit-control').show();
					$('#cmtwo').val('');
					$('#cmtwo2').val('');
					$('#codeproc').val('');
				} 
// jika tidak sesuai source nya dari QRCODE atau dari EDI/ORDER
				else if(uiexec == '2'){
					swall('WO Source Not same','WO source QRCODE Or Manual','warning',3000);
					$('#submit-control').show();
					$('#cmtwo').val('');
					$('#cmtwo2').val('');
					$('#codeproc').val('');
					$(":checkbox").prop("checked", false);
				}
				else if(uiexec == '4'){
					swall('Data Not Insert','Call ITD','error',2000);
					$('#submit-control').show();
					$('#cmtwo').val('');
					$('#cmtwo2').val('');
					$('#codeproc').val('');
					$(":checkbox").prop("checked", false);
				}
			}
		});
	}
	function hapusker(a){
		$('#cmtwo').val(a);
		var data = $('.form-user').serialize();
		$.ajax({
				type: 'POST',
				url:  "apps/Transaction/Sequence/simpan.php",
				data: data,
				success: function() {
					$('#tampilwo').load("apps/Transaction/Sequence/tampilwo.php");
					$('#tampilapp').load("apps/Transaction/Sequence/tampilapp.php");
					$('#checkwip').prop("checked", false);
					$('#checkwo').prop("checked", false);
					$('#cmtwo').val('');
					$('#cmtwo2').val('');
					$('#codeproc').val('');
				}
		});
	}
	function hapusproses(a,b,c,d){
		swal({
			  title: "Are you sure?",
			  text: "Delete This Process",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			})
			.then((willDelete) => {
			  if (willDelete) {
			  	$('#cmtwo2').val(10);
				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Transaction/Sequence/simpan.php?d="+a+"&e="+b+"&f="+c,
					data: data,
					success: function() {
						$('#tampildata').load("apps/Transaction/Sequence/tampil.php");
						$('#turun').val('');
						$('#idnya').val('');
						$('#tampilapp').load("apps/Transaction/Sequence/tampilapp.php");
						$('#cmtwo').val('');
						$('#cmtwo2').val('');
						$('#codeproc').val('');
					}
				});
			    swall("Process has been deleted!","","success",2000);
			  } 
			});
	}

	function hapusprosesedit(a,b,c,d,idm){
		swal({
			  title: "Are you sure?",
			  text: "Delete This Process",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true
			}).then((willDelete) => {
				if (willDelete) {
					$('#cmtwo2').val(11);
					$('#editseqwo').val(1);
					var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/Sequence/simpan.php?d="+a+"&e="+b+"&f="+c,
						data: data,
						success: function() {
							$('#tampildata').load("apps/Transaction/Sequence/tampileditwo.php?d="+d+"&idm="+idm);
							$('#turun').val('');
							$('#idnyaedit').val('');
							$('#tampilapp').load("apps/Transaction/Sequence/tampilapp.php?idm="+$('#getidm').val());
							$('#cmtwo').val('');
							$('#cmtwo2').val('');
							$('#editseqwo').val('');
							$('#codeproc').val('');
						}
					});
				}
			});
	}
	
	function hapus(id){
		$('#id').val(id);
		$('#aksi').val('hapus');
		javascript: document.getElementById('form_index').submit();
		
	}
	function hitung(a,b,c,d){

		$('#cmtwo').val(c);
		var countdtl = $('#countdtl_'+b).val();
		var sum = 0;
		for (j=1;j<=countdtl;j++){
			if ($('#seq_'+j+'_'+b).val() == ''){
				var dtl = "0";
			}else { 	
				var dtl = $('#seq_'+j+'_'+b).val();
			}
			sum += parseInt(dtl);
		}
		$('#mainseq_'+b).val(sum);
		$('#cmtwo2').val(4);
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url:  "apps/Transaction/Sequence/simpan.php?d="+b,
			data: data,
			success: function(ui) {
				$('#tampil').load("apps/Transaction/Sequence/tampil.php");
				$('#cmtwo').val('');
				if (sum == 0){
					$('#validasitime').val(0);
				} else {
					$('#validasitime').val(1);
				}
				
			}
		});
	}

	function hitungmain(a,b,c){
		$('#cmtwo2').val(c);
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url:  "apps/Transaction/Sequence/simpan.php?d="+b,
			data: data,
			success: function() {
				$('#tampil').load("apps/Transaction/Sequence/tampil.php");
				$('#cmtwo').val('');
				$('#cmtwo2').val('');
				$('#codeproc').val('');
			}
		});
	}

	function hitungedit(a,b,c,d){

		$('#cmtwo').val(c);
		var countdtl = $('#countdtl_'+b).val();
		var sum = 0;
		for (j=1;j<=countdtl;j++){
			if ($('#seq_'+j+'_'+b).val() == ''){
				var dtl = "0";
			}else { 	
				var dtl = $('#seq_'+j+'_'+b).val();
			}
			sum += parseInt(dtl);
		}
		$('#mainseq_'+b).val(sum);
		$('#cmtwo2').val(7);
		$('#editseqwo').val(1);
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url:  "apps/Transaction/Sequence/simpan.php?d="+b,
			data: data,
			success: function() {
				$('#tampil').load("apps/Transaction/Sequence/tampileditwo.php");
				$('#cmtwo').val('');
				$('#cmtwo2').val('');
				$('#editseqwo').val('');
				$('#codeproc').val('');
				if (sum == 0){
					$('#validasitime').val(0);
				} else {
					$('#validasitime').val(1);
				}
			}
		});
	}

	function hitungmainedit(a,b,c){
		//alert(c);
		$('#cmtwo2').val(c);
		$('#editseqwo').val(1);
		var data = $('.form-user').serialize();
			$.ajax({
				type: 'POST',
				url:  "apps/Transaction/Sequence/simpan.php?d="+b,
				data: data,
				success: function() {
					$('#tampil').load("apps/Transaction/Sequence/tampileditwo.php");
					$('#cmtwo').val('');
					$('#cmtwo2').val('');
					$('#editseqwo').val('');
					$('#codeproc').val('');
					
				}
			});
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

	function kate(id){

		$.get('apps/Transaction/Sequence/sourceprocess.php?id='+id, function(data) {
				$('#proc').html(data);    
		});
		var ids = id.split("_");
		if (ids[0] > 3){
			$('#cekal').show();
			$('#uncekal').show();
			
		} else {
			$('#cekal').hide();
			$('#uncekal').hide();
			
		}
		
	}	

	function temp(id){
		$('#tampildata').html('<img src="./assets/images/spinner.gif">');
		$.get('apps/Transaction/Sequence/sourcetemplate.php?id='+id, function(data) {
				$('#tampildata').html(data);    
		});
		$('#simpan-all').hide();
		$('#mod').hide();
		$('#buttontemplate').show();
		$('#temple').val(9);
	}	

	function resettemplate(id){
		$.get('apps/Transaction/Sequence/tampil.php', function(data) {
				$('#tampildata').html(data);    
		});
		$('#simpan-all').show();
		$('#mod').show();
		$('#buttontemplate').hide();
		$('#temp_name').val('');
		$(".collapse").collapse('hide');
	}	

	function usetemplate(d){
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url:  "apps/Transaction/Sequence/simpan.php?d="+d,
			data: data,
			success: function() {
				$('#tampil').load("apps/Transaction/Sequence/tampil.php");
				$('#cmtwo').val('');
				$('#cmtwo2').val('');
				$('#codeproc').val('');
			}
		});
	}	

	function onc(el){
			var sem = $(":checkbox:checked").length;
			$('#process_'+el).val(el+"_"+sem);
			$('#urseq_'+el).val(sem);
			if (document.querySelector('#process_'+el+':checked') == null) {
			    $('#urseq_'+el).val("");
			}
	}
	function onc2(el,id){
			var sam = $(":checkbox:checked").length;
			$('#process2_'+el).val(el+"_"+sam+"_"+id);
			$('#urseq_'+el).val(sam);
			if (document.querySelector('#process2_'+el+':checked') == null) {
			    $('#urseq_'+el).val("");
			}
		
	}

	function cekall(id){
	// Check
	$(":checkbox").prop("checked", true);
		for(i=1;i<=id;i++) {
			var sem = $(":checkbox:checked").length;
			$('#process_'+el).val(el+"_"+sem);
			$('#urseq_'+el).val(sem);
		}
	}
	 
	function uncekall(id){
	// Uncheck
	$(":checkbox").prop("checked", false);
	$('#urseq_'+el).val(sem);
	}

	function uncekall(id){
	// Uncheck
	$(":checkbox").prop("checked", false);
	$('#urseq_'+el).val(sem);
	}

	function saveedit(d,idm,type) {
		swal({
		  title: "Are you sure?",
		  text: "Save Editing Process",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willSave) => {
			if (willSave) 
			{
				if (type == 1){
					$('#codeproc').val("input");
					var data = $('.form-user').serialize();
					$.ajax({
							type: 'POST',
							url:  "apps/Transaction/Sequence/simpan.php",
							data: data,
							success: function() {
								$('#tampildata').load("apps/Transaction/Sequence/tampileditwo.php?d="+d+"&idm="+idm);
								$('#tampilapp').load("apps/Transaction/Sequence/tampilapp.php?d="+d+"&idm="+idm);
								$('#cmtwo').val('');
								$('#cmtwo2').val('');
								$('#codeproc').val("");
								$('#editseqwo').val(1);
							}
					});
				} else {
					$('#codeproc').val("edit");
					var data = $('.form-user').serialize();
					$.ajax({
							type: 'POST',
							url:  "apps/Transaction/Sequence/simpan.php",
							data: data,
							success: function() {
								$('#tampildata').load("apps/Transaction/Sequence/tampileditwo.php?d="+d+"&idm="+idm);
								$('#tampilapp').load("apps/Transaction/Sequence/tampilapp.php?d="+d+"&idm="+idm);
								$('#cmtwo').val('');
								$('#cmtwo2').val('');
								$('#codeproc').val("");
								$('#editseqwo').val(1);
								//document.location='content.php?p='+$('#getp').val();
							}
					});
				}
				

			}
		});
		
	} 

	function saveeditnew() {
		swal({
		  title: "Are you sure?",
		  text: "Save Editing Process",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willSave) => {
			if (willSave) 
			{
				$('#codeproc').val("edit");
				var data = $('.form-user').serialize();
				$.ajax({
						type: 'POST',
						url:  "apps/Transaction/Sequence/simpan.php",
						data: data,
						success: function() {
							$('#codeproc').val('');
							window.location.reload();
						}
				});
			}
		});
		
	}

	function hapusroledtl (a,b,c,d,e,f,idm){
			$('#hpsdtl').val(a);
			$('#codeproc').val("hapusdtl");
			if (e != '0'){
				$('#editseqwo').val(1);
				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Transaction/Sequence/simpan.php?id="+b+"&seq="+d+"&jns="+e,
					data: data,
					success: function() {
						$('#tampildata').load("apps/Transaction/Sequence/tampileditwo.php?d="+f+"&idm="+idm);
						$('#proc').load("apps/Transaction/Sequence/sourceprocessedit2wo.php?id="+b+"&j="+c+"&seq="+d+"&jns="+e+"&de="+f);
						$('#hpsdtl').val('');
						$('#cmtwo').val('');
						$('#cmtwo2').val('');
						$('#codeproc').val('');
						$('#editseqwo').val('');						
					}
				});
			} else {
				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Transaction/Sequence/simpan.php?id="+b+"&seq="+d,
					data: data,
					success: function() {
						$('#tampildata').load("apps/Transaction/Sequence/tampil.php");
						$('#proc').load("apps/Transaction/Sequence/sourceprocessedit2.php?id="+b+"&j="+c+"&seq="+d);
						$('#hpsdtl').val('');
						$('#cmtwo').val('');
						$('#cmtwo2').val('');
						$('#codeproc').val('');
						$('#editseqwo').val('');	
					}
				});
			}
	}
	
//simpan data dari editing sequence
	function simpan(a){
		swal({
		  title: "Are you sure?",
		  text: "Save Process",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willOK) => {
		if (willOK) 
		{
    		if ($('#jmlappwo').val() == '' && $('#idm').val() == ''){
    			swall('Data Not Complete!!','Check WO No on Plan or Sequence Process','error',2000);
    			return false;
    		} else if ($('#role_grup_m_id').val() == '' && $('#idm').val() == ''){
    			swall('Sequence Process Not Found','Sequence Process Not Found','error',2000);
    			return false;
    		} else if ($('#validasitime').val() == '0'){
    			swall('Time not Set!','Please Set your Time','error',2000);
    			return false;
    		} else {
    			if($('#idm').val() == ''){
					$('#simpanan').val('simpanall');
				} else {
					$('#simpanan').val('simpanedit');
				}
				
				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Transaction/Sequence/simpan.php",
					data: data,
					success: function() {
						swall('saved','','success',1000);
						if($('#idm').val() == ''){
							$('#tampildata').load("apps/Transaction/Sequence/tampil.php");
							$('#tampil').load("apps/Transaction/Sequence/tampil.php");
							$('#tampilwo').load("apps/Transaction/Sequence/tampilwo.php");
							$('#tampilapp').load("apps/Transaction/Sequence/tampilapp.php");
							$('#tampilwip').load("apps/Transaction/Sequence/tampilwip.php");
							$('#simpanan').val('');
							$('#codeproc').val('');
						} else {
							window.location.href="content.php?option=Transaction&task=sequence_v&act=ugr_transaction";
						}
						
					}
				});	
			}
		} else {
			return false;
		}
		});
	}

	//function edit sequence drag n drop d detail role
    // function editsequence(roleid,masterid,grupseq){
    // 	$('#proc').load("apps/Transaction/Sequence/sourcesequence.php?id="+roleid+"&j="+masterid+"&seq="+grupseq);
    // }

    //function simpan Edit Sequence WO (saat klik awal Edit)
    function editrolewo(option,task,act,d,idm,l){
    	swal({
			  title: "Are you sure?",
			  text: "Edit This Process",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			})
			.then((willDelete) => {
			  	if (willDelete) {
			    	$('#modsequenceeditid').val(d);
			    	var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/Sequence/simpan.php",
						data: data,
						success: function() {
							$('#modsequenceeditid').val('');
							$('#codeproc').val('');
							window.location.href="content.php?option="+option+"&task=sequence&act="+act+"&d="+d+"&idm="+idm+"&l="+l;
						}
					});
				}
			});
    }

    function savehold(id){
    	swal({
			  title: "Are you sure?",
			  text: "Hold/Unhold This WO/Colors/Ex Fty Date?",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			})
			.then((willDelete) => {
			  	if (willDelete) {
			    	$('#simpanhold').val(1);
			    	var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/Sequence/simpan.php?id="+id,
						data: data,
						success: function() {
							$('#simpanhold').val('');
							$('#codeproc').val('');
							window.location.reload();
						}
					});
				}
			});
    }

    function updproc(procid, exdate){
			$.ajax({
				type: 'GET',
				url:  "apps/Transaction/Sequence/simpan_updproc.php?procid="+procid+"&exdate="+exdate,
				success: function(lot) {
					swal({
						  icon: 'Warning',
						  title: 'Are You Sure?',
						  text: 'Sesuaikan Ex Fty Date',
						  footer: '<a href>Why do I have this issue?</a>'
					}).then((willoke) => {
						window.location.reload();
					});
				} 
					
			});
	}

    function Reset () {
    	window.location.reload();
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