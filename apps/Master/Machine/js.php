<script>
 //alert("jooosss");

// (function( $ ) {

// 	'use strict';

// 	var datatableInit = function() {

// 		var $table = $('#datatable-ajax');
// 		$table.dataTable({
// 			"processing": true,
//          	"serverSide": true,
//         	"ajax": "apps/Master/Machine/data.php",
//         	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
// 			   var index = iDisplayIndex +1;
// 			   $('td:eq(0)',nRow).html(index);
// 			   return nRow;
// 			}
//         },

// 	);
		    

    	
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
				"ajax": "apps/Master/Machine/data.php",
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

 
	function edit(id){
		
		var getp = $('#getp').val();
		$('#kode').val(id);
		$('#tampilprocess').load("apps/Master/Machine/detailmachine.php?p="+getp+"&id="+id);
	}
	function hapus(id){
		$('#id').val(id);
		$('#aksi').val('hapus');
		javascript: document.getElementById('form_index').submit();
		
	}

	function simpan () {
		if($('#name').val() == ''){
			swal({
				  icon: 'warning',
				  title: 'Name Is Null',
				  timer : 2000,
			});
			$('#name').focus();
		}
		else if($('#machine_code').val() == ''){
			swal({
				  icon: 'warning',
				  title: 'Machine Code is Null',
				  timer : 2000,
			});
			$('#name').focus();
		}
		else if($('#machine_type_use').val() == ''){
			swal({
				  icon: 'warning',
				  title: 'Machine Type Null',
				  timer : 2000,
			});
			$('#name').focus();
		}
	 	else{
			swal({
			  title: "Are you sure?",
			  text: "Save New Process",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			})
			.then((willSave) => {
			  	if (willSave) {
			    	var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Master/Machine/simpan.php",
						data: data,
						success: function() {
							window.location.reload();
						}
					});
				}
			});
		}
	}

	function Reset () {
		window.location.reload();
	}

	function onc(el){
			var sem = $(":checkbox:checked").length;

			$('#process_'+el).val(el+"_"+sem);
			$('#counting').val(sem);
			// if (document.querySelector('#process_'+el+':checked') == null) {
			//     $('#urseq_'+el).val("");
			// }
	}

	function saveprocess (id,typepro) {
		if ($('#counting').val() == 0){
			swal({
				  icon: 'error',
				  title: 'Process Not Selected',
				  timer : 2000,
			});
		}
		
	 	else{
			swal({
			  title: "Are you sure?",
			  text: "Save Process for Machine",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			})
			.then((willSave) => {
			  	if (willSave) {
			  		$('#confprocess').val(1);
			    	var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Master/Machine/simpan.php",
						data: data,
						success: function() {
							$('#tampilproc').load("apps/Master/Machine/sourcemodprocess.php?reload=1&id="+id+"&typepro="+typepro);
							$('#tampilmachproc').load("apps/Master/Machine/sourcemodmachproc.php?reload=1&id="+id+"&typepro="+typepro);
						}
					});
				}
			});
		}
	}

	function resetprocess(id,typepro){
		$('#tampilproc').load("apps/Master/Machine/sourcemodprocess.php?reload=1&id="+id+"&typepro="+typepro);
		
	}
	
	function deleteprocess (idd,idm,typepro) {
		
			swal({
			  title: "Are you sure?",
			  text: "Delete Process for Machine",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			})
			.then((willSave) => {
			  	if (willSave) {
			  		$('#confprocess').val(2);
			    	var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Master/Machine/simpan.php?idd="+idd+"&idm="+idm,
						data: data,
						success: function() {
							$('#tampilproc').load("apps/Master/Machine/sourcemodprocess.php?reload=1&id="+idm+"&typepro="+typepro);
							$('#tampilmachproc').load("apps/Master/Machine/sourcemodmachproc.php?reload=1&id="+idm+"&typepro="+typepro);
						}
					});
				}
			});
	}

	function checkcode(a){
				$.ajax({
					type: 'GET',
					url:  "apps/Master/Machine/checkcode.php?id="+a,
					success: function(code) {
						$('#machine_code').val(code);
					}
				});
	}
//function modal //
	function modalmachproc(id){
		
		$("#funModalrec").show();
		pg = $("#getp").val();
			$.get('apps/Master/Machine/modmachproc.php?p='+pg+'&id='+id, function(data) {
					$('#modalrec').html(data);    
		});
	}

	
// end function modal //

</script>