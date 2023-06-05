<?php 
require( '../../../funlibs.php' );
$con=new Database;
session_start();
if ($_GET['type'] == 'A'){
  foreach ($con->select("laundry_receive a JOIN m_users b on a.rec_createdby=b.user_id","a.rec_no as lot_no,a.wo_master_dtl_proc_id,a.rec_break_status as statuslot,a.user_code,a.rec_createdate as createdate,b.username","rec_id = '".$_GET['id']."'") as $lotnum){}
} else {
  foreach ($con->select("laundry_lot_number a JOIN m_users b on a.lot_createdby=b.user_id","a.lot_no as lot_no,a.wo_master_dtl_proc_id,a.reject_from_lot,a.lot_type,a.lot_status as statuslot,a.lot_parent,a.user_code,a.lot_createdate as createdate,b.username","lot_id = '".$_GET['id']."'") as $lotnum){}

  //cek apakah lot ini sebagai parent (jika lot combine atau split) 
  $cekparent = $con->selectcount("laundry_lot_number_dtl","lot_id","lot_id_parent = '".$_GET['id']."'");  
  $cekchild = $con->selectcount("laundry_lot_number_dtl","lot_id","lot_id = '".$_GET['id']."'");
  foreach($con->select("laundry_lot_number_dtl","lot_id,create_type","lot_id_parent = '".$_GET['id']."'") as $parentlotid){} 
  foreach($con->select("laundry_lot_number_dtl","lot_id_parent,create_type","lot_id = '".$_GET['id']."'") as $childlotid){}  
  // ==============================================================
}

$selproc = $con->select("laundry_wo_master_dtl_proc","*","wo_master_dtl_proc_id = '".$lotnum['wo_master_dtl_proc_id']."'");

foreach ($selproc as $pro) {}


?>
<br>
<div class="form-group" style="font-size: 14px;">
    <div class="col-sm-4 col-md-4 col-lg-4">
        <b>Lot No :</b>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8">
        <b><?php echo $lotnum['lot_no']; ?><a href='javascript:void(0)' class="btn btn-primary" data-toggle='modal' data-target='#funModal' id='mod'  onclick="modelcekloths('<?=$lotnum[lot_no]?>')" style="float: right"><i class="fa fa-list"></i></a></b>
    </div>
    <!-- <div class="col-sm-2 col-md-2 col-lg-2">
        <a href="javascript:void(0)" class="btn btn-warning" data-toggle='modal' data-target='#funModal' id='mod' onclick='modelview(<?=$pro['role_wo_master_id']?>)'>Role Process</a>
    </div> -->
    <div class="col-sm-4 col-md-4 col-lg-4">
        <b>WO No :</b>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8">
        <b><?php echo $pro['wo_no']; ?></b>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4">
        <b>Color EIS :</b>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8">
        <b><?php echo $pro['garment_colors']; ?></b>
    </div>
     <div class="col-sm-4 col-md-4 col-lg-4">
        <b>Color Wash :</b>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8">
        <b><?php echo $pro['color_wash']; ?>&nbsp;</b>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4">
        <b>Created By :</b>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8">
        <b><?php echo $lotnum['user_code']." - ".$lotnum['createdate']; ?></b>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4">
        <b>Created Login :</b>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8">
        <b><?php echo $lotnum['username']; ?></b>
    </div>
<!-- jika lot reject -->
    <?php 
      if($lotnum['reject_from_lot'] != '' && $lotnum['lot_type'] == 'R') { 
      $judulreject = "Reject";
    ?>
                <div class="col-sm-12 col-md-12 col-lg-12">
                  &nbsp;
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4">
                    <b>Reject From :</b>
                </div>
                <div class="col-sm-8 col-md-8 col-lg-8">
                    <b><?php echo $lotnum['reject_from_lot']; ?></b>
                </div>
    <?php } ?>
<!-- end jika lot reject -->

<!-- jika lot Scrap -->
    <?php 
      if($lotnum['reject_from_lot'] != '' && $lotnum['lot_type'] == 'S') { 
      $judulreject = "Scrap";
    ?>
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    &nbsp;
                  </div>
                  <div class="col-sm-4 col-md-4 col-lg-4">
                      <b>Scrap From :</b>
                  </div>
                  <div class="col-sm-8 col-md-8 col-lg-8">
                      <b><?php echo $lotnum['reject_from_lot']; ?></b>
                  </div>
    <?php } ?>
<!-- end jika lot scrap -->

<!-- jika lot rework -->
    <?php   
      if($lotnum['reject_from_lot'] != '' && $lotnum['lot_type'] == 'W') { 
      $judulreject = "Rework";
    ?>
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    &nbsp;
                  </div>
                  <div class="col-sm-4 col-md-4 col-lg-4">
                      <b>Rework From :</b>
                  </div>
                  <div class="col-sm-8 col-md-8 col-lg-8">
                      <b><?php echo $lotnum['reject_from_lot']; ?></b>
                  </div>
    <?php } ?>
<!-- end jika lot rework -->

