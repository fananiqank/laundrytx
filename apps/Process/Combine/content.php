<?php
  if ($_GET['stat'] == 'process'){
    // session_start();
    // require_once("../../../funlibs.php");
    // $con=new Database();
    $usercode = $_GET['usercode'];
  } else {
    $usercode = "";
  }
?>
<section class="panel"> 
  <div class="panel-body" id="loaded">
   
    <form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
                  <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>User</b></label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                          <input type="text" class="form-control" id="usercode" name="usercode" value="<?=$usercode?>" onkeyup="ucode()">
                          <input type="hidden" class="form-control" id="userid" name="userid" value="<?php echo $user[user_id]; ?>" readonly>
                        </div>
                        
                      <?php if($_GET['pro'] == 1) { ?>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                          <a href="content.php?option=Process&task=combine&act=ugr_process" class="btn btn-warning" style="float: right;">Back Process</a>
                        </div>
                      <?php } ?>

                  </div>
                  <div class="form-group">
                      	<label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Lot No</b></label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                          <input type="text" class="form-control" id="lot_no" name="lot_no" value="" onkeyup="lcode()">
                        </div> 

                        <div class="col-sm-5 col-md-5 col-lg-5">
                          <a href='javascript:void(0)' data-toggle='modal' data-target='#funModalrec' id='mod' class="btn btn-warning" style="float: right;" onclick="modelcombine()">Combine Hold</a>
                        </div>
                  </div>
                  <hr>
                  <div class="form-group" id="tampilcombine">
                      	<?php include "tampilcombine.php"; ?>
                  </div>
                   
                      <input id="jenis" name="jenis" value="" type="hidden">
                      <input class="form-control" name="kode" id="kode" value="<?php echo $kode; ?>" type="hidden" />
                      <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
                      <input type="hidden" name="action" id="action" value="">
                      <input type="hidden" name="parentlot" id="parentlot" value="<?php echo $_GET[parlot]; ?>">
                      <input type="hidden" name="parentlot" id="parentlot" value="<?php echo $_GET[parent1]; ?>">
       
    </form>
  </div>
</section>

    
