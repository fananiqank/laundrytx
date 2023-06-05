<script>
//alert("jooosss");

(function( $ ) {

	'use strict';

	var datatableInit = function() {
		var $table = $('#datatable-ajax');
		$table.dataTable({
			"processing": true,
         	"serverSide": true,
        	"ajax": "apps/Transaction/Sequence/data.php",
        	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			   var index = iDisplayIndex +1;
			   $('td:eq(0)',nRow).html(index);
			   return nRow;
			} 
        },

	);
		    

    	
	};
	
	$(function() {
		datatableInit();
	});

 }).apply( this, [ jQuery ]);

 (function( $ ) {

	'use strict';

	/*
	Basic
	*/
	$('.modal-basic').magnificPopup({
		type: 'inline',
		preloader: false,
		modal: true
	});

	/*
	Sizes
	*/
	$('.modal-sizes').magnificPopup({
		type: 'inline',
		preloader: false,
		modal: true
	});

	/*
	Modal with CSS animation
	*/
	$('.modal-with-zoom-anim').magnificPopup({
		type: 'inline',

		fixedContentPos: false,
		fixedBgPos: true,

		overflowY: 'auto',

		closeBtnInside: true,
		preloader: false,
		
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-zoom-in',
		modal: true
	});

	$('.modal-with-move-anim').magnificPopup({
		type: 'inline',

		fixedContentPos: false,
		fixedBgPos: true,

		overflowY: 'auto',

		closeBtnInside: true,
		preloader: false,
		
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-slide-bottom',
		modal: true
	});

	/*
	Modal Dismiss
	*/
	$(document).on('click', '.modal-dismiss', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});

	/*
	Modal Confirm
	*/
	$(document).on('click', '.modal-confirm', function (e) {
		e.preventDefault();
		$.magnificPopup.close();

		new PNotify({
			title: 'Success!',
			text: 'Modal Confirm Message.',
			type: 'success'
		});
	});

	/*
	Form
	*/
	$('.modal-with-form').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#name',
		modal: true,

		// When elemened is focused, some mobile browsers in some cases zoom in
		// It looks not nice, so we disable it:
		callbacks: {
			beforeOpen: function() {
				if($(window).width() < 700) {
					this.st.focus = false;
				} else {
					this.st.focus = '#name';
				}
			}
		}
	});

	/*
	Ajax
	*/
	$('.simple-ajax-modal').magnificPopup({
		type: 'ajax',
		modal: true
	});

}).apply( this, [ jQuery ]);
// // Call template init (optional, but faster if called manually)
// 		$.template.init();

// 		// Table sort - DataTables
// 		var table = $('#datatable-ajax');
// 		//alert(table);
// 		table.dataTable({
// 				"processing": true,
//         		"serverSide": true,
//         		"ajax": "apps/master/agent/data.php",
// 				"pagingType": "full_numbers",
// 				"order": [[ 0, "desc" ]],
// 				"fnRowCallback": function (nRow, aData, iDisplayIndex) {
// 					 var info = $(this).DataTable().page.info();
// 					 $("td:nth-child(1)", nRow).html(info.start + iDisplayIndex + 1);
// 					 return nRow;
// 				 }
			
