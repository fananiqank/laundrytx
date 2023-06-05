<?php
    //perpindahan menu
  $exppage = explode('_',$_GET['task']);
  $page = $exppage[0];  
  $last = $exppage[1];    
   
     $selectuser = $con->select("m_users a join laundry_user_section b on a.user_id=b.user_id","a.user_id,username","a.user_id = '".$_SESSION['ID_LOGIN']."'");
     foreach ($selectuser as $user) {}  

     if($_GET['parent1']){
        if($_GET['parent1'] != ''){
          foreach ($con->select("laundry_process a join laundry_lot_number b on a.lot_no = b.lot_no join (select parent_first from laundry_lot_number_dtl order by lot_dtl_id DESC) as c on b.lot_id=c.lot_id","a.*,b.lot_id","b.lot_id = '".$_GET['parent1']."'") as $procparent) {} 
         
        } 
        
     }

?>
<section class="panel"> 
  <div class="panel-body" id="loaded">
   
    <form class="form-user" id="formku" method="post" enctype="multipart/form-data">
        
                  <div class="form-group">
                      <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>User</b></label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                          <input type="text" class="form-control" id="usercode" name="usercode" value="" >
                          <input type="hidden" class="form-control" id="userid" name="userid" value="<?php echo $user[user_id]; ?>" readonly>
                        </div>  

                      <?php if($_GET['pro'] == 1) { ?>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                          <a href="content.php?option=Process&task=split&act=ugr_process" class="btn btn-warning" style="float: right;">Back Process</a>
                        </div>
                      <?php } ?>
                  
                  </div>
                  <div class="form-group">
                      <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Lot No</b></label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                          <input type="text" class="form-control" id="lot_no" name="lot_no" value="">
                        </div> 
                      	
                  </div>

                  <div class="form-group" id="tampilcmt">
                      	
                  </div>
                  <?php 
                    if($_GET['parent1'] != ''){
                        $parlot = $procparent['lot_no'];
                        $parent1 = $procparent['lot_id'];
                    } else {
                        $parlot = 0;
                        $parent1 = 0;
                    }
                  ?>
                      <input id="jenis" name="jenis" value="" type="hidden">
                      <input class="form-control" name="kode" id="kode" value="<?php echo $kode?>" type="hidden" />
                      <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
                      <input type="hidden" id="hal" name="hal" value="<?php echo $get?>">
                      <input type="hidden" name="parlot" id="parlot" value="<?php echo $_GET[parlot]?>">
                      <input type="hidden" name="parent1" id="parent1" value="<?php echo $_GET[parent1]?>">
       
    </form>
  </div>
</section>
