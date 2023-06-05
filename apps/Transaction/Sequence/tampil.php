<?php 
  session_start();
  include '../../../funlibs.php';
  $con = new Database;
  if ($_GET['id']){
    $where = "and role_grup_master_id = '".$_GET['id']."'";
    $where2 = "and b.role_grup_master_id = '".$_GET['id']."'";
  } else if ($_GET['d']) {
    $where = "and role_grup_master_id = '".$_GET['id']."'";
    $where2 = "and b.role_grup_master_id = '".$_GET['id']."'";
  } else {
    $where = "and role_grup_master_status = 1";
    $where2 = "and role_grup_master_status = 1";
  }

  $selgm = $con->select("laundry_role_grup_master","*","role_grup_master_status = 1");
  foreach ($selgm as $gm) {}

  //cek created by and modify by
  foreach ($con->select("m_users a join laundry_user_section b on a.user_id=b.user_id","a.user_id,username","a.user_id = '".$gm['role_grup_master_createdby']."'") as $createdby){}

  foreach ($con->select("m_users a join laundry_user_section b on a.user_id=b.user_id","a.user_id,username","a.user_id = '".$gm['role_grup_master_modifyby']."'") as $modifyby){}
    if ($modifyby['username'] != ''){
      $modif = "Modify by : ".$modifyby['username'];
    } else {
      $modif = "";
    }
  // end cek ===================

  //pengecekan apakah role grup master memiliki turunan pada role grup
  $cekrolegrup = $con->selectcount("laundry_role_grup","role_grup_id","role_grup_master_id = '".$gm['role_grup_master_id']."'");
  // end pengecekan ==================================================
