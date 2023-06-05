<?php 

session_start();
include "../../../funlibs.php";
$con = new Database();

$selectuser = $con->select("m_users a join laundry_user_section b on a.user_id=b.user_id join laundry_master_type_process c on b.master_type_process_id = c.master_type_process_id","a.user_id,username,c.master_type_process_name","a.user_id = '$_SESSION[ID_LOGIN]'");
  foreach ($selectuser as $user) {}    

//mendapatkan sequence untuk Scanning Dry
$countscan= $con->selectcount("laundry_process","process_id","lot_no = '$_GET[lot]' and master_process_id = '42' and process_type = 4");
$sequencedry = $countscan+1;

    if ($_GET['d'] == '42'){
        $typescan = 2;
        $section = "Dry";
        $workcenter_id = "WAS3";
        $remark_scan = "<option value='1'>Good</option>";
        foreach($con->select("laundry_receive a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id","b.rework_seq","rec_no = '$_GET[lot]'") as $wodtl){}
        $rework_seq = $wodtl['rework_seq'];

    } else if ($_GET['d'] == '3'){
        $typescan = 3;
        $section = "QC Inspection";
        $workcenter_id = "WAS2";
        $remark_scan = "<option value=''>Choose one</option>
                        <option value='1'>Good</option>
                        <option value='2'>Reject</option>
                        <option value='3'>Rework</option>";
       foreach($con->select("laundry_lot_number a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id","b.rework_seq","lot_no = '$_GET[lot]'") as $wodtl){}
        $rework_seq = $wodtl['rework_seq'];

    }

$seldefect = $con->select("qrcode_defect_master","*","workcenter_id = '$workcenter_id'");

?>
<header>
          <table width="100%" style="font-size: 14px;">
            <tr>
                <td width="30%">
                    <table class="table datatable-basic table-hover dataTable no-footer" width="100%">
                        <tr>
                            <td>
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
                        
                    </table>
                </td>
                <td>
                    <span class="badge" style='margin-bottom: 0%;float: right;' id="cart">
                        <?php include "cart_pro.php";?>            
                    </span>
                    <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelscan('<?=$typescan?>','<?=$_GET[id]?>','<?=$_GET[roledtlid]?>','<?=$_GET[lot]?>','<?=$_GET[rolewoid]?>','<?=$_GET[qtylast]?>')">Confirm</a>
                </td>
            </tr>
          </table>
    </header>
    <hr>
    
    <form class="form-user" id="formku" method="post" onsubmit="return false" enctype="multipart/form-data">
            <div class="form-group">
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  User
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" style="background-color: #FFFFFF; ">
                  <input type="text" class="form-control" name="usercode" id="usercode" style="font-size: 14px;" onkeypress="onEnteruser(event,1)" required>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  Factory
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" style="background-color: #FFFFFF; ">
                    <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="factory" id="factory" onchange="ftypescan(this.value)" readonly>
                          <option value='4'>Laundry</option>              
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  Status
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" style="background-color: #FFFFFF; ">
                      <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="typescan" id="typescan" onchange="ftypescan(this.value)" required>
                                    <?php echo $remark_scan; ?>               
                      </select>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                  &nbsp;
                </div>
            </div>
            <div class="form-group" id="desinfect" style="display: none">
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  Location
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" style="background-color: #FFFFFF; ">
                   <input type="text" class="form-control" name="panel_type" id="panel_type" style="font-size: 14px;" onkeypress="onEnterPanel(event)" required>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  Reject Type
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" style="background-color: #FFFFFF; ">
                   <input type="text" class="form-control" name="reject_type" id="reject_type" style="font-size: 14px;" onkeypress="onEnterReject(event,3)" required>
                </div>
            </div>
            <div class="form-group" onload="document.getElementById('scanner').focus();">
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  Scan QR
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6" style="background-color: #FFFFFF; ">
                  <input type="text" class="form-control" name="scanner" id="scanner" style="font-size: 16px;height:40px;" onkeypress="onEnter(event)" required>
                  <a href="javascript:void(0)" class="btn btn-primary" id="inputtype" name="inputtype" onclick="scaninput('<?=$typescan?>','<?=$_GET[lot]?>')" style="display: none">input</a>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2"/>
                  &nbsp;
                </div>              
            </div>
            <hr>
            <div class="form-group">
                  <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax4" style="font-size: 11px;">
                    <thead>
                      <tr>
                        <th width="15%">WO No</th>
                        <th width="15%">QRcode Key</th>
                        <th width="10%">Color</th>
                        <th width="5%">Size</th>
                        <th width="5%">Inseam</th>
                        <th width="5%">Seq_No</th>
                        <th width="5%">Status</th>
                        <th width="10%">User</th>
                        <th width="8%">Factory</th>  
                      </tr>
                    </thead>
                    <tbody id="tampilscan" >
                      <?php 
                          echo "tes";
                          include "apps/Process/Scan/sourcedatascan_pro.php";
                        
                       ?>

                    </tbody>
                  </table>
                
            </div>
      

        <input type="hidden" id="view" name="view" value="<?=$last?>">
        <input type="hidden" id="conf" name="conf" value="">
        <input type="hidden" name="kode" id="kode" value="<?=$kode?>"  />
        <input type="hidden" name="getp" id="getp" value="<?=$_GET[p]?>" />
        <input type="hidden" id="hal" name="hal" value="<?=$get?>">
        <input type="hidden" name="getpage" id="getpage" value="<?=$page?>">
        <input type="hidden" id="scan_type" name="scan_type" value="">
        <input type="hidden" id="getd" name="getd" value="<?=$_GET[d]?>"> <!-- jika process scan garment dry akan terisi !-->
        <?php if ($_GET['d'] == '42'){ ?>
          <input type="hidden" id="typescan_value" name="typescan_value" value="2">
        <?php } else { ?>
          <input type="hidden" id="typescan_value" name="typescan_value" value="">
        <?php } ?>
        <input type="hidden" id="defect_value" name="defect_value" value="">
        <input type="hidden" id="rework_value" name="rework_value" value="<?=$rework_seq?>">
        <input type="hidden" id="qtylast" name="qtylast" value="<?=$_GET[qtylast]?>">
        <input type="hidden" id="lotnumber" name="lotnumber" value="<?=$_GET[lot]?>">
    </form>