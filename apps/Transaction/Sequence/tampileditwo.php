<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

if ($_GET['id']){
  $where = "role_wo_master_id = '$_GET[id]'";
  $where2 = "b.role_wo_master_id = '$_GET[id]'";
  $rolewomasterid = $_GET['id'];
} else if ($_GET['d']) {
  $where = "role_wo_master_id = '$_GET[d]'";
  $where2 = "b.role_wo_master_id = '$_GET[d]'";
  $rolewomasterid = $_GET['d'];
} else {
  $where = "role_wo_master_status = 1";
  $where2 = "role_wo_master_status = 1";
}

$selgm = $con->select("laundry_role_wo_master","*","role_wo_master_status = 1");
foreach ($selgm as $gm) {}
?>        
<input type="hidden" name="role_wo_m_id" id="role_wo_m_id" value="<?=$rolewomasterid?>">
  <table class="" align="center" width="100%" >
<?php 
      $no = "A";
      $ur = 1;
      $selminmax = $con->select("laundry_role_wo","MAX(role_wo_seq) as max, MIN(role_wo_seq) as min","$where");
      foreach ($selminmax as $smm) {}

      foreach($con->select("laundry_role_wo","MAX(role_wo_name_seq) as maxnameseq","master_type_process_id = 2") as $mxns){}

      $selroleker = $con->select("laundry_role_wo a join laundry_role_wo_master b on a.role_wo_master_id = b.role_wo_master_id","*","$where2 and a.role_wo_status != 2","role_wo_seq ASC");
    //echo "select * from laundry_role_wo a join laundry_role_wo_master b on a.role_wo_master_id = b.role_wo_master_id where $where2";
      foreach ($selroleker as $roleker) {


        if ($smm['max'] == $roleker['role_wo_seq']) {
            $dis = "onclick='upedit($roleker[role_wo_id],$roleker[role_wo_seq])'";
            $das = "style='display:none'";
        } else if ($smm['min'] == $roleker['role_wo_seq']) {
            $dis = "style='display:none'";
            $das = "onclick='downedit($roleker[role_wo_id],$roleker[role_wo_seq])'";
        } else {
            $dis = "onclick='upedit($roleker[role_wo_id],$roleker[role_wo_seq])'";
            $das = "onclick='downedit($roleker[role_wo_id],$roleker[role_wo_seq])'";
        }

        $urutsequence = $roleker[role_wo_id].'_'.$ur;
?>       
 
      <tr>
        <td width="5%" align="center"> 
          <?php echo $no."."; ?>
          <input type="hidden" id="urutsequence_<?=$roleker[role_wo_id]?>" name="urutsequence[]" 
          value="<?=$urutsequence?>">
        </td>
        <td width="35%" > 
          <?php echo $roleker['role_wo_name'] ?>
        </td>
        <td width="10%">
          <?php if ($roleker['master_type_process_id'] > '3'){ $read = "readonly"; } else {$read = "";}?>
          <input type="text" class="form-control" id="mainseq_<?=$roleker[role_wo_id]?>" name="mainseq[]" placeholder="Minutes" value="<?=$roleker[role_wo_time]?>" <?=$read?> onkeyup="hitungmainedit(this.value,<?=$roleker['role_wo_id']?>,8)" onkeydown='return hanyaAngka(this, event);'> 
          <input type="hidden" class="form-control" id="mainseq_id[]" name="mainseq_id[]" value="<?=$roleker[role_wo_id]?>"> 
        </td>
        <td width="10%" align="center">
          <?php if ($roleker['master_type_process_id'] > '3'){ ?>
             <a href="javascript:void(0)" data-toggle='modal' data-target='#funModal' id='mod' onclick='modelwo(<?=$roleker['role_wo_id']?>,<?=$_GET['idm']?>,<?=$roleker['master_type_process_id']?>,<?=$_GET['d']?>,2)' class="btn btn-default" style="border-color: #8B0000;"><i class="fa fa-sort" aria-hidden="true"></i></a>
          <?php } ?>
        </td>
        <td width="40%" align="right"> 
            <?php //if($roleker['master_type_process_id'] != 2){ ?> 
                
                <a href="javascript:void(0)" class="btn btn-default" <?=$dis?>><i class="fa fa-arrow-up"></i></a>
                <a href="javascript:void(0)" class="btn btn-default" <?=$das?>><i class="fa fa-arrow-down"></i></a>
            
            <?php //} ?>
            &emsp;<a href="javascript:void(0)" data-toggle='modal' data-target='#funModal' id='mod' onclick='modelwo(<?=$roleker['role_wo_id']?>,<?=$_GET['idm']?>,<?=$roleker['master_type_process_id']?>,<?=$_GET['d']?>,0)'><i class="fa fa-pencil"></i></a>
            &emsp;
            <?php //if($roleker['master_type_process_id'] != 2 || ($roleker['master_type_process_id'] == 2 && $roleker['role_wo_name_seq'] == $mxns['maxnameseq'])){ 
            //jika max role wo name seq ?> 

                <a href="javascript:void(0)" onclick="hapusprosesedit('<?=$roleker[role_wo_id]?>','<?=$roleker[role_wo_seq]?>','<?=$roleker[role_wo_createdby]?>','<?=$_GET[d]?>','<?=$_GET[idm]?>')"><i class="fa fa-trash"></i></a>

            <?php //} ?>
        </td>
      </tr>
      <tr>
        <td colspan="5">
<?php 
              $hu = "1";
              $selminmax1 = $con->select("laundry_role_dtl_wo","COUNT(role_dtl_wo_id) as jumlah,MAX(role_dtl_wo_seq) as max, MIN(role_dtl_wo_seq) as min","role_wo_id = '$roleker[role_wo_id]' and role_dtl_wo_status = 1 and master_process_id != 42");
              foreach ($selminmax1 as $smm1) {}

                    $selroleker1 = $con->select("laundry_role_dtl_wo a join laundry_master_process b on a.master_process_id = b.master_process_id and master_process_status = 1","*","role_wo_id = '$roleker[role_wo_id]'  and role_dtl_wo_status = 1","role_dtl_wo_seq ASC");
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
                <?php echo $roleker1['master_process_name'] ?>
              </td>
              <td width="10%" align="left">
                <input type="text" class="form-control" id="seq_<?=$hu?>_<?=$roleker1[role_wo_id]?>" name="seq[]" value="<?=$roleker1[role_dtl_wo_time]?>" onkeyup="hitungedit(this.value,<?=$roleker1['role_wo_id']?>,6)" onkeydown='return hanyaAngka(this, event);'> 
                <input type="hidden" class="form-control" id="seq_id[]" name="seq_id[]" value="<?=$roleker1[role_dtl_wo_id]?>"> 
              </td>
              <td width="20%">&nbsp; Minutes</td>
              <td width="30%"> </td>
            </tr>
          </table>
          <?php $hu++; } ?>

          <input id="countdtl_<?=$roleker1['role_wo_id']?>" name="countdt[]" value="<?=$smm1[jumlah]?>" type="hidden">
        </td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;<hr></td>
      </tr>
<?php
     $no++; 
     $ur++; 
    } 
?>
  </table>