<?php 

$exppage = explode('_',$_GET['task']);
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

if ($_GET['xty'] != ''){
  $xftydate = $_GET['xty'];
  $xtyd = $xftydate;
} else {
  $xtyd = '';
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

if ($xftydate != ''){
  $xdate = "and DATE(ex_fty_date) = '$xftydate'";
} else {
  $xdate = "";
}


?>

<section class="panel">
    <div class="panel-body">
         <div id="back">
            <?php include "back.php"; ?>     
         </div>
     
    </div>
    
    <form class="form-user" id="formku" method="post" enctype="multipart/form-data">
     
                    <div class="form-group">
                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
                            
                            <div id="halcontent">
                                <?php include "view_data.php"; ?>
                            </div>
                        </div>
                    </div>
                    
                       
                      <input id="view" name="view" value="" type="hidden">
                      <input class="form-control" name="kode" id="kode" value="<?php echo $kode; ?>" type="hidden" />
                      <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
                      <input type="hidden" name="getpage" id="getpage" value="<?php echo $page; ?>">
                      <input type="hidden" name="no_sizes" id="no_sizes" value="<?php echo $_GET[si]; ?>">
                      <input type="hidden" name="no_cmt" id="no_cmt" value="<?php echo $_GET[cm]; ?>">
                      <input type="hidden" name="no_colors" id="no_colors" value="<?php echo $_GET[co]; ?>">
                      <input type="hidden" name="no_inseams" id="no_inseams" value="<?php echo $_GET[in]; ?>">
                      <input type="hidden" name="saw" id="saw" value="<?php echo $_GET[saw]; ?>">
                      <input type="hidden" name="halreload" id="halreload" value="">
    </form>
  </div>
</section>
