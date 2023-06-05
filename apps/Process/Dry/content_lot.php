<?php
  session_start();
  require_once("../../../funlibs.php");
  $con=new Database();
    //perpindahan menu
  $first = substr($_GET[p], 0,1);
  $last = substr($_GET[p], 1,3);    
   
      $selrolegrupmas2 = $con->select("laundry_role_grup_master","role_grup_master_id","role_grup_master_status = 1");
      foreach ($selrolegrupmas2 as $grupmas2) {}
    ?>

    <a href="javascript:void(0)">
      <img src="assets/images/go-back.png" width="5%" onclick="back()">
    </a>
    <br><br>
    <form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">

                  <div class="form-group">
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Lot Number</b></label>
                        <div class="col-md-4">
                            <input type="text" name="buyer_name" id="buyer_name" class="form-control" value="" required>
                            <input type="hidden" name="buyer_no" id="buyer_no" class="form-control">
                        </div>
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>User</b></label>
                        <div class="col-md-4">
                            <input type="text" name="cmt_no" id="cmt_no" class="form-control" value="" required>
                        </div>
                    </div>
                  </div>
                       
                     
                      <input id="naik" name="naik" value="" type="hidden">
                      <input id="turun" name="turun" value="" type="hidden">
                      <input id="idnya" name="idnya" value="" type="hidden">
                      <input id="sub" name="sub" value="" type="hidden">
                      <input id="cmtwo" name="cmtwo" value="" type="hidden">
                      <input id="cmtwo2" name="cmtwo2" value="" type="hidden">
                      <input id="rolegrupmas2" name="rolegrupmas2" value="<?=$grupmas2[role_grup_master_id]?>" type="hidden">
                      <input class="form-control" name="kode" id="kode" value="<?=$kode?>" type="hidden" />
                      <input class="form-control" name="getp" id="getp" value="<?=$_GET[p]?>" type="hidden" />
                      <input type="hidden" id="hal" name="hal" value="<?=$get?>">
                      <input type="hidden" name="no_style" id="no_style" value="">
                      <input type="hidden" name="no_cmt" id="no_cmt" value="">
                      <input type="hidden" name="no_color" id="no_color" value="" onchange="color(this.value)">
                      <input type="hidden" name="tanggal1" id="tanggal1" value="">
                      <input type="hidden" name="tanggal2" id="tanggal2" value="">
                      <input type="hidden" name="no_buyer" id="no_buyer" value="">
    </form>
 
    
