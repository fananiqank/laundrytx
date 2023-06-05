<?php
    //perpindahan menu
  $exppage = explode('_',$_GET['task']);
  $page = $exppage[0];  
  $last = $exppage[1];    
   
  $selrolegrupmas2 = $con->select("laundry_role_wo_master","role_wo_master_id","role_wo_master_status = 1");
  foreach ($selrolegrupmas2 as $grupmas2) {}
  
  if ($last != '') {
    //jika view
    $onc = "onClick='pindahData2(nocmt.value,nocolor.value,exftydate.value)'";
  } else {
    //jika bukan view
    $onc = "onClick='pindahData2(nocmt.value,nocolor.value,exftydate.value)'";
  }

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
  <div class="panel-body" id="loaded">
    <a class='btn btn-warning' style='margin-bottom: 0%;' data-toggle='collapse' data-target='#demo'>Filter</a>
    <?php if ($_GET['cm'] || $_GET['co'] || $_GET['xty']){ ?>
          <div id="demo" class="collapse in" >
        <?php } else { ?>
          <div id="demo" class="collapse in" >
        <?php } ?>
          <hr>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>WO No <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocmt" id="nocmt" class="form-control" value="<?php echo $ncmt; ?>" required>
            </div>
            <label class="col-md-2 control-label" for="profileLastName"><b>Colors<font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocolor" id="nocolor" class="form-control" value="<?php echo $ncol; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Ex fty Date <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="exftydate" id="exftydate" class="form-control" value="<?php echo $xtyd; ?>" required>
            </div>
           
          </div>
          <div class="form-group">
              <div class="col-md-12">
                <a href="content.php?option=View&task=lotnumber&act=ugr_view" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" <?php echo $onc; ?> >
              </div>
          </div>
           <hr>
        </div>
    <form class="form-user" id="formku" method="post" enctype="multipart/form-data">

       <?php include "view_lot.php"; ?>      
      
        <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
        <input type="hidden" name="no_cmt" id="no_cmt" value="<?php echo $_GET[cm]; ?>">
        <input type="hidden" name="no_colors" id="no_colors" value="<?php echo $_GET[co]; ?>">
        <input type="hidden" name="no_ex_fty_date" id="no_ex_fty_date" value="<?php echo $_GET[xty]; ?>">
    
    </form>
  </div>
</section>

    