<!-- Jika Sebagai Parent Combine/Split Lot-->
    <?php if($cekparent > 0){ 
      foreach ($con->select("laundry_lot_event",
                            "event_type,
                             CASE 
                                  WHEN event_type = 2 
                                  THEN 'Split Lot'
                                  WHEN event_type = 3
                                  THEN 'Combine Lot' 
                             END as remarkevent",
                             "lot_no = '".$_GET['lot']."'","event_id") as $event) {}
      if ($event['remarkevent'] == ''){
        $reason = "style='display:none'";
      } else {
        $reason = "";
      }
    ?>
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    &nbsp;
                  </div>
                  <div class="col-sm-4 col-md-4 col-lg-4" <?php echo $reason; ?>>
                      <b> Reason :</b>
                  </div>
                  <div class="col-sm-8 col-md-8 col-lg-8" <?php echo $reason; ?>>
                      <b><?php echo $event['remarkevent']; ?></b>
                  </div>
                  <div class="col-sm-4 col-md-4 col-lg-4">
                      <b> Child :</b>
                  </div>
                  <div class="col-sm-8 col-md-8 col-lg-8">
                      <b>
                        <?php 
                            $sellotparent = $con->select("laundry_lot_number_dtl a join laundry_lot_number b on a.lot_id=b.lot_id","b.lot_no","a.lot_id_parent = '".$_GET['id']."'");
                            //echo "select a.lot_no from laundry_lot_number_dtl a join laundry_lot_number b on a.lot_id=b.lot_id where ";
                            foreach ($sellotparent as $lotparent){
                                echo $lotparent['lot_no']."<br>";
                            }
                        ?>
                        
                      </b>
                  </div>
                  
    <?php } ?>
<!-- end jika sebagai parent combine/split lot-->
<!-- Jika Sebagai Child Combine/Split Lot-->
    <?php if($cekchild > 0 && $lotnum['statuslot'] == '0' && $_GET['type'] != 'S' && $_GET['type'] != 'W'){ 
      foreach ($con->select("laundry_lot_event",
                            "event_type,
                             CASE 
                                  WHEN event_type = 2 
                                  THEN 'Split Lot'
                                  WHEN event_type = 3
                                  THEN 'Combine Lot' 
                             END as remarkevent",
                             "lot_no = '".$_GET['lot']."'","event_id") as $event) {}
      if ($event['remarkevent'] == ''){
        $reason = "style='display:none'";
      } else {
        $reason = "";
      }
    ?>
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    &nbsp;
                  </div>
                  <div class="col-sm-4 col-md-4 col-lg-4" <?php echo $reason; ?>>
                      <b> Reason :</b>
                  </div>
                  <div class="col-sm-8 col-md-8 col-lg-8" <?php echo $reason; ?>>
                      <b><?php echo $event['remarkevent']; ?></b>
                  </div>
                  <div class="col-sm-4 col-md-4 col-lg-4">
                      <b> Child From :</b>
                  </div>
                  <div class="col-sm-8 col-md-8 col-lg-8">
                      <b>
                        <?php 
                            $sellotparent = $con->select("laundry_lot_number","lot_no","lot_id = '".$childlotid['lot_id_parent']."'");
                            foreach ($sellotparent as $lotparent){
                                echo $lotparent['lot_no']."<br>";
                            }
                        ?>
                        
                      </b>
                  </div>
                  
    <?php } ?>
<!-- end jika sebagai parent combine/split lot-->
</div>
<HR>

<?php if ($judulreject == ''){ $judulreject = 'Reject'; } else { $judulreject = $judulreject; } ?>

  <div class="col-sm-12 col-md-12 col-lg-12">
    <div class="col-sm-3 col-md-3 col-lg-3">
      Process :
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6">
      <input id="carilot" name="carilot" value="" type="text" class="form-control">
      <input id="proc_id" name="proc_id" value="<?php echo $_GET[id]; ?>" type="hidden" class="form-control">
      <input type="hidden" id="lotno" name="lotno" value="<?php echo $_GET[lot]; ?>">
      <input type="hidden" id="type" name="type" value="<?php echo $_GET[type]; ?>">
      <input type="hidden" id="womasterprocid" name="womasterprocid" value="<?php echo $_GET[womasterprocid]; ?>">
    </div>
    <div class="col-sm-3 col-md-3 col-lg-3">
      <a href="javascript:void(0)" class="btn btn-primary" onClick="caridatalot(carilot.value)">Go</a>
    </div>
    <br><br>
    <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer pre-scrollable" width="100%" id="datatable-ajax2" style="font-size: 13px;">
        <thead>
          <tr>
            <th width="5%">No</th>
            <th width="25%">Process</th>
            <th width="10%">Good</th>
            <!-- <th width="15%"><?=$judulreject?></th> -->
            <th width="10%">Rjk</th>
            <th width="10%">Rwk</th>
            <th width="10%">Status</th>
          </tr>
        </thead>
        <tbody id="isidetaillot">
          <?php include "view_detail_lot_isi.php"; ?>
        </tbody>
    </table>
  </div>


