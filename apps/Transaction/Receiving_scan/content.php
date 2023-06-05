<?php 
  
//   if ($_GET['cm'] != ''){
//   $cmt = $con->searchseqcmt($_GET['cm']);
//   $ncmt = $_GET['cm'];
// } else {
//   $ncmt = '';
// }
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

if ($_GET['cm'] != ''){
  $cm = "and wo_no = '$_GET[cm]'";
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
    <div class="panel-body">
      <a class='btn btn-warning' style='margin-bottom: 0%;' data-toggle='collapse' data-target='#demo'>Filter</a>
      <div id="demo" class="collapse in" >
          <hr>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>WO No <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocmt" id="nocmt" class="form-control" value="<?php echo $_GET[cm]; ?>" required>
            </div>
            <label class="col-md-2 control-label" for="profileLastName"><b>Colors<font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocolor" id="nocolor" class="form-control" value="<?php echo $colors; ?>" required>
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
                <a href="content.php?option=Transaction&task=receiving_scan2&act=ugr_transaction" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" onClick='pindahData2(nocmt.value,nocolor.value)'>
              </div>
          </div>
           <hr>
        </div>
      <div class="form-group">
          <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
              <div class="row">
                <div class="col-sm-1"> 
                  <b> User :</b>
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="usercod" id="usercod" placeholder="fill user">
                </div>
                
              </div>
              <hr>
              <div class="row" align="center">
                <h4><b>Receive Data</b></h4>
              </div>
                <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" id="datatable-ajax2" width="100%">
                    <thead>
                      <tr >
                        <th width="20%">Wo No</th>
                        <th width="20%">Colors</th>
                        <th width="10%">Color Wash</th>
                        <th width="10%">Ex Fty Date</th>
                        <th width="10%">GDPBatch</th>
                        <th width="10%">Qty</th>
                        <th width="10%">Scan Date</th>
                        <th width="10%">User</th>
                        <th width="15%">Create Lot</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
          </div>
          
      </div>
   
      <input id="conf" name="conf" value="" type="hidden">
      <input class="form-control" name="kode" id="kode" value="<?=$kode?>" type="hidden" />
      <input class="form-control" name="getp" id="getp" value="option=<?=$_GET[option]?>&task=<?=$_GET[task]?>&act=<?=$_GET[act]?>" type="hidden" />
      <input type="hidden" name="getpage" id="getpage" value="<?=$page?>">
   
  </div>
</section>
