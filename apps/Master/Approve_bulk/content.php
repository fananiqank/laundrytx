<?php
  //perpindahan menu
  $exppage = explode('_',$_GET['task']);
  $page = $exppage[0];  
  $last = $exppage[1];  
  $selcmt = $con->select("laundry_wo_master_dtl_proc","*","wo_master_dtl_proc_id = '".$_GET['idm']."'");
  foreach ($selcmt as $cmt) {}

  //mendapatkan jumlah wo rework di keranjang wo master.
  $countrework = $con->selectcount("laundry_wo_master_keranjang","wo_master_keranjang_id","status_seq = '2'");
  
  if($countrework > 0) {
    $statusseq = "<option value='2'>Rework</option>";
  } else {
    $statusseq = "<option value='1'>New</option>
                  <option value='2'>Rework</option>";
  }

  if ($_GET['idm']){
    $model = "onclick='modeladdwo($_GET[idm],$_GET[d],1)'";

    //tombol simpan jika dari approve berbeda dengan dari edit
    if($_GET['l'] == "a"){
      $funcsimpan = "simpanapprove()";
    } else {
      $funcsimpan = "simpan()";
    }

  } else {
    $model = "onclick='model(1)'";
  }

////////////////////////////////////////////////////////////////////////////
  
  $onc = "onClick='pindahData2(nocmt.value,nocolor.value)'";

  if ($_GET['cm'] != ''){
    $cmt = $con->searchseqcmt($_GET['cm']);
    $ncmt = $cmt;
  } else {
    $ncmt = '';
  }
  if ($_GET['co'] != ''){
    $colors = $con->searchseq($_GET['co']);
    $ncol = $colors;
  } else {
    $ncol = '';
  }

  if ($_GET['xty'] != ''){
    $xftydate = $_GET['xty'];
    $xtyd = $xftydate;
  } else {
    $xtyd = '';
  } 

  if ($cmt != ''){
    $cm = "and wo_no = '$cmt'";
  } else {
    $cm = "";
  }

  if ($colors != ''){
    $co = "and garment_colors = '$colors'";
  } else {
    $co = "";
  }

  if ($xftydate != ''){
    $xdate = "and DATE(ex_fty_date) = '$xftydate'";
  } else {
    $xdate = "";
  }

?>

<section class="panel">
  <a class='btn btn-warning' style='margin-bottom: 0%;' data-toggle='collapse' data-target='#demo'>Filter</a>
  <?php if ($_GET['cm'] || $_GET['co'] || $_GET['xty']){ ?>
        <div id="demo" class="collapse in" >
     <?php } else { ?>
        <div id="demo" class="collapse" >
     <?php } ?>
          <hr>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>WO No <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocmt" id="nocmt" class="form-control" value="<?php echo $ncmt; ?>" required>
                <!-- <input type="text" name="idcmt" id="idcmt" class="form-control"> -->
            </div>
            <label class="col-md-2 control-label" for="profileLastName"><b>Colors <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocolor" id="nocolor" class="form-control" value="<?php echo $ncol; ?>" required>
            </div>
          </div>
          <!-- <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Ex fty Date <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="exftydate" id="exftydate" class="form-control" value="<?php echo $xtyd; ?>" required>
            </div>
           
          </div> -->
          <div class="form-group">
              <div class="col-md-12">
                <a href="content.php?option=View&task=history&act=ugr_view" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" <?php echo $onc; ?> >
                
              </div>
          </div>
          <hr>
        </div>
        
        <?php include "view_approve.php"; ?>
   
   <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
   <input class="form-control" name="getpage" id="getpage" value="<?php echo $page; ?>" type="hidden" />
</section>

    
