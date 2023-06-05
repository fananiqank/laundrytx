
<?php
session_start();
include "../../../funlibs.php";
$con = new Database();


?>
<div class="col-lg-12 col-md-12" id="formcombine">
	<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
		<div class="col-lg-12 col-md-12">
		
			<input id="getp" name="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" >
			
				<fieldset id="mastercombinetable">
					<?php include "sourcemodcombineholdmaster.php"; ?>
				</fieldset>
		
		</div>
		<!-- <div class="col-lg=6 col-md-6" id="tampilcombinehold">
		</div> -->
	</form>
</div>

