<section class="panel">
  <?php
    //perpindahan menu
  $first = substr($_GET[p], 0,1);
  $last = substr($_GET[p], 1,3);    
   
      $selrolegrupmas2 = $con->select("laundry_role_grup_master","role_grup_master_id","role_grup_master_status = 1");
      foreach ($selrolegrupmas2 as $grupmas2) {}
    ?>

  <div class="panel-body" id="tampilcontent">
        <?php include "content_first.php"; ?>
  </div>
</section>

    
