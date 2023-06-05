<?php
    //perpindahan menu
  $exppage = explode('_',$_GET['task']);
  $page = $exppage[0];  
  $middle = $exppage[1];
  $last = $exppage[2];  

      $selrolegrupmas2 = $con->select("laundry_role_wo_master","role_wo_master_id","role_wo_master_status = 1");
      foreach ($selrolegrupmas2 as $grupmas2) {}
?>
<section class="panel"> 
    <form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
      <div class="panel-body" id="tampilcontent">
        <?php 
              include "content_isi.php";      
        
        ?>  
      </div>    
                      
                      <hr>
               
                      <input id="jenis" name="jenis" value="" type="hidden">
                      <input class="form-control" name="kode" id="kode" value="<?=$kode?>" type="hidden" />
                      <input type="hidden" name="no_style" id="no_style" value="">
                      <input type="hidden" name="no_cmt" id="no_cmt" value="">
                      <input type="hidden" name="no_color" id="no_color" value="">
                      <input type="hidden" name="no_buyer" id="no_buyer" value="">
                      <input type="hidden" name="ceknolot" id="ceknolot" value="">
                      <input type="hidden" name="typesequence" id="typesequence" value="">
   
    </form>
  </div>
</section>

    
