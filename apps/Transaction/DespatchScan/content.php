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
      <?php include "content_scan.php"; ?>
  </div>
</section>
