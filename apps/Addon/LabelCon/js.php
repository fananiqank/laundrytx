<script>
	
	$(function() {
		    	//$('#nocmt').focus();
		        $("#nocmt").autocomplete({  
		         	source: function(request, response) {
			            $.getJSON(
			                "apps/Addon/LabelCon/sourcedata.php",
			                { d:'1', term:request.term}, 
			                response
			            );
			        },
		           	minLength:2, 
		        	select: function (event, ui) {
		            if (ui.item != undefined) {
		                $(this).val(ui.item.value);	
		                //$('#selected_id').val(ui.item.id);
				        //$( "#idcmt" ).val( ui.item.id );
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
			                "apps/Addon/LabelCon/sourcedata.php",
			                { d:'2', term:request.term, scmt:$('#nocmt').val()}, 
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

	function simpan(){
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url: "apps/Addon/LabelCon/simpan.php",
			data: data,
			success: function(lot) {
				explot = lot.split('_');
				swal({
					icon: 'success',
					title: 'Label : ' + explot[0],
					text: 'Saved',
				}).then((willoke) => {
					if (willoke) {
						javascript: window.open("lib/pdf-qrcode_label.php?c=" + explot[1], "_blank", "width=700,height=450");
						//window.location.reload();
						$('#usercode').val($('#usercode').val());
						$('#nocmt').val('');
						$('#nocolor').val('');
						$('#label_qty').val('');
					}
				});
			}
		});
	}

	
	
</script>