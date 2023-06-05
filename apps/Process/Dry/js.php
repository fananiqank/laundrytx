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
        }

	)};
	
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

	function enterrec(a){
		$('#tampilcontent').load("apps/Transaction/Process/content_rec.php");
	}

	function enterlot(a){
		$('#tampilcontent').load("apps/Transaction/Process/content_lot.php");
	}

	// function ceklot(lot,user){
	// 	alert(user);
	// 	if (lot == ''){
	// 		alert(lot);
	// 	} else {
	// 		idcmt = $('#idcmt').val();
	// 		showcmt = $('#showcmt').val();
	// 		trimcmt = showcmt.trim();
	// 		expcmt = trimcmt.split('/');
	//  		cmt = expcmt.join('-');
	// 		$.ajax({
	// 			type: 'GET',
	// 			url:  "apps/Process/Dry/tampillotnumber.php?id="+id+"&cmt="+cmt+"&idc="+idcmt,
	// 			success: function() {
	// 				$('#tampillot').load("apps/Transaction/LotMaking/tampillotnumber.php?id="+id+"&cmt="+cmt+"&idc="+idcmt);
	// 			}
	// 		});
	// 	}
	// }
	// var input = document.getElementById("user");
	// input.addEventListener("keyup", function(event) {
	//   if (event.keyCode === 13) {
	//   	var lot = $('#lot_no').val();
	//   	if (lot == ''){
	//   		alert("Error");
	//   		window.location.reload();
	//   	} else {
	//    		event.preventDefault();
	//    		cekuser($('lot_no').val(),$('user').val());
	//   	}
	//   }
	// });

	// var input = document.getElementById("lot_no");
	// input.addEventListener("keyup", function(event) {
	//   if (event.keyCode === 13) {
	//   	var user = $('#user').val();
	//   	if (user == ''){
	//   		alert("Error");
	//   		window.location.reload();
	//   	} else {
	// 	   event.preventDefault();
	// 	   cekuser($('lot_no').val(),$('user').val());
	//   	}
	//   }
	// });

	function cekuser(lot,user){
		var explot = lot.split('-');
		var lotres = explot[0].substr(-4,1);
		if (lot == ''){
			swal({
					  icon: 'error',
					  title: 'Lot Number Empty',
					  text: 'Lot Number Must be Filled',
					  footer: '<a href>Why do I have this issue?</a>'
					});
		} 
		
		
		//alert(lotres);	
		$('#tampilcmt').load("apps/Process/Dry/tampilcmt.php?lot="+lot+"&user="+user+"&typelot="+lotres);
			
		
	}

	function cancel(){
		window.location.reload();
	}

	function correct(lot,user,role,typelot,master_type_process){
		if (master_type_process == 2){
			swal({
				icon: 'error',
				title: 'Lot Making',
				text: 'Section on Lot Making',
				footer: '<a href>Why do I have this issue?</a>'
			});
		} else if (master_type_process == 3){
			swal({
				icon: 'error',
				title: 'QA Inspection',
				text: 'Section on QA Inspection',
				footer: '<a href>Why do I have this issue?</a>'
			});
		} else if (master_type_process == 5){
			swal({
				icon: 'error',
				title: 'Wet Process',
				text: 'Section on Wet',
				footer: '<a href>Why do I have this issue?</a>'
			});
		}
		else {
				swal({
				  title: "Are you sure?",
				  text: "Data is Correct",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				  		$('#tampilcontent').load("apps/Process/Dry/content_process.php?lot="+lot+"&user="+user+"&role="+role+"&typelot="+typelot);
				  		loopnotif(lot,user,role,typelot); 
				  }
				});
		}
	}
	function loopnotif(lot,user,role,typelot){
		setInterval(function(){ $('#tampilcontent').load("apps/Process/Dry/content_process.php?lot="+lot+"&user="+user+"&role="+role+"&typelot="+typelot);  }, 5000);
	}

	function back(a){
		if (a == 'process'){
			$('#tampilcontent').load("apps/Process/Dry/content_isi.php?stat="+a);
		}
	}
	
	function process_in(a,lot){
		$('#process-status').val(a);
					var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Process/Dry/simpan.php",
						data: data,
						success: function() {
							$('#tampilprocess').load("apps/Process/Dry/process_start.php?pro="+a+"&lot="+lot);	
						}
					});
				
	}
	function process_start(a,lot,machine,machinetime,usemachine){
		$('#process-status').val(a);

				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Process/Dry/simpan.php?machine="+machine+"&lot="+lot,
					data: data,
					success: function() {
						if (usemachine == '0') {
							$('#button-process').load("apps/Process/Dry/button-process-nonmachine.php?lot="+lot+"&machine="+machine);
						} else {
							$('#button-process').load("apps/Process/Dry/button-process.php?lot="+lot+"&machine="+machine);
						}
						$('#history').load("apps/Process/Dry/history.php?lot="+lot+"&machine="+machine);
						var menit = machinetime;
						var detik = 1;
						function hitung() {
							timer = setTimeout(hitung,1000);
							$('#tampilkanmenit_'+machine).load("apps/Process/Dry/hitung.php?menit="+menit+"&detik="+detik);
							detik --;
								if(detik < 0) {
								detik = 59;
								menit --;
									if(menit < 0) {
									menit = 0;
									detik = 0;
									clearTimeout(timer);
									swal({
										icon: 'info',
										title: 'Time Out',
										text: 'Please Click End',
										footer: '<a href>Why do I have this issue?</a>'
									});
									}
								}
						}
						hitung();
					}
				});
	}

	function process_end(a,lot,machine,usemachine){
		$('#process-status').val(a);
					var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Process/Dry/simpan.php?machine="+machine+"&lot="+lot,
						data: data,
						success: function() {
							if (usemachine == '0') {
								$('#button-process').load("apps/Process/Dry/button-process-nonmachine.php?lot="+lot+"&machine="+machine);
							} else {
								$('#button-process').load("apps/Process/Dry/button-process.php?lot="+lot+"&machine="+machine);
							}
							$('#history').load("apps/Process/Dry/history.php?lot="+lot+"&machine="+machine);
							clearTimeout(timer);
							load_unseen_notification();
						}
					});
				
	}
	function process_out(a,lot,user){
		swal({
				  title: "Are you sure?",
				  text: "Out This Lot From this Section",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
			}).then((willDelete) => {
				  	if (willDelete) {
				  		$('#process-status').val(a);
						var data = $('.form-user').serialize();
						$.ajax({
							type: 'POST',
							url:  "apps/Process/Dry/simpan.php",
							data: data,
							success: function() {
								window.location.reload();
							}	
						});
				  	}
				});
		
	}
	// var status = document.getElementById("process-status").val();
	// if (status == 1){
	// 	alert("masuk");
	// 	document.getElementById("process-in").disabled = true; 
	// }
	
	function machine(id,lot,time,role_wo_id){
		var sumtime = parseInt($('#jumtime').val())+parseInt(time);
		if (sumtime > parseInt($('#time').val())){
			swal({
				icon: 'error',
				title: 'Over Time Process',
				text: 'Check Your Time on Planning Process',
				footer: '<a href>Why do I have this issue?</a>'
			});
		} 
		else {
			$('#machine-input').val(1);
			var data = $('.form-user').serialize();
			$.ajax({
				type: 'POST',
				url:  "apps/Process/Dry/simpan.php",
				data: data,
				success: function() {
					$('#tampilmachine').load("apps/Process/Dry/tampilmachine.php?id="+id+"&lot="+lot);
					//$('#nextprocess').show();
					//$('#machine-input').val(0);
				}	
			});
		}
		
	}

	function machineinprocess(id,lot){
		$('#machine-input').val(4);
			var data = $('.form-user').serialize();
			$.ajax({
				type: 'POST',
				url:  "apps/Process/Dry/simpan.php",
				data: data,
				success: function() {
					$('#button-process').load("apps/Process/Dry/button-process.php?machine="+id+"&lot="+lot);
				}	
			});
		
	}

	function savetime(val,master,lot,no){
		hitungtime(val,no);
	}

	function hitungtime(val,no){
		var timeall = $('#time').val();
		var jumlahid = $('#jumid').val();
		var sum = 0;
		for (j=1;j<=jumlahid;j++){
			if ($('#timemac_'+j).val() == ''){
				var dtl = "0";
			} else { 	
				var dtl = $('#timemac_'+j).val();
			}

			sum += parseInt(dtl);
			if (parseInt(sum) > parseInt(timeall)){
				swal({
					  icon: 'error',
					  title: 'Overtime',
					  text: 'Time machine more than Time Planning',
					  footer: '<a href>Why do I have this issue?</a>'
					});
				$('#timemac_'+j).val('0');
				inputtime(no);
			} else {
				inputtime(no);

				
			}
		}
	}

	function inputtime(no){
			$('#machine-input').val(3);
				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Process/Dry/simpan.php?no="+no,
					data: data,
					success: function() {
						$('#tampilmachine').load("apps/Process/Dry/tampilmachine.php?id="+id+"&lot="+lot);
						$('#machine-input').val(0);
					}	
				});
	}

	function hapusmachine(procid,id,lot,sub){
		swal({
			title: "Are You Sure?",
			text: "Delete Machine" ,
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				
				$('#machine-input').val(2);
				$('#machine_id').val(procid);

				var data = $('.form-user').serialize();
				$.ajax({
					type: 'POST',
					url:  "apps/Process/Dry/simpan.php",
					data: data,
					success: function() {
						if (sub == 1){
							$('#button-process').load("apps/Process/Dry/button-process.php?machine="+id+"&lot="+lot);
						} else {
							$('#tampilmachine').load("apps/Process/Dry/tampilmachine.php?id="+id+"&lot="+lot);
						}
						$('#machine-input').val(0);
					}	
				});
			}
		});	
	}

