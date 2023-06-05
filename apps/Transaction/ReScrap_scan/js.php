<script>
	document.getElementById('usercode').focus();

	function swall(title, text, icon, timer) {
		swal({
			title: title,
			text: text,
			icon: icon,
			timer: timer
		});
	}

	tyseq = $('#status_seq').val();

	(function($) {

		'use strict';

		var datatableInit = function() {
			var $table = $('#datatable-ajax2');
			$table.dataTable({
				"processing": true,
				"serverSide": true,
				"ajax": "apps/Transaction/ReScrap_scan/data.php?type="+tyseq,
				// "fnRowCallback": function(nRow, aData, iDisplayIndex) {
				// 	var index = iDisplayIndex + 1;
				// 	$('td:eq(0)', nRow).html(index);
				// 	return nRow;
				// },
				"lengthMenu": [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"]
				]
			});

		};

		$(function() {
			datatableInit();
		});

	}).apply(this, [jQuery]);

	function savetoreceive(id,type) {
			if ($('#usercode').val() != ''){
				swal({
					title: "Are You Sure?",
					text: "Save Receiving",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((savedata) => {
					if (savedata) {
						$('#confirm').val(type);
							var data = $('.form-user').serialize();
							$.ajax({
								type: 'POST',
								url: "apps/Transaction/ReScrap_scan/simpan.php",
								data: data,
								success: function(lot) {
										$('#datatable-ajax2').DataTable().ajax.reload();
										explot = lot.split('_');
										$('#confirm').val('');
										swal({
											icon: 'success',
											title: 'Lot No : ' + explot[0],
											text: 'Saved',
										}).then((willoke) => {
											if (willoke) {
												javascript: window.open("lib/pdf-qrcode.php?c=" + explot[1], "_blank", "width=700,height=450");
												window.location.reload();
												$('#close').click(function() {
													$('#funModalrec').modal('hide');
												});
												$('#tampilscan').load("apps/Transaction/ReScrap_scan/sourcedatascan.php");
												$('#cart').load("apps/Transaction/ReScrap_scan/cart.php");
											}
										});
								}
							});
					}
				});
			} else {
				swall('User Not Found', 'Please Input User!!', 'error', 2000);
			}	
	}

	function changestatus(a){
		var hal = $('#getp').val();
		window.location="content.php?"+hal+"&type="+a;
	}

	function ceknow(skg,rcv){
		// alert(skg);
		// alert(rcv);
		if(parseInt(rcv) < parseInt(skg)) {
			swall('Qty More Than Total Scan', 'Check Receive Now', 'error', 2000);
			$('#recnow').val('');
			$('#rwknow').val(0);
		} 

	}
</script>