<script>
//alert("jooosss");

c = $('#kategori').val();
y = $('#priority').val();
t = $('#lstatus').val();
g = $('#ass').val();
if($('#tgl').val() == '' ){
	tgsatu = 'A';
} else {
	tgsatu = $('#tglsatu').val();
}

if($('#tgl2').val() == '' ){
	tgdua = 'A';
} else {
	tgdua = $('#tgldua').val();
}

(function( $ ) {

	'use strict';

	var datatableInit = function() {

		var $table = $('#datatable-ajax');
		$table.dataTable({
			"processing": true,
         	"serverSide": true,
         	"scrollX" : false,
        	"ajax": "apps/report/tasks/data.php?c="+c+"&y="+y+"&t="+t+"&g="+g+"&tg="+tgsatu+"&tg2="+tgdua,
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

 	function pindahData2(c,y,t,g,tg,tg2){
 		if (tg != ''){
 			var tga = tg.split('/');
 			var istga = tga[2]+"-"+tga[0]+"-"+tga[1];
 		} else {
 			var istga = 'A';
 		}
 		if (tg != ''){
 			var tgb = tg2.split('/');
 			var istgb = tgb[2]+"-"+tgb[0]+"-"+tgb[1];
 		} else {
 			var istgb = 'A';
 		}
 		var hal = $('#hal').val();
		window.location="content.php?p="+hal+"&c="+c+"&y="+y+"&t="+t+"&g="+g+"&tg="+istga+"&tg2="+istgb;
	}
	function edit(id){
		$('#id').val(id);
		//javascript: document.getElementById('form_index').submit();
		javascript: window.location.href="index.php?x=berita_e&id="+id;
		//alert(id);
		
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

	
</script>