// fungsi ini digunakan untuk mengecek penambahan machine pada multimachine tidak boleh lebih dari 1
	function cekmachine(lot,jmlmachine,counttime,time){
		
			$('#machine-input').val(4);
			var jmlcheck = $(":checkbox:checked").length;
			var jmlall = parseInt(jmlmachine)+parseInt(jmlcheck);
			if (jmlall > 1){
				swal({
					icon: 'error',
					title: 'Max Machine 1',
					text: '',
				    footer: '<a href>Why do I have this issue?</a>'
				});
				$(":checkbox:checked").prop("checked", false);
				$('#machine-input').val(0);
				$('#timemachine').val('');
			}
	}

	function hitungqtymach(good,reject){
		if (reject == ''){
			var reject = 0;
		} else {
			var reject = reject;
		} 

		var total = parseInt(good)+parseInt(reject);
		if (total > parseInt($('#qtylot').val())){
			swal({
					icon: 'error',
					title: 'Over Qty',
					text: 'Check Qty Lot',
				    footer: '<a href>Why do I have this issue?</a>'
				});
			$('#qtygood').val();
			$('#qtyreject').val('0');
			$('#qtytotal').val('');
		} else {
			if ($('#qtyreject').val() == ''){
				$('#qtyreject').val(0);
			}
		$('#qtytotal').val(total);
		}
	}
</script>