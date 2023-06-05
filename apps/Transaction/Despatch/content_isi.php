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
<br><br>
<div class="form-group">
  <div class="form-group">
      <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>User</b></label>
          <div class="col-md-4">
              <input type="text" name="usercode" id="usercode" class="form-control" onkeyup="ucode()" value="<?=$usercode?>" required>
              <input type="hidden" name="userid" id="userid" class="form-control" value="<?php echo $_SESSION[ID_LOGIN]?>" required>
              <input type="hidden" name="mastertypeid" id="mastertypeid" class="form-control" value="<?php echo $user[master_type_process_id]; ?>" required>
          </div>
      <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Lot Number</b></label>
      <div class="col-md-4">
          <input type="text" name="lot_no" id="lot_no" class="form-control" onkeyup="lcode()" value="" required>
          <input type="hidden" name="buyer_no" id="buyer_no" class="form-control">
           <input type="hidden" id="stat" name="stat" value="<?=$_GET[stat]?>">
      </div>
      
  </div>
</div>

<div class="form-group" id="tampilcmt">
  <?php //include "tampilcmt2.php"; ?>
</div>
   
<input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />

<script type="text/javascript">
  if($('#stat').val() == 'process'){
    $('#lot_no').focus();
  }
</script>
