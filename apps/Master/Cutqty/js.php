<script>


	// (function( $ ) {

	// 'use strict';

	// 	var datatableInit = function() {
	// 		var $table = $('#datatable-ajax');
	// 		$table.dataTable({
	// 			"processing": true,
	//          	"serverSide": true,
	//         	"ajax": "apps/Master/Cutqty/data.php",
	//         	"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	//         	"order": [ 0, 'desc' ],
 //   			// "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	// 		//    var index = iDisplayIndex +1;
	// 		//    $('td:eq(0)',nRow).html(index);
	// 		//    return nRow;
	// 		// } 
 //        	});
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
				"ajax": "apps/Master/Cutqty/data.php",
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

    function savecutting(){
    	swal({
			  title: "Are you sure?",
			  text: "Save Change Cut Qty",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			})
			.then((willDelete) => {
			  	if (willDelete) {
			    	var data = $('.form-user').serialize();
					$.ajax({
						type: 'POST',
						url:  "apps/Master/Cutqty/simpan.php",
						data: data,
						success: function() {
							window.location.reload();
						}
					});
				}
			});
    }

//function modal //
	function model(id){
		$("#funModal").show();
			$.get('apps/Master/Cutqty/modcutqty.php?id='+id, function(data) {
					$('#modalagent').html(data);    
		});
	}
//end function modal //
</script>