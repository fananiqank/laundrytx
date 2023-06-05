<h4>Lot Number Rework Not In Process</h4>
<div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF;">
 
  	<div class="col-md-4 pre-scrollable" style="height: 200px;">
	    <h5><b>Choose Lot </b> &nbsp;<span style="float: right"><input type="checkbox" value="1" onClick="cekswip(this)" id="checkwip"> All &emsp;</span></h5>
	    <div id="tampilwip1"></div>
	</div>
	<div class="col-md-1" style="height: 200px;" id="submit-control">
		<br>
        <br>
        <br>
        <a href="javascript:void(0)" onclick="savewo(1,'<?php echo $_GET[statusseq]; ?>')" class="btn btn-success" style="font-size: 16px;vertical-align: middle;"><b>>></b></a>
	</div>
	<div class="col-md-7 pre-scrollable" style="height: 200px;" >
		<h5><b>On Plan </b> &nbsp;</h5>
    	<div id="tampilwip2">
    		<?php include "tampilwip.php"; ?>
    	</div>
  	</div>
 
  
</div>
