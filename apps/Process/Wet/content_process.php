<?php
  session_start();
  require_once("../../../funlibs.php");
  $con=new Database();
    //perpindahan menu
  $first = substr($_GET[p], 0,1);
  $last = substr($_GET[p], 1,3);    
  $expparent = explode('_',$_GET['parent']);
  $lotparent = $expparent[0];
  $trueparent = $expparent[1];

   $selectuser = $con->select("m_users a join laundry_user_section b on a.user_id=b.user_id","a.user_id,username","a.user_id = '".$_SESSION['ID_LOGIN']."'");

  if ($trueparent == 1){
      $lotnum = $lotparent;
  } else {
      $lotnum = $_GET['lot'];
  }

  foreach ($selectuser as $user) {} 
  $tablerole1 = " laundry_wo_master_dtl_proc F 
                  JOIN laundry_lot_number G on F.wo_master_dtl_proc_id = G.wo_master_dtl_proc_id 
                  JOIN laundry_data_wo H on F.wo_no=H.wo_no 
                  JOIN laundry_role_child J on G.lot_no=J.lot_no 
                  JOIN laundry_role_wo A ON J.role_wo_id=A.role_wo_id 
                  LEFT JOIN laundry_role_dtl_wo B ON J.role_dtl_wo_id = B.role_dtl_wo_id
                  LEFT JOIN laundry_master_process C ON B.master_process_id = C.master_process_id
                  LEFT JOIN (
                      SELECT
                        role_wo_id,
                        master_process_id,
                        role_dtl_wo_id,
                        process_type,
                        lot_no
                      FROM
                        laundry_process 
                      WHERE
                        role_wo_master_id = '".$_GET['role']."'  
                        and lot_no = '".$_GET['lot']."'
                        AND master_type_process_id between 4 and 5
                      GROUP BY
                        role_dtl_wo_id,
                        role_wo_id,
                        master_process_id,
                        process_type,
                        lot_no 
                      ORDER BY
                        process_type DESC 
                  ) AS E ON E.role_dtl_wo_id = B.role_dtl_wo_id";

  $fieldrole1 = "DISTINCT b.master_process_id,
            A.role_wo_name,
            master_process_name,
            A.role_wo_id,
            A.role_wo_name_seq,
            B.role_dtl_wo_id,
            E.lot_no AS dtl_lot_number,
            B.role_dtl_wo_time,
            A.role_wo_seq,
            B.role_dtl_wo_seq,
            A.master_type_process_id,
            C.master_process_usemachine,
            B.role_dtl_wo_time,
            A.role_wo_time,
            F.wo_master_dtl_proc_id,
            F.wo_no,
            F.buyer_id,
            F.garment_colors,
            F.role_wo_master_id,
            G.lot_qty,
            G.lot_no AS lotno,
            G.lot_createdby,
            J.role_wo_seq,
            J.role_dtl_wo_seq,
            to_char(F.ex_fty_date,'DD-MM-YYYY') as ex_fty_date";

