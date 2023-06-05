<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;
?>                              
                      <table class="" align="center" width="100%">
                        <?php 
                            $no = "A";
                            $ur = 1;
                            $selminmax = $con->select("laundry_role_grup","MAX(role_grup_seq) as max, MIN(role_grup_seq) as min,role_grup_id","role_grup_user_id = '$_SESSION[ID_LOGIN]'");
                            //echo "select MAX(role_grup_seq) as max, MIN(role_grup_seq) as min from laundry_role_grup where role_grup_user_id = '$_SESSION[ID_LOGIN]'";
                            foreach ($selminmax as $smm) {}
                                  $selroleker = $con->select("laundry_role_grup a join laundry_role_grup_master b on a.role_grup_master_id = b.role_grup_master_id","*","role_grup_user_id = '$_SESSION[ID_LOGIN]' and role_grup_master_status = 1","role_grup_seq ASC");
                                  foreach ($selroleker as $roleker) { 
                                    if ($smm['max'] == $roleker['role_grup_seq']) {
                                      echo "a";
                                      $dis = "onclick='up($roleker[role_grup_id],$roleker[role_grup_seq])'";
                                      $das = "style='display:none'";
                                    } else if ($smm['min'] == $roleker['role_grup_seq']) {
                                      echo "b";
                                      $dis = "style='display:none'";
                                      $das = "onclick='down($roleker[role_grup_id],$roleker[role_grup_seq])'";
                                    } else {
                                      $dis = "onclick='up($roleker[role_grup_id],$roleker[role_grup_seq])'";
                                      $das = "onclick='down($roleker[role_grup_id],$roleker[role_grup_seq])'";
                                    }
                            ?>       
                       
                            <tr>
                              <td width="5%" align="center"> 
                                <?php echo $no."."; ?>
                              </td>
                              <td width="35%" > 
                                <?php echo $roleker['role_grup_name'] ?>
                              </td>
                              <td width="10%">
                                <?php if ($roleker['role_grup_jenis'] > '3'){ $read = "readonly"; } else {$read = "";}?>
                                <input type="text" class="form-control" id="mainseq_<?=$roleker[role_grup_id]?>" name="mainseq[]" value="<?=$roleker[role_grup_time]?>" <?=$read?> onkeyup="hitungmain(this.value,<?=$roleker['role_grup_id']?>,5)"> 
                                <input type="hidden" class="form-control" id="mainseq_id[]" name="mainseq_id[]" value="<?=$roleker[role_grup_id]?>"> 
                              </td>
                              <td width="10%">&nbsp; </td>
                              <td width="40%" align="right"> 
                                  <a href="javascript:void(0)" class="btn btn-default" <?=$dis?>><i class="fa fa-arrow-up"></i></a>
                                 
                                  <a href="javascript:void(0)" class="btn btn-default" <?=$das?>><i class="fa fa-arrow-down"></i></a>
                                 
                                  &emsp;<a href="javascript:void(0)" data-toggle='modal' data-target='#funModal' id='mod' onclick='model(<?=$roleker[role_grup_id]?>,2)'><i class="fa fa-pencil"></i></a>
                                  &emsp;<a href="javascript:void(0)" onclick='hapusproses(<?=$roleker[role_grup_id]?>,<?=$roleker[role_grup_seq]?>,<?=$roleker[role_grup_user_id]?>)'><i class="fa fa-trash"></i></a>
                                 
                              </td>
                            </tr>
                            <tr>
                              <td colspan="5">
                                 <?php 
                                    $hu = "1";
                                    $selminmax1 = $con->select("laundry_role_dtl_grup","COUNT(role_dtl_grup_id) as jumlah,MAX(role_dtl_grup_seq) as max, MIN(role_dtl_grup_seq) as min","role_grup_id = '$roleker[role_grup_id]'");
                                    foreach ($selminmax1 as $smm1) {}
                                          $selroleker1 = $con->select("laundry_role_dtl_grup a join laundry_master_process b on a.master_process_id = b.master_process_id","*","role_grup_id = '$roleker[role_grup_id]'","role_dtl_grup_seq ASC");
                                          foreach ($selroleker1 as $roleker1) { 
                                            if ($smm1['max'] == $roleker1['role_dtl_grup_seq']) {
                                              if ($smm1['jumlah'] <= 1){
                                                $dis1 = "style='display:none'";
                                                $das1 = "style='display:none'";
                                              } else {
                                                $dis1 = "onclick='up($roleker1[role_dtl_grup_id],$roleker1[role_dtl_grup_seq],$roleker1[role_grup_id])'";
                                                $das1 = "style='color:#FFFFFF;cursor:not-allowed'";
                                              }
                                            } else if ($smm1['min'] == $roleker1['role_dtl_grup_seq']) {
                                              if ($smm1['jumlah'] <= 1){
                                                $dis1 = "style='display:none'";
                                                $das1 = "style='display:none'";
                                              } else {
                                                $dis1 = "style='color:#FFFFFF;cursor:not-allowed'";
                                                $das1 = "onclick='down($roleker1[role_dtl_grup_id],$roleker1[role_dtl_grup_seq],$roleker1[role_grup_id])'";
                                              }
                                            } else {
                                              $dis1 = "onclick='up($roleker1[role_dtl_grup_id],$roleker1[role_dtl_grup_seq],$roleker1[role_grup_id])'";
                                              $das1 = "onclick='down($roleker1[role_dtl_grup_id],$roleker1[role_dtl_grup_seq],$roleker1[role_grup_id])'";
                                            }
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
                                    <input type="text" class="form-control" id="seq_<?=$hu?>_<?=$roleker1[role_grup_id]?>" name="seq[]" value="<?=$roleker1[role_dtl_grup_time]?>" onkeyup="hitung(this.value,<?=$roleker1['role_grup_id']?>,3)"> 
                                    <input type="hidden" class="form-control" id="seq_id[]" name="seq_id[]" value="<?=$roleker1[role_dtl_grup_id]?>"> 
                                  </td>
                                  <td width="20%">&nbsp; Minutes</td>
                                  <td width="30%"> 
                                      <a href="javascript:void(0)" class="btn btn-default" <?=$dis1?>><i class="fa fa-arrow-up"></i></a>
                                     
                                      <a href="javascript:void(0)" class="btn btn-default" <?=$das1?>><i class="fa fa-arrow-down"></i></a>
                                     
                                     
                                  </td>

                                </tr>
                              </table>
                              <?php $hu++; } ?>
                              <input id="countdtl_<?=$roleker1['role_grup_id']?>" name="countdt[]" value="<?=$smm1[jumlah]?>" type="hidden">
                              </td>
                            </tr>
                            <tr>
                              <td colspan="5">&nbsp;<hr></td>
                            </tr>
                             <?php
                                  $no++; } 
                            ?>

                        </table>