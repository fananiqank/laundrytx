function callMenu(id){
			document.body.scrollTop = document.documentElement.scrollTop = 0;
			$('#spinner-light').click();
			$(".blockUI").show();
			try {
				//alert('as');
					$.get( id ,function( data ) {
					 	$('#content-isi').html(data);
					 	//alert('aaaaaa');
					 	//$(document).ready(function(){
					 		//		alert('ab');
										$(".blockUI").css({ display: "none" });
						 //});
					});
					/*$.ajax({
				        url: 'assets/transaksi/pinjaman/content.php',
				        success: function(html){
				          //$('#content-isi').html(html);
				        }
				      });*/

					/*$.ajax({
				        url: 'assets/js/js/cont.php',
				        type: 'get',
				        success: function(html){
				        //	alert('as');
				          $('#content-isi').html(html);
				        }
				      });*/

					
			} catch (err){
				alert("ok");
			}
	}