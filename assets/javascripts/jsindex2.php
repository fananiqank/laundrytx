<script>	
		function validate(email){

			$.post('validate_email.php', { email: email }, function(data){
				//alert(data);
				if (data == 1){
				$('#feedback').text('Email valid');
				$('#bs').val(data);
				$('#isibs').val(email);
				}
				else if (data == 2) {
				$('#feedback').text('Email not valid!!');
				$('#bs').val(data);
				$('#isibs').val(email);
				}
			});
		
		}
		
		$(document).ready(function(){

			$('#email').focus(function(){
		
				if($('#email').val() === ''){
					$('#feedback').text('Go on, enter a valid email address....');
				}else{
					validate($('#email').val());
				}
		
				
			}).blur(function(){
				if($('#bs').val() == 2){
				swal({
						  type: 'warning',
						  title: 'Email Not Valid!!',
						  text: 'Please enter your email correctly',
						  ConfirmButtonText: 'OK',
						})
				$('#email').val('');
				$('#email').focus()
				}
				else if ($('#bs').val() == 1){
					var str = $('#isibs').val();
					var res = str.split("@");
					if(res[1] == 'eratex.co.id'){
						$('#feedback').text('');
					} else {
						swal({
						  type: 'warning',
						  title: 'e-mail must use eratex email',
						  text: 'use your eratex email',
						  ConfirmButtonText: 'OK',
						})
					$('#email').val('');
					$('#email').focus()
					}
					
				}
			}).keyup(function(){
				validate($('#email').val());
				
			});
		
		});
		
		function valscript(evt) {
		  var charCode = (evt.which) ? evt.which : event.keyCode
		   if (charCode > 32 && (charCode < 44 || charCode > 57) && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122))
 
		    return false;
		  return true;
		}
		
		function allvalscript(evt) {
			  var charCode = (evt.which) ? evt.which : event.keyCode
			   if (charCode > 33 && (charCode < 35 || charCode > 38)&&(charCode < 40 || charCode > 64) && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122))
	 
			    return false;
			  return true;
		}
		
		function hanyaHuruf(evt) {
			  var charCode = (evt.which) ? evt.which : event.keyCode
			   if (charCode > 32 && (charCode < 48 || charCode > 57) && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122))
	 
			    return false;
			  return true;
		}
		<?php if($_GET[x] == 'view'){ ?>	
		(function( $ ) {

			'use strict';

			var datatableInit = function() {

				var $table = $('#datatable-ajax');
				$table.dataTable({
					"processing": true,
		         	"serverSide": true,
		        	"ajax": "data.php",
		        },

				);
			};
			
			$(function() {
				datatableInit();
			});

		 }).apply( this, [ jQuery ]);
		<?php } 
		if ($_GET[x] == 'tips'){
		?>
		 (function( $ ) {

			'use strict';

			var datatableInit = function() {

				var $table = $('#datatable-ajax');
				$table.dataTable({
					"processing": true,
		         	"serverSide": true,
		        	"ajax": "data2.php",
		        	"order": [[ 0, "desc" ]],
		        	"columnDefs": [
				    {
				        targets: 2,
				        className: 'dt-body-center'
				    }
				  ]
		        },

				);
			};
			
			$(function() {
				datatableInit();
			});

		 }).apply( this, [ jQuery ]);
		<?php } ?>

		function model(id){
			$("#funModal").show();
			isix = $("#getx").val();
			pg = $("#hal").val();
			$.get('modindex.php?id='+id+"&x="+isix, function(data) {
						$('#modalagent').html(data);    
				});
		}
</script>