<section class="panel">
  <?php
    //perpindahan menu
    $first = substr($_GET[p], 0,1);
    $last = substr($_GET[p], 1,3);    
  ?>

  <div class="panel-body" id="tampilcontent">
        <?php include "content_isi.php"; ?>
  </div>

</section>

    
