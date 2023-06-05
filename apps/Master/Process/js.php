<script>
 //alert("jooosss");

// (function( $ ) {

// 	'use strict';

// 	var datatableInit = function() {

// 		var $table = $('#datatable-ajax');
// 		$table.dataTable({
// 			"processing": true,
//          	"serverSide": true,
//         	"ajax": "apps/Master/Process/data.php",
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
				"ajax": "apps/Master/Process/data.php",
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
		$('#tampilprocess').load("apps/Master/Process/detailprocess.php?p="+getp+"&id="+id);
	}
	function hapus(id){
		$('#id').val(id);
		$('#aksi').val('hapus');
		javascript: document.getElementById('form_index').submit();
		
	}
	function detagent(id){
		$('#id').val(id);
		//javascript: document.getElementById('form_index').submit();
		javascript: window.location.href="index.php?x=berita_e&id="+id;
		//alert(id);
		
	}
	function nilai(id){
		alert ($('#gambar').val(id));
		var t = $('#gambar').val(id);
		$('#nilai').val(t);
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
						url:  "apps/Master/Process/simpan.php",
						data: data,
						success: function() {
							window.location.reload();
						}
					});
				}
			});
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

	function Reset () {
		window.location.reload();
	}

//function modal //
	function modalproc(id){
		$("#funModal").show();
			$.get('apps/Master/Process/modprocess.php?&id='+id, function(data) {
					$('#modalagent').html(data);    
		});
	}
//end function modal //
	
</script>