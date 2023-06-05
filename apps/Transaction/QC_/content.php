<?php 

$exppage = explode('_',$_GET['p']);
$page = $exppage[0];  
$last = $exppage[1];  
// $exppage = explode('_',$_GET['p']);
// $page = $exppage[0];  
// $last = $exppage[1];  
?>

<section class="panel">
    <div class="panel-body" id="scancontent">
    	<!-- <div class="col-md-6 col-lg-6">
    		 <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%; float: right; padding: 20px" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelqcscan('<?=$typescan?>','<?=$rec_id?>','<?=$_GET[rolewoid]?>')">Receive QC</a>
    	</div>
    	<div class="col-md-6 col-lg-6">
    		 <a href="javacript:void(0)" class="btn btn-warning" style="margin-bottom: 0%; padding: 20px" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelqcscan('<?=$typescan?>','<?=$rec_id?>','<?=$_GET[rolewoid]?>')">Scan QC</a>
    	</div> -->
      <?php include "content_scan.php"; ?>
  </div>
</section>
