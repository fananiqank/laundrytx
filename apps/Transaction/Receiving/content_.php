<?php 

$exppage = explode('_',$_GET['p']);
$page = $exppage[0];  
$last = $exppage[1];  
// $exppage = explode('_',$_GET['p']);
// $page = $exppage[0];  
// $last = $exppage[1];  
if ($last != '') {
  //jika view
  $onc = "onClick='pindahData2(nocmt.value,nocolor.value,0,0,2)'";
} else {
  //jika bukan view
  $onc = "onClick='pindahData2(nocmt.value,nocolor.value,noinseams.value,nosizes.value,1)'";
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
if ($_GET['in'] != ''){
  $nins = $_GET['in'];
} else {
  $nins = '';
}
if ($_GET['si'] != ''){
  $nsiz = $_GET['si'];
} else {
  $nsiz = '';
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

if ($nins != ''){
  $in = "and garment_inseams = '$nins'";
} else {
  $in = "";
}

if ($nsiz != ''){
  $si = "and garment_sizes = '$nsiz'";
} else {
  $si = "";
}

?>


<section class="panel">
    <div class="panel-body">
      <a class='btn btn-warning' style='margin-bottom: 0%;' data-toggle='collapse' data-target='#demo'>Filter</a>

      <?php if($last == 'v') { ?>
          <a href="content.php?p=<?=$page?>" class='btn btn-success' style='margin-bottom: 0%;'>View Sewing Data</a>
      <?php } else { ?> 
         <!--  <a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('v')">View Receive Data</a> -->
         <!--  <span class="badge" style='margin-bottom: 0%;float: right;' id="cart">
            <?php include "cart.php";?>            
          </span>
          <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelcart()"><i class="fa fa-shopping-cart"></i></a> -->
      <?php } ?>

        <?php if ($_GET['cm'] || $_GET['co'] || $_GET['in'] || $_GET['si']){ ?>
          <div id="demo" class="collapse in" >
        <?php } else { ?>
          <div id="demo" class="collapse in" >
        <?php } ?>
          <hr>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>CMT No <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocmt" id="nocmt" class="form-control" value="<?=$ncmt;?>" required>
            </div>
            <label class="col-md-2 control-label" for="profileLastName"><b>Colors<font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocolor" id="nocolor" class="form-control" value="<?=$ncol;?>" required>
            </div>
          </div>
          <div class="form-group">
              <div class="col-md-12">
                <a href="content.php?p=<?=$_GET[p]?>" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" <?=$onc?> >
              </div>
          </div>
           <hr>
        </div>

    </header>
    
    <form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
                            <div class="row" align="center">
                            <h4><b>Receive Data</b></h4>
                            </div>
                              <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax2" style="font-size: 13px;">
                                <thead>
                                  <tr>
                                    <th width="5%">No</th>
                                    <th width="12%">No Receive</th>
                                    <th width="13%">No CMT</th>
                                    <th width="13%">Colors</th>
                                    <th width="5%">Qty Receive</th>
                                    <th width="5%">User </th>
                                    <th width="5%">Action </th>
                                      
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                        </div>
                    </div>
       
                       
                      <input id="view" name="view" value="<?=$last?>" type="hidden">
                      <input id="conf" name="conf" value="" type="hidden">
                      <input class="form-control" name="kode" id="kode" value="<?=$kode?>" type="hidden" />
                      <input class="form-control" name="getp" id="getp" value="<?=$_GET[p]?>" type="hidden" />
                      <input type="hidden" id="hal" name="hal" value="<?=$get?>">
                      <input type="hidden" name="getpage" id="getpage" value="<?=$page?>">
                      <input type="hidden" name="no_sizes" id="no_sizes" value="<?=$_GET[si]?>">
                      <input type="hidden" name="no_cmt" id="no_cmt" value="<?=$_GET[cm]?>">
                      <input type="hidden" name="no_colors" id="no_colors" value="<?=$_GET[co]?>">
                      <input type="hidden" name="no_inseams" id="no_inseams" value="<?=$_GET[in]?>">
                      <input type="hidden" name="saw" id="saw" value="<?=$_GET[saw]?>">
    </form>
  </div>
</section>