?>        
<input type="hidden" name="role_grup_m_id" id="role_grup_m_id" value="<?php echo $gm[role_grup_master_id]; ?>">
<input type="hidden" name="role_grup_m_remark" id="role_grup_m_remark" value="<?php echo $gm[role_grup_master_remark]; ?>">
<input type="hidden" name="type_receive" id="type_receive" value="<?php echo $gm[type_receive]; ?>">
<table class="" align="center" width="100%"  style="font-size: 12px;">
  <?php 
    if ($cekrolegrup > 0) { ?>
      <tr>
          <td colspan="6" align="right"><i>Created by : <?php echo $createdby['username']; ?></i></td>
      </tr>

      <tr>
          <td colspan="6" align="right" style="padding-bottom:10px; "><i><?=$modif?></i></td>
      </tr>
  <?php 
    } 

      $no = "A";
      $ur = 1;
      $selminmax = $con->select("laundry_role_grup a join laundry_role_grup_master b on a.role_grup_master_id=b.role_grup_master_id","MAX(role_grup_seq) as max, MIN(role_grup_seq) as min","role_grup_createdby = '".$_SESSION['ID_LOGIN']."' $where");
      // echo "select MAX(role_grup_seq) as max, MIN(role_grup_seq) as min from laundry_role_grup a join laundry_role_grup_master b on a.role_grup_master_id=b.role_grup_master_id where role_grup_createdby = '$_SESSION[ID_LOGIN]' $where";
      foreach ($selminmax as $smm) {}

      foreach($con->select("laundry_role_grup","MAX(role_grup_name_seq) as maxnameseq","master_type_process_id = 2") as $mxns){}
            $selroleker = $con->select("laundry_role_grup a join laundry_role_grup_master b on a.role_grup_master_id = b.role_grup_master_id","*","role_grup_createdby = '".$_SESSION['ID_LOGIN']."' $where2","role_grup_seq ASC");

            foreach ($selroleker as $roleker) { 

              if ($smm['max'] == $roleker['role_grup_seq']) {
                $dis = "onclick='up($roleker[role_grup_id],$roleker[role_grup_seq])'";
                $das = "style='display:none'";
              } else if ($smm['min'] == $roleker['role_grup_seq']) {
                $dis = "style='display:none'";
                $das = "onclick='down($roleker[role_grup_id],$roleker[role_grup_seq])'";
              } else {
                $dis = "onclick='up($roleker[role_grup_id],$roleker[role_grup_seq])'";
                $das = "onclick='down($roleker[role_grup_id],$roleker[role_grup_seq])'";
              }

              if ($roleker['master_type_process_id'] == 4){
                  $displayedit = "style='display:inline'";
              } else if ($roleker['master_type_process_id'] == 5){
                  $displayedit = "style='display:inline'";
              } else {
                  $displayedit = "style='display:none'";
              }
              
              $urutsequence = $roleker[role_grup_id].'_'.$ur;
  ?>       
 
      <tr>
        <td width="5%" align="center"> 
          <?php echo $no."."; ?>
        </td>
        <td width="35%" > 
          <?php echo $roleker['role_grup_name'] ?>
        </td>
        <td width="10%">
          <?php if ($roleker['master_type_process_id'] > '3'){ $read = "readonly"; } else {$read = "";}?>
          <input type="text" class="form-control" id="mainseq_<?php echo $roleker[role_grup_id]; ?>" name="mainseq[]" placeholder="Minutes" value="<?php echo $roleker[role_grup_time]; ?>" <?=$read?> onkeyup="hitungmain(this.value,<?php echo $roleker['role_grup_id']; ?>,5)" onkeydown='return hanyaAngka(this, event);'> 
          <input type="hidden" class="form-control" id="mainseq_id[]" name="mainseq_id[]" value="<?php echo $roleker[role_grup_id]; ?>"> 
        </td>
        <td width="10%" align="center"> &emsp;
          <?php if ($roleker['master_type_process_id'] > '3' && $roleker['master_type_process_id'] != '6'){ ?>
          <!--tombol untuk ubah semua posisi sequence -->
            <a href="javascript:void(0)" data-toggle='modal' data-target='#funModal' id='mod' onclick='model(<?php echo $roleker['role_grup_id']; ?>,5,<?php echo $_GET['d']; ?>)' class="btn btn-default" style="border-color: #8B0000;"><i class="fa fa-sort" aria-hidden="true"></i></a>
          <?php } ?>
        </td>
        <td width="40%" align="right"> 
            <?php //if($roleker['master_type_process_id'] != 2){ ?> 
              
              <a href="javascript:void(0)" class="btn btn-default" <?=$dis?>><i class="fa fa-arrow-up"></i></a>
              <a href="javascript:void(0)" class="btn btn-default" <?=$das?>><i class="fa fa-arrow-down"></i></a>
            
            <?php //} ?>
            
            &emsp;<a href="javascript:void(0)" data-toggle='modal' data-target='#funModal' id='mod' onclick='model(<?php echo $roleker['role_grup_id']; ?>,2,<?php echo $_GET['d']; ?>)' <?=$displayedit?>><i class="fa fa-pencil"></i></a>
            &emsp;
            <?php //if($roleker['master_type_process_id'] != 2 || ($roleker['master_type_process_id'] == 2 && $roleker['role_grup_name_seq'] == $mxns['maxnameseq'])){ 
            //jika max role wo name seq ?> 
            
              <a href="javascript:void(0)" onclick='hapusproses(<?php echo $roleker['role_grup_id']; ?>,<?php echo $roleker['role_grup_seq']; ?>,<?php echo $roleker['role_grup_createdby']; ?>,<?php echo $_GET['d']; ?>)'><i class="fa fa-trash"></i></a>
            
            <?php //} ?>
        </td>
      </tr>
      <tr>
        <td colspan="5">
           <?php 
              $hu = "1";
              foreach ($selminmax1 = $con->select("laundry_role_dtl_grup","COUNT(role_dtl_grup_id) as jumlah,MAX(role_dtl_grup_seq) as max, MIN(role_dtl_grup_seq) as min","role_grup_id = '".$roleker[role_grup_id]."' and master_process_id != 42") as $smm1){}

                  $selroleker1 = $con->select("laundry_role_dtl_grup a join laundry_master_process b on a.master_process_id = b.master_process_id and master_process_status = 1","*","role_grup_id = '".$roleker[role_grup_id]."'","role_dtl_grup_seq ASC");
                  foreach ($selroleker1 as $roleker1) { 
                    
            ?>   
            <table cellpadding="0" cellspacing="0" width="100%">    
            <tr>
              <td width="5%" align="center">
                &nbsp;
              </td>
              <td width="5%" align="left">
                <?php echo $hu."."; ?>
              </td>
              <td width="30%" align="left" > 
                <?php echo $roleker1['master_process_name']; ?>
              </td>
              <td width="10%" align="left">
                <input type="text" class="form-control" id="seq_<?=$hu?>_<?php echo $roleker1[role_grup_id]; ?>" name="seq[]" value="<?php echo $roleker1[role_dtl_grup_time]; ?>" onkeyup="hitung(this.value,<?php echo $roleker1['role_grup_id']; ?>,3)" onkeydown='return hanyaAngka(this, event);' required> 
                <input type="hidden" class="form-control" id="seq_id[]" name="seq_id[]" value="<?php echo $roleker1[role_dtl_grup_id]; ?>"> 
              </td>
              <td width="20%">&nbsp; Minutes</td>
              <td width="30%"> 
             
              </td>
            </tr>
          </table>
        <?php $hu++; } ?>
        <input id="countdtl_<?=$roleker1['role_grup_id']?>" name="countdt[]" value="<?php echo $smm1[jumlah]; ?>" type="hidden">
        </td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;<hr></td>
      </tr>
       <?php
            $no++; } 
      ?>

  </table>