// 		});

	function pindahData2(buyer,style,cmt,color,tgl,tgl2){
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

 		var hal = $('#hal').val();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/Sequence/tampilwip.php?b="+buyer+"&s="+style+"&cm="+cmt+"&co="+color+"&tgl="+istga+"&tgl2="+istgb,
						success: function() {
							$('#tampilwip').load("apps/Transaction/Sequence/tampilwip.php?b="+buyer+"&s="+style+"&cm="+cmt+"&co="+color+"&tgl="+istga+"&tgl2="+istgb);
							$('#no_buyer').val(buyer);
							$('#no_style').val(style);
							$('#no_cmt').val(cmt);
							$('#no_color').val(color);
							$('#tanggal1').val(istga);
							$('#tanggal2').val(istgb);
						}
					});
		//window.location="content.php?p="+hal+"&b="+buyer+"&s="+style+"&cm="+cmt+"&co="+color+"&tgl="+istga+"&tgl2="+istgb;
	}

	function enterrec(a){
		$('#tampilcontent').load("apps/Transaction/Process/content_rec.php");
	}

	function enterlot(a){
		$('#tampilcontent').load("apps/Transaction/Process/content_lot.php");
	}

	function back(a){
		$('#tampilcontent').load("apps/Transaction/Process/content_first.php");
	}
	
	function ccmt(id){
		//CMT
 		trimcmt = id.trim();
 		expapp = trimcmt.split('_');
 		app = expapp[1];
 		id = expapp[2];
 		cmt2 = expapp[0];
		expcmt = cmt2.split('/');
 		cmt = expcmt.join('-');
 		if (app == 0) {
 			alert("Sequence Process must be Approve");
 			$('#tampilcmt').load("apps/Transaction/LotMaking/tampilcmt.php");
 			$('#tampilselectlot').load("apps/Transaction/LotMaking/selectlot.php");
			$('#showcmt').val('');
 			$('#idcmt').val('');
 		}
 		else {
				$.ajax({
						type: 'GET',
						url:  "apps/Transaction/LotMaking/tampilcmt.php?id="+cmt,
						success: function() {
							$('#tampilcmt').load("apps/Transaction/LotMaking/tampilcmt.php?id="+cmt);
							$('#tampilselectlot').load("apps/Transaction/LotMaking/selectlot.php?id="+id);
							$('#showcmt').val(cmt2);
 							$('#idcmt').val(id);
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

	function lotnumber(id){
			idcmt = $('#idcmt').val();
			showcmt = $('#showcmt').val();
			trimcmt = showcmt.trim();
			expcmt = trimcmt.split('/');
	 		cmt = expcmt.join('-');
			$.ajax({
				type: 'GET',
				url:  "apps/Transaction/LotMaking/tampillotnumber.php?id="+id+"&cmt="+cmt+"&idc="+idcmt,
				success: function() {
					$('#tampillot').load("apps/Transaction/LotMaking/tampillotnumber.php?id="+id+"&cmt="+cmt+"&idc="+idcmt);
				}
			});
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
							$('#tampildata').load("apps/Transaction/Sequence/tampil.php");
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
							$('#tampildata').load("apps/Transaction/Sequence/tampil.php");
							$('#turun').val('');
							$('#idnya').val('');
						}
					});		
	}

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
					url:  "apps/Transaction/Sequence/simpan.php",
					data: data,
					success: function() {
						$('#tampilwo').load("apps/Transaction/Sequence/tampilwo.php");
						$('#tampilwip').load("apps/Transaction/Sequence/tampilwip.php?b="+$('#no_buyer').val()+"&s="+$('#no_style').val()+"&cm="+$('#no_cmt').val()+"&co="+$('#no_color').val()+"&tgl="+$('#tanggal1').val()+"&tgl2="+$('#tanggal2').val());
						$('#checkwip').prop("checked", false);
						$('#checkwo').prop("checked", false);
						$('#tampilapp').load("apps/Transaction/Sequence/tampilapp.php?b="+$('#no_buyer').val()+"&s="+$('#no_style').val()+"&cm="+$('#no_cmt').val()+"&co="+$('#no_color').val()+"&tgl="+$('#tanggal1').val()+"&tgl2="+$('#tanggal2').val());
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
						$('#tampilwip').load("apps/Transaction/Sequence/tampilwip.php?b="+$('#no_buyer').val()+"&s="+$('#no_style').val()+"&cm="+$('#no_cmt').val()+"&co="+$('#no_color').val()+"&tgl="+$('#tanggal1').val()+"&tgl2="+$('#tanggal2').val());
						$('#tampilapp').load("apps/Transaction/Sequence/tampilapp.php");
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
					url:  "apps/Transaction/Sequence/simpan.php?d="+a+"&e="+b+"&f="+c,
					data: data,
					success: function() {
						$('#tampildata').load("apps/Transaction/Sequence/tampil.php");
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
			//alert(dtl);
			sum += parseInt(dtl);
			
		}
		$('#mainseq_'+b).val(sum);
		$('#cmtwo2').val(4);
		var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Transaction/Sequence/simpan.php?d="+b,
					data: data,
					success: function() {
						$('#tampil').load("apps/Transaction/Sequence/tampil.php");
						$('#cmtwo').val('');
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
						$('#cmtwo2').val('');
					}
			});
	}

	function detagent(id){
		$('#id').val(id);
		//javascript: document.getElementById('form_index').submit();
		javascript: window.location.href="index.php?x=berita_e&id="+id;
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

	function saveedit() {
		$('#codeproc').val("edit");
	}
	function hapusroledtl (a,b,c,d){
			$('#hpsdtl').val(a);
			$('#codeproc').val("hapusdtl");
			var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Transaction/Sequence/simpan.php?id="+b+"&seq="+d,
						data: data,
						success: function() {
							$('#tampildata').load("apps/Transaction/Sequence/tampil.php");
							$('#proc').load("apps/Transaction/Sequence/sourceprocessedit2.php?id="+b+"&j="+c+"&seq="+d);
								$('#hpsdtl').val('');
								//$('#idnya').val('');
								$('#codeproc').val("");
						}
					});
	}
	

</script>