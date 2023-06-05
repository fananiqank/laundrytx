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
      <?php 
      		if($_GET['task'] == 'qcinspectscan') {
      			include "content_scan_pro.php"; 
      		} else {
      			include "content_scan.php"; 
      		}
      		
      ?>
  </div>
</section>
