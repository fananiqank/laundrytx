<?php 
  
if ($_GET['d']){
    session_start();
    include "../../../funlibs.php";
    $con = new Database();
}

$selectuser = $con->select("m_users a join laundry_user_section b on a.user_id=b.user_id join laundry_master_type_process c on b.master_type_process_id = c.master_type_process_id","a.user_id,username,c.master_type_process_name","a.user_id = '$_SESSION[ID_LOGIN]'");
  foreach ($selectuser as $user) {}    
    
  $typescan = 3;
  $section = "QC Inspection";
  $defect = "WAS2";

$seldefect = $con->select("qrcode_defect_master","*","workcenter_id = '$defect'");

?>
<header>
          <table width="100%" style="font-size: 14px;">
            <tr>
                <td width="30%">
                    <table class="table datatable-basic table-hover dataTable no-footer" width="100%">
                        <tr>
                            <td width="30%">
                              <b>User</b>
                            </td>
                            <td>
                              <b>: <?php echo $user['username']; ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                              <b>Section</b>
                            </td>
                            <td>
                              <b>: <?php echo $section; ?></b> 
                            </td>
                        </tr>
                        <tr>
                            <td>
                              <b>Type Scan</b>
                            </td>
                            <td>
                              
                                <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="typescan" id="typescan" onclick="typescan(this.value)" required>
                                  <option value="">Choose one</option>
                                  <option value="1"><b>Good</b></option>
                                  <option value="2"><b>Reject</b></option>   
                                  <option value="3"><b>Rework</b></option>                             
                                </select>
                              
                            </td>
                        </tr>
                         <tr id="desinfect" style="display: none">
                            <td width="30%">
                              <b>Defect</b>
                            </td>
                            <td>
                                <select data-plugin-selectTwo class="form-control populate" placeholder="None Selected" name="defect" id="defect" onclick="defectscan(this.value)" required>
                                    <option value="">Choose one</option>

                                <?php foreach ($seldefect as $def) { ?>
                                    <option value="<?=$def[id]?>"><?=$def['defect_type']?></option>
                                  
                                <?php } ?>                       
                                </select>
                              
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <span class="badge" style='margin-bottom: 0%;float: right;' id="cart">
                        <?php include "cart.php";?>            
                    </span>
                    <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelqcscan('<?=$typescan?>','<?=$rec_id?>','<?=$_GET[rolewoid]?>')">Confirm</a>
                </td>
            </tr>
          </table>
    </header>
    
    <form class="form-user" id="formku" method="post" onsubmit="return false" enctype="multipart/form-data">
        <div class="form-group" onload="document.getElementById('scanner').focus();">
            <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
            
            <h3 align="center">Scan QR Code</h3>
              <div class="col-sm-2 col-md-2 col-lg-2"/>
                &nbsp;
              </div>
              <div class="col-sm-8 col-md-8 col-lg-8" align="center"/>
                <input type="text" class="form-control" name="scanner" id="scanner" style="font-size: 18px;height: 60px;" onkeypress="onEnter(event)"><br>
                <a href="javascript:void(0)" class="btn btn-primary" id="inputtype" name="inputtype" onclick="scaninput(<?=$typescan?>)">input</a>
              </div>
              <div class="col-sm-2 col-md-2 col-lg-2"/>
                &nbsp;
              </div>
              
            </div>
            <hr>
        </div>
        <div class="form-group">
            &nbsp;
        </div>
        <div class="form-group">
                <div class="row" align="center">
                <h4><b>Scanned Data</b></h4>
                </div>
                  <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax4" style="font-size: 13px;">
                    <thead>
                      <tr>
                        <th width="5%">No</th>
                        <th width="15%">No CMT</th>
                        <th width="15%">Colors</th>
                        <th width="5%">Inseams</th>
                        <th width="5%">Sizes</th>
                        <th width="5%">Seq_Cutting</th>  
                      </tr>
                    </thead>
                    <tbody id="tampilscan" >
                      <?php 
                        if($_GET['d'] == '42'){
                          include "sourcedatascandry.php";
                        } else {
                          include "sourcedatascan.php";
                        }
                       ?>

                    </tbody>
                  </table>
            </div>
        </div> 
   
        <input type="hidden" id="view" name="view" value="<?=$last?>">
        <input type="hidden" id="conf" name="conf" value="">
        <input type="hidden" name="kode" id="kode" value="<?=$kode?>"  />
        <input type="hidden" name="getp" id="getp" value="<?=$_GET[p]?>" />
        <input type="hidden" id="hal" name="hal" value="<?=$get?>">
        <input type="hidden" name="getpage" id="getpage" value="<?=$page?>">
        <input type="hidden" id="scan_type" name="scan_type" value="">
        <input type="hidden" id="getd" name="getd" value="<?=$_GET[d]?>"> <!-- jika process scan garment dry akan terisi !-->
        <input type="hidden" id="typescan_value" name="typescan_value" value="">
        <input type="hidden" id="defect_value" name="defect_value" value="">
    </form>