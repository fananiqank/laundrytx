<?php if($_GET['id'] == '1') { ?>
<a href="javascript:void(0)" class='btn btn-primary' style='margin-bottom: 0%;float: right;' onclick="Reset()">Create Lot</a>
<?php } else if($_GET['id'] == '2') { ?>
<a href="javascript:void(0)" class='btn btn-warning' onclick="reclot(3)" style='margin-bottom: 0%;float: right;'>End Process</a>
<a href="javascript:void(0)" class='btn btn-primary' style='margin-bottom: 0%;float: right;' onclick="Reset()">Create Lot</a>
<?php } else if($_GET['id'] == '3') { ?>
<a href="javascript:void(0)" class='btn btn-warning' style='margin-bottom: 0%;float: right;' onclick="Reset()">Create Lot</a>
<a href="javascript:void(0)" class='btn btn-primary' onclick="reclot(2)" style='margin-bottom: 0%;float: right;'>Receive Lot</a>
<?php } else { 
 	
 		if ($_GET['p'] == "MjAwNg=="){
?>
		<a href="javascript:void(0)" class='btn btn-primary' onclick="reclot(1)" style='margin-bottom: 0%;float: right;'>Receive / End Lot</a>
<?php 	} else {

?>
		<a href="javascript:void(0)" class='btn btn-warning' onclick="reclot(3)" style='margin-bottom: 0%;float: right;'>End Process</a>
		<a href="javascript:void(0)" class='btn btn-primary' onclick="reclot(2)" style='margin-bottom: 0%;float: right;'>Receive Lot</a>

<?php 
		}	
	
} 

?>
  