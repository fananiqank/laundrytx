<script>
 //alert("jooosss");

// (function( $ ) {

// 	'use strict';

// 	var datatableInit = function() {

// 		var $table = $('#datatable-ajax');
// 		$table.dataTable({
// 			"processing": true,
//          	"serverSide": true,
//         	"ajax": "apps/Master/Shift/data.php",
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
				"ajax": "apps/Master/Shift/data.php",
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
		$('#tampilprocess').load("apps/Master/Shift/detailshift.php?p="+getp+"&id="+id);

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
	 	else{
			swal({
			  title: "Are you sure?",
			  text: "Save New Shift",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			})
			.then((willSave) => {
			  	if (willSave) {
			    	var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Master/Shift/simpan.php",
						data: data,
						success: function() {
							// $('#tampilprocess').load("apps/Master/Shift/detailshift.php?id=");
							// $('#datatable-ajax').DataTable().ajax.reload();

							window.location.reload();
						}
					});
				}
			});
		}
	}
	
	function deleted (id) {
			swal({
			  title: "Are you sure?",
			  text: "Delete Shift",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			})
			.then((willSave) => {
			  	if (willSave) {
			    	var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Master/Shift/simpan.php?id="+id+"&del=1",
						data: data,
						success: function() {
							//window.location.reload();
							$('#datatable-ajax').DataTable().ajax.reload();
						}
					});
				}
			});
	}
</script>