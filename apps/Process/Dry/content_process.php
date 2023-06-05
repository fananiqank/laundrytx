<?php
  session_start();
  require_once("../../../funlibs.php");
  $con=new Database();
    //perpindahan menu
  $first = substr($_GET[p], 0,1);
  $last = substr($_GET[p], 1,3);    


    if ($_GET['typelot'] == 'A'){ 
    $selcmt = $con->select("laundry_wo_master_dtl_proc a 
                             JOIN laundry_receive b on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id 
                             JOIN laundry_data_wo c on a.wo_no=c.wo_no
                             JOIN laundry_role_wo d on a.role_wo_master_id=d.role_wo_master_id
                             LEFT JOIN laundry_role_dtl_wo e ON d.role_wo_id = e.role_wo_id
                             LEFT JOIN laundry_master_process f ON e.master_process_id = f.master_process_id",
                            "a.wo_master_dtl_proc_id,
                             a.wo_no,
                             a.buyer_id,
                             a.garment_colors,
                             DATE(a.ex_fty_date) as ex_fty_date,
                             a.role_wo_master_id,
                             b.rec_qty as qty,
                             b.rec_no AS lotno,
                             b.rec_createdby,
                             d.role_wo_id,
                             d.master_type_process_id,
                             d.role_wo_time,
                             d.role_wo_name_seq,
                             role_wo_name,
                             master_process_name,
                             f.master_process_id,
                             f.master_process_usemachine,
                             COALESCE(e.role_dtl_wo_id,0) as role_dtl_wo_id,
                             COALESCE(e.role_dtl_wo_time,0) as role_dtl_wo_time,
                             COALESCE(e.master_process_id,0) as master_process_id",
                            "rec_no = '".$_GET['lot']."'
                             and a.role_wo_master_id = '".$_GET['role']."' and 
                             CONCAT(
                                    a.wo_no,'_',a.garment_colors,'_',d.role_wo_id,'_',COALESCE(role_dtl_wo_id,0),'_',rec_no)
                             NOT IN (select CONCAT(
                                     wo_no,'_',garment_colors,'_',role_wo_id,'_',role_dtl_wo_id,'_',lot_no) 
                                     from laundry_process where process_type = 4)
                             ","d.role_wo_seq,e.role_dtl_wo_seq","1");
    
    } else {
      if ($_GET['mpd'] != '' ){
        $mpd = "d.role_wo_id = '".$_GET['rwid']."' and
                f.master_process_id = '".$_GET['mpd']."'";
      } else {
        $mpd = "d.role_wo_id = '".$_GET['rwid']."'";
      }
      $selcmt = $con->select("laundry_wo_master_dtl_proc a 
                             JOIN laundry_lot_number b on a.wo_master_dtl_proc_id = b.wo_master_dtl_proc_id 
                             JOIN laundry_data_wo c on a.wo_no=c.wo_no
                             JOIN laundry_role_wo d on a.role_wo_master_id=d.role_wo_master_id
                             LEFT JOIN laundry_role_dtl_wo e ON d.role_wo_id = e.role_wo_id
                             LEFT JOIN laundry_master_process f ON e.master_process_id = f.master_process_id",
                            "a.wo_master_dtl_proc_id,
                             a.wo_no,
                             a.buyer_id,
                             a.garment_colors,
                             a.role_wo_master_id,
                             DATE(a.ex_fty_date) as ex_fty_date,
                             b.lot_qty as qty,
                             b.lot_qty_good_upd as qty_upd,
                             b.lot_no AS lotno,
                             b.lot_createdby,
                             d.role_wo_id,
                             d.master_type_process_id,
                             d.role_wo_time,
                             d.role_wo_name_seq,
                             role_wo_name,
                             master_process_name,
                             f.master_process_id,
                             f.master_process_usemachine,
                             ISNULL(e.role_dtl_wo_id,0) as role_dtl_wo_id,
                             ISNULL(e.role_dtl_wo_time,0) as role_dtl_wo_time,
                             ISNULL(e.master_process_id,0) as master_process_id",
                            "lot_no = '".$_GET['lot']."'
                             and a.role_wo_master_id = '".$_GET['role']."' and
                             $mpd and
                             CONCAT(
                                    a.wo_no,'_',a.garment_colors,'_',d.role_wo_id,'_',ISNULL(role_dtl_wo_id,0),'_',lot_no)
                             NOT IN (select CONCAT(
                                     wo_no,'_',garment_colors,'_',role_wo_id,'_',role_dtl_wo_id,'_',lot_no) 
                                     from laundry_process where process_type = 4)
                             ","d.role_wo_seq,e.role_dtl_wo_seq","1");
    
    }
      foreach ($selcmt as $cmt) {}
      //cek process in pada process laundry
      $selectlog= $con->select("laundry_process","process_type,process_createdate","lot_no = '".$_GET['lot']."' and master_process_id = '".$cmt['master_process_id']."'","process_type DESC","1");
      // echo "select process_type,process_createdate from laundry_process where lot_no = '$_GET[lot]' and master_process_id = '$cmt[master_process_id]'";
      foreach ($selectlog as $slog) {}

      //cek process on process laundry
      $cekprocess = $con->selectcount("laundry_process","process_id",
                                 "process_status = 1 and lot_no = '".$_GET['lot']."' and master_process_id = '".$cmt['master_process_id']."' and role_wo_id = '".$cmt['role_wo_id']."' and role_dtl_wo_id = '".$cmt['role_dtl_wo_id']."'");
     
      if ($cmt['master_process_usemachine'] == 0) {
          $displaynone = 'display:none;';
      } else {
          $displaynone = '';
      }
    ?>

  <div class="panel-body">
   <img src="assets/images/go-back.png" width="5%" onclick="back('process')">
   <!--  <a href="javascript:void(0)" class="btn btn-success" style="float: right">Next</a> -->
    
    <br><br>
    <form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
        <a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick="model('<?=$cmt[master_process_id]?>','<?=$cmt[lotno]?>','<?=$_GET[usercode]?>',1,'<?=$cmt[role_wo_id]?>')" class='btn btn-warning' style='font-size:12px;background-color:#0000FF;float: right;<?=$displaynone?>' >Add Machine</a>
	    <div class="form-group"  style="font-size: 14px;" >
	        <label class="col-md-2 control-label" for="profileLastName"><b>PROCESS :</b></label>
	          <div class="col-md-4">
	            <?php 
	                  if ($cmt['master_type_process_id'] != '') {
	                      echo "<b>".$cmt['master_process_name']."</b>";
	            ?>
	                      <input id='rolewoid' name='rolewoid' value='<?=$cmt[role_wo_id]?>' type='hidden'>
	                      <input id='roledtlwoid' name='roledtlwoid' value='<?=$cmt[role_dtl_wo_id]?>' type='hidden'>
	                      <input id='masterprocessid' name='masterprocessid' value='<?=$cmt[master_process_id]?>' type='hidden'>
	                      <input id='mastertypeprocessid' name='mastertypeprocessid' value='<?=$cmt[master_type_process_id]?>' type='hidden'>
                        <input id='rolewonameseq' name='rolewonameseq' value='<?=$cmt[role_wo_name_seq]?>' type='hidden'>
	            <?php 
	                  } else{
	                      echo "<b>".$cmt['role_wo_name']."</b>";
	            ?>
	                      <input id='rolewoid' name='rolewoid' value='<?=$cmt[role_wo_id]?>' type='hidden'>
	                      <input id='mastertypeprocessid' name='mastertypeprocessid' value='<?=$cmt[master_type_process_id]?>' type='hidden'>
                        <input id='rolewonameseq' name='rolewonameseq' value='<?=$cmt[role_wo_name_seq]?>' type='hidden'>
	            <?php
	                  }
	            ?>
	            <input id="datedetail" name="datedetail" value="<?=date('Y-m-d');?>" type="hidden">
	          </div>
	        <br><br>
	    </div>
        <div class="form-group"  style="font-size: 12px;">
            <label class="col-md-2 control-label" for="profileLastName"><b>Date :</b></label>
            <div class="col-md-4">
              <?php echo date('d-m-Y'); ?>
              <input id="date-process" name="date-process" value="<?=date('Y-m-d');?>" type="hidden">
            </div>
          <label class="col-md-2 control-label" for="profileLastName"><b>Lot Number :</b></label>
            <div class="col-md-4">
              <?php echo $cmt['lotno'];?>
              <input id="lot-no" name="lot-no" value="<?=$cmt[lotno]?>" type="hidden">
            </div>
        </div>
        <div class="form-group"  style="font-size: 12px;">
            <label class="col-md-2 control-label" for="profileLastName"><b>WO No :</b></label>
            <div class="col-md-4">
              <?php echo $cmt['wo_no'];?>
              <input id="wo-no" name="wo-no" value="<?=$cmt[wo_no]?>" type="hidden">
            </div>
          <label class="col-md-2 control-label" for="profileLastName"><b>Colors :</b></label>
            <div class="col-md-4">
              <?php echo $cmt['garment_colors'];?>
              <input id="colors" name="colors" value="<?=$cmt[garment_colors]?>" type="hidden">
            </div>
        </div>
        <div class="form-group"  style="font-size: 12px;">
            <label class="col-md-2 control-label" for="profileLastName"><b>Ex Fty Date :</b></label>
            <div class="col-md-4">
              <?php echo date('d-m-Y', strtotime($cmt['ex_fty_date']));?>
              <input id="ex_fty_date" name="ex_fty_date" value="<?=$cmt[ex_fty_date]?>" type="hidden">
              <input id="buyer-id" name="buyer-id" value="<?=$cmt[buyer_id]?>" type="hidden">
            </div>
          <label class="col-md-2 control-label" for="profileLastName"><b>Qty :</b></label>
            <div class="col-md-4">
              <?php echo $_GET['q'];?>
              <input id="qty" name="qty" value="<?=$_GET[q]?>" type="hidden">
            </div>
        </div>
        <div class="form-group"  style="font-size: 12px;">
            <label class="col-md-2 control-label" for="profileLastName"><b>User :</b></label>
            <div class="col-md-4">
              <?php echo $_GET['usercode'];?>
              <input id="user-id" name="user-id" value="<?php echo $_SESSION[ID_LOGIN]?>" type="hidden">
              <input id="usercode" name="usercode" value="<?php echo $_GET[usercode]?>" type="hidden">
            </div>
            <label class="col-md-2 control-label" for="profileLastName"><b>Time :</b></label>
            <div class="col-md-4">
              <?php
                  if ($cmt['role_dtl_wo_time'] != '0') {
                        echo "<b>".$cmt['role_dtl_wo_time']."</b>";
                        echo "<input id='time' name='time' value='$cmt[role_dtl_wo_time]' type='hidden'>";
                  } else {
                        echo "<b>Time Not Set</b>";
                        echo "<input id='time' name='time' value='$cmt[role_dtl_wo_time]' type='hidden'>";
                  }
              ?>
              Minutes
            </div>
             
        </div>
        <hr>
        <div class="form-group" align="center" id="tampilprocess">
          <?php  
                if ($cekprocess == 0) {
                    include "process_in.php"; 
                } else {
                    include "process_start.php";
                }
          ?>
        </div>  
        
        <input type="hidden" name="process-status" id="process-status" value="">
        <input type="hidden" name="wo-master-dtl-proc-id" id="wo-master-dtl-proc-id" value="<?=$cmt[wo_master_dtl_proc_id]?>">  
        <input type="hidden" name="type_lot" id="type_lot" value="<?=$_GET[typelot]?>"> 
        <input type="hidden" name="role-wo-master-id" id="role-wo-master-id" value="<?=$cmt[role_wo_master_id]?>">  
        <input class="form-control" name="machine-input" id="machine-input" value="" type="hidden" />
        <input type="hidden" class="form-control" id="machine_id" name="machine_id">
    </form>

