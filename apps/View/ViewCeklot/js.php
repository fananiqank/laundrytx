<script>
	document.getElementById('lot_no').focus();
	
	function lcode (lot) {
    	if(event.keyCode == 13){// => Karakter enter dikenali sebagai angka 13
	        $('#tampilshow1').load("apps/View/ViewCeklot/view_ceklot.php?lot="+lot);
 	   } 
	}

	
</script>