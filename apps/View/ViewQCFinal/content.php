<?php 

$exppage = explode('_',$_GET['p']);
$page = $exppage[0];  
$last = $exppage[1];  
// $exppage = explode('_',$_GET['p']);
// $page = $exppage[0];  
// $last = $exppage[1];  


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
if ($_GET['type'] != ''){
  $ntype = $_GET['type'];
} else {
  $ntype = '';
}


if ($cmt != ''){
  $cm = "and wo_no = '".$cmt."'";
} else {
  $cm = "";
}

if ($colors != ''){
  $co = "and garment_colors = '".$colors."'";
} else {
  $co = "";
}

if ($_GET['type'] != ''){
  $type = "and wo_master_dtl_qc_qty = '".$_GET['type']."'";
} else {
  $type = "";
}

if ($_GET['xty'] != ''){
  $xftydate = $_GET['xty'];
  $xtyd = $xftydate;
} else {
  $xtyd = '';
} 

if ($xftydate != ''){
  $xdate = "and DATE(ex_fty_date) = '$xftydate'";
} else {
  $xdate = "";
}

?>


<section class="panel">
    <div class="panel-body">
      <a class='btn btn-warning' style='margin-bottom: 0%;' data-toggle='collapse' data-target='#demo'>Filter</a>

      <?php if($last == 'v') { ?>
          <a href="content.php?option=View&task=qcfinal&act=ugr_view" class='btn btn-success' style='margin-bottom: 0%;'>View Sewing Data</a>
      <?php } else { ?> 
         <!--  <a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('v')">View Receive Data</a> -->
         <!--  <span class="badge" style='margin-bottom: 0%;float: right;' id="cart">
            <?php include "cart.php";?>            
          </span>
          <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelcart()"><i class="fa fa-shopping-cart"></i></a> -->
      <?php } ?>

        <?php if ($_GET['cm'] || $_GET['co']){ ?>
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

            <!-- <label class="col-md-2 control-label" for="profileLastName"><b>Type</b></label>
            <div class="col-md-4">
                <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="type_qc" id="type_qc">
                      <option value="">All</option>
                      <option value="1" <?php if($ntype == 1){echo "Selected";}?> >Good</option>
                      <option value="2" <?php if($ntype == 2){echo "Selected";}?>>Scrap</option>
                      <option value="3" <?php if($ntype == 3){echo "Selected";}?>>Rework</option>
                </select>
            </div> -->
          </div>
         
          <div class="form-group">
              <div class="col-md-12">
                <a href="content.php?option=View&task=qcfinal&act=ugr_view" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" onClick='pindahData2(nocmt.value,nocolor.value,exftydate.value)' >
              </div>
          </div>
           <hr>
        </div>

    </header>
    
    <form class="form-user" id="formku" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
                            <div class="row" align="center">
                            <h4><b>QC Final Data</b></h4>
                            </div>
                              <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax2" style="font-size: 13px;">
                                <thead>
                                  <tr>
                                    <th width="17%">WO No</th>
                                    <th width="15%">Color Qr</th>
                                    <th width="15%">Color Wash</th>
                                    <th width="15%">Ex Fty Date</th>
                                    <th width="5%">Sequence</th>
                                    <th width="5%">Good</th>
                                    <th width="5%">Reject</th>
                                    <th width="5%">Rework</th>
                                    <!-- <th width="5%">Act</th> -->
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                        </div>
                    </div>
       
                       
                      <input id="view" name="view" value="<?php echo $last; ?>" type="hidden">
                      <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
                      <input type="hidden" name="no_sizes" id="no_sizes" value="<?php echo $_GET[si]; ?>">
                      <input type="hidden" name="no_cmt" id="no_cmt" value="<?php echo $_GET[cm]; ?>">
                      <input type="hidden" name="no_colors" id="no_colors" value="<?php echo $_GET[co]; ?>">
                      <input type="hidden" name="no_inseams" id="no_inseams" value="<?php echo $_GET[in]; ?>">
    </form>
  </div>
</section>