$whererole1 =  "A.role_wo_master_id = '".$_GET['role']."' AND J.lot_no = '".$_GET['lot']."' AND
          concat(E.lot_no,'_',E.role_wo_id,'_',B.master_process_id) NOT IN (select concat(lot_no,'_',role_wo_id,'_',master_process_id) from laundry_process where process_type = 4) AND 
          CONCAT(B.master_process_id,'_',A.role_wo_id) NOT IN  (
              select CONCAT(COALESCE(master_process_id,0),'_',role_wo_id) as master_process_id
            from laundry_process 
            where role_wo_master_id = '".$_GET['role']."' and lot_type = 1
            GROUP BY master_process_id,role_wo_id) AND 
          CONCAT(A.master_type_process_id,'_',A.role_wo_id,'_',A.role_wo_seq)
              NOT IN  (
              select CONCAT(COALESCE(master_type_process_id,0),'_',role_wo_id,'_',A.role_wo_seq) as master_type_process_id
            from laundry_process 
            where role_wo_master_id = '".$_GET['role']."' and lot_type = 1)";
    //echo "select $fieldrole1 from $tablerole1 where $whererole1";
    $selcmt = $con->select($tablerole1,$fieldrole1,$whererole1,"J.role_wo_seq,J.role_dtl_wo_seq","1");
    foreach ($selcmt as $cmt) {}

      //cek process in pada process laundry
      $selectlog= $con->select("laundry_process","process_type,process_createdate","lot_no = '".$_GET['lot']."' and master_process_id = '".$cmt['master_process_id']."'","process_type DESC","1");
      
      foreach ($selectlog as $slog) {}
      //explode get parent lot
        $expparlot = explode("_",$_GET['parent']);
        $parentlot = $expparlot[0];

      //jika parent lot tidak sama dengan get lot maka parent digunakan sebagai parameter cek process
        if ($parentlot != $_GET['lot']){
              $lotnumber = $parentlot;
        } 
      //jika tidak maka get lot sebagai parameter cek process
        else {
              $lotnumber = $_GET['lot'];
        }

      //cek process on process laundry
      $cekprocess = $con->selectcount("laundry_process","process_id",
                                 "process_status = 1 and lot_no = '$lotnumber' and master_process_id = '".$cmt['master_process_id']."' and role_wo_id = '".$cmt['role_wo_id']."' and role_dtl_wo_id = '".$cmt['role_dtl_wo_id']."'");
    //  echo "select process_id from laundry_process where process_status = 1 and lot_no = '$_GET[lot]' and master_process_id = '$cmt[master_process_id]' and role_wo_id = '$cmt[role_wo_id]' and role_dtl_wo_id = '$cmt[role_dtl_wo_id]'";
      if ($cmt['master_process_usemachine'] == 0) {
          $displaynone = 'display:none;';
      } else {
          $displaynone = '';
      }
    ?>

  <div class="panel-body">
    <!-- <a href="javascript:void(0)" class="btn btn-info" onclick="back('process')">Back</a> -->
   <!--  <a href="javascript:void(0)" class="btn btn-success" style="float: right">Next</a> -->
    <br><br>
    <form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
        <a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' onclick="model('<?=$cmt[master_process_id]?>','<?=$cmt[lotno]?>','<?=$_GET[user]?>',1,'<?=$cmt[role_wo_id]?>')" class='btn btn-warning' style='font-size:12px;background-color:#0000FF;float: right;<?=$displaynone?>' >Add Machine</a>
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
              <?php echo $_GET['lot'];?>
              <input id="lot-no" name="lot-no" value="<?=$_GET[lot]?>" type="hidden">
            </div>
        </div>
        <div class="form-group"  style="font-size: 12px;">
          <label class="col-md-2 control-label" for="profileLastName"><b>WO No :</b></label>
            <div class="col-md-4">
              <?php echo $cmt['wo_no'];?>
              <input id="wo-no" name="wo-no" value="<?=$cmt[wo_no]?>" type="hidden">
            </div>
          <label class="col-md-2 control-label" for="profileLastName"><b>Qty :</b></label>
            <div class="col-md-4">
              <?php echo $_GET['q'];?>
              <input id="qty" name="qty" value="<?=$_GET[q]?>" type="hidden">
            </div>
        </div>
        <div class="form-group"  style="font-size: 12px;">
          <label class="col-md-2 control-label" for="profileLastName"><b>Colors :</b></label>
            <div class="col-md-4">
              <?php echo $cmt['garment_colors'];?>
              <input id="colors" name="colors" value="<?=$cmt[garment_colors]?>" type="hidden">
            </div>
          <label class="col-md-2 control-label" for="profileLastName"><b>Time :</b></label>
            <div class="col-md-4">
              <?php
                  if ($cmt['role_dtl_wo_time'] != '0') {
                        echo "<b>".$cmt['role_dtl_wo_time']."</b>";
                        echo "<input id='time' name='time' value='$cmt[role_dtl_wo_time]' type='hidden'>";
                  } else {
                        echo "<b>".$cmt['role_wo_time']."</b>";
                        echo "<input id='time' name='time' value='$cmt[role_wo_time]' type='hidden'>";
                  }
              ?>
              Minutes
            </div>
        </div>
        <div class="form-group"  style="font-size: 12px;">
          <label class="col-md-2 control-label" for="profileLastName"><b>Ex Fty Date :</b></label>
            <div class="col-md-4">
              <?php echo $cmt['ex_fty_date'];?>
              <input id="ex-fty-date" name="ex-fty-date" value="<?=$cmt[ex_fty_date]?>" type="hidden">
            </div>
          <label class="col-md-2 control-label" for="profileLastName"><b>User :</b></label>
            <div class="col-md-4">
              <?php echo $_GET['usercode'];?>
              <input id="user-id" name="user-id" value="<?php echo $_SESSION[ID_LOGIN]?>" type="hidden">
              <input id="usercode" name="usercode" value="<?php echo $_GET[usercode]?>" type="hidden">
            </div>
             
        </div>
        <div>
          <label class="col-md-2 control-label" for="profileLastName"><b>Buyer :</b></label>
            <div class="col-md-4">
              <?php echo $cmt['buyer_id'];?>
              <input id="buyer-id" name="buyer-id" value="<?=$cmt[buyer_id]?>" type="hidden">
            </div>
          <label class="col-md-2 control-label" for="profileLastName">&nbsp;</label>
            <div class="col-md-4">
              &nbsp;
            </div>
        </div>
        <hr>
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
        <input type="hidden" name="machine-input" id="machine-input" value="" />
        <input type="hidden" name="machine_id" id="machine_id" >
    </form>

