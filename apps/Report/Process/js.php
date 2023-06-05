<script>
cm = $('#nocmt').val();
co = $('#nocolor').val();
xty = $('#exftydate').val();
tpro = $('#tpro').val();
dpro = $('#dpro').val();
tgl1js = $('#tgl1js').val();
tgl2js = $('#tgl2js').val();
trimcmt = cm.trim();
expcmt = trimcmt.split('/');
cmts = expcmt.join('-');

trimcolor = co.trim();
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


if(tpro != '') {
	$(document).ready(function(){
       // tampil_data_barang();   //pemanggilan fungsi tampil barang.
        var spintable = $('#datatable-ajax').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": "apps/Report/Process/data.php?cm="+cmts+"&co="+color+"&xty="+xty+"&tpro="+tpro+"&dpro="+dpro+"&tgl1="+tgl1js+"&tgl2="+tgl2js,
				// "fnRowCallback": function(nRow, aData, iDisplayIndex) {
				// 	var index = iDisplayIndex + 1;
				// 	$('td:eq(0)', nRow).html(index);
				// 	return nRow;
				// },
				"lengthMenu": [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"]
				],
				"order": [ 9, 'desc' ],
				
			});
      //  testrecursive();
     //    function testrecursive(){
    	// 	setTimeout(() => {  spintable.ajax.reload(); testrecursive(); console.log("World!"); }, 10000);    
    	// }

	});
}
	function pindahData2(cmt,colors,exftydate,conpro,tgl,tgl2){

	 	//CMT
	 	// if (cmt == '') {
	 	// 	swal({
			// 	icon: 'error',
			// 	title: 'WO No Not Found',
			// 	text: 'Please Fill Wo No Number',
			// 	footer: '<a href>Why do I have this issue?</a>'
			// });
	 	// } else if (colors == '') {
	 	// 	swal({
			// 	icon: 'error',
			// 	title: 'Colors Not Found',
			// 	text: 'Please Fill Colors',
			// 	footer: '<a href>Why do I have this issue?</a>'
			// });
	 	// } else if (exftydate == '') {
	 	// 	swal({
			// 	icon: 'error',
			// 	title: 'Ex Fty Date Not Found',
			// 	text: 'Please Fill Ex Fty Date',
			// 	footer: '<a href>Why do I have this issue?</a>'
			// });
	 	// } else {
	 		trimcmt = cmt.trim();
			expcmt = trimcmt.split('/');
	 		cmts = expcmt.join('-');
		 	//Color
	 		trimcolor = colors.trim();
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

		 	expprocess= conpro.split('_');
		 	tpro=expprocess[0];
		 	dpro=expprocess[1];

	 		 var hal = $('#getp').val();
	 		 if(tpro == '0'){
	 		 	swall("Process Not Found!","Please Choose Process","error","2000");
	 		 } else if (tgl == '' || tgl2 == ''){
	 		 	swall("Periode Not Found!","Please input date 1 and date 2","error","2000");
	 		 } else {
			 	window.location="content.php?"+hal+"&cm="+cmts+"&co="+color+"&xty="+exftydate+"&tpro="+tpro+"&dpro="+dpro+"&tgl1="+tgl+"&tgl2="+tgl2;
			 }
	}

	$(function() {  
		    	//$('#nocmt').focus();
		        $("#nocmt").autocomplete({  
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Report/Process/sourcedata.php",
			                { d:'1', term:request.term}, 
			                response
			            );
			        },
		           	minLength:2, 
		        	select: function (event, ui) {
		            if (ui.item != undefined) {
		                $(this).val(ui.item.value);	
		                //$('#selected_id').val(ui.item.id);
				        $( "#idcmt" ).val( ui.item.id );
				       	//depar(ui.item.id_departemen);
		            	} 
		            	return false;
	        		}
		        });
	});

	$(function() {  
		        $("#nocolor").autocomplete({
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Report/Process/sourcedata.php",
			                { d:'2', term:request.term, scmt:$('#nocmt').val(), xty:$('#exftydate').val()}, 
			                response
			            );
			        },  
		           	minLength:0, 
		           	select: function (event, ui) {
		           	if (ui.item != undefined) {
		                $(this).val(ui.item.value);	
				        $( "#idcolor" ).val( ui.item.id );
		            	} 
		            	return false;
	        		}
		        }).focus(function () {
				    $(this).autocomplete("search", "");
			});        
	});

	$(function() {  
		    	
		        $("#exftydate").autocomplete({
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Report/Process/sourcedata.php",
			                { d:'3', term:request.term, scmt:$('#nocmt').val(), scolor:$('#nocolor').val()}, 
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