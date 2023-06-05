<?php
if ($_GET['stat'] == 'process'){
  session_start();
  require_once("../../../funlibs.php");
  $con=new Database();
}
 
?>

  <div class="panel-body">
  
    <form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">

                  <div class="form-group">
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>User</b></label>
                        <div class="col-md-4">
                            <input type="text" name="lot_no" id="lot_no" class="form-control" value="" required>
                            <input type="hidden" name="buyer_no" id="buyer_no" class="form-control">
                        </div>
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Password</b></label>
                        <div class="col-md-4">
                            <input type="text" name="user" id="user" class="form-control" value="" onblur="cekuser(lot_no.value,this.value)" required>
                        </div>
                    </div>
                  </div>
                  
                  <div class="form-group" id="tampilcmt">
                    <?php include "tampilcmt.php"; ?>
                  </div>
                     
                  <input class="form-control" name="kode" id="kode" value="<?php echo $kode; ?>" type="hidden" />
                  <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
                 
                   
    </form>

