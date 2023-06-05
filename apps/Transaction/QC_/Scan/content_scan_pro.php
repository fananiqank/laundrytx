<?php 

session_start();
include "../../../funlibs.php";
$con = new Database();

  

//mendapatkan sequence untuk Scanning Dry
$countscan= $con->selectcount("laundry_process","process_id","lot_no = '".$_GET['lot']."' and master_process_id = '42' and process_type = 4");
$sequencedry = $countscan+1;

   /* if ($_GET['d'] == '42'){
        $typescan = 2;
        $section = "Dry";
        $workcenter_id = "WAS3";
        $remark_scan = "<option value='1'>Good</option>";
        foreach($con->select("laundry_receive a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id","b.rework_seq","rec_no = '$_GET[lot]'") as $wodtl){}
        $rework_seq = $wodtl['rework_seq'];

    } else if ($_GET['d'] == '3'){*/
        $typescan = 3;
        $section = "QC Inspection";
        $workcenter_id = "WAS2";
        
       foreach($con->select("laundry_lot_number a join laundry_wo_master_dtl_proc b on a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id","b.rework_seq","lot_no = '".$_GET['lot']."'") as $wodtl){}
        $rework_seq = $wodtl['rework_seq'];

    //}

$seldefect = $con->select("qrcode_defect_master","*","workcenter_id = '$workcenter_id'");

?>
<header>
          <table width="100%" style="font-size: 14px;">
            <tr>
                <td width="30%">
                      <table style="border: 0" width="100%">
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
                    <span class="badge" style='margin-bottom: 0%;float: right;display: none' id="cart">
                        <?php include "cart_pro.php";?>            
                    </span>
                    <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;display: none" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelscanpro('<?php echo $typescan; ?>','<?php echo $_GET[id]; ?>','<?php echo $_GET[roledtlid]; ?>','<?php echo $_GET[lot]; ?>','<?php echo $_GET[rolewoid]; ?>','<?php echo $_GET[qtylast]; ?>')">Confirm</a>
                </td>
            </tr>
          </table>
    </header>
    <hr>
    
    <form class="form-user" id="formku" method="post" onsubmit="return false" enctype="multipart/form-data">
            <div class="form-group">
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  <h5><b>User</b></h5>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" style="background-color: #FFFFFF; ">
                  <input type="text" class="form-control" name="usercode" id="usercode" style="font-size: 14px;" onkeypress="onEnteruser(event,1)" required>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  <h5><b>Factory</b></h5>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" style="background-color: #FFFFFF; ">
                    <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="factory" id="factory" onchange="ftypescan(this.value)" readonly>
                          <option value='4'>Laundry</option>              
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  <h5><b>Lot No</b></h5>
                </div>
                <div class="col-sm-10 col-md-10 col-lg-10" style="background-color: #FFFFFF; ">
                  <input type="text" class="form-control" name="lot_number" id="lot_number" style="font-size: 14px;" onkeypress="onEnterlot(event,this.value)" required>
                </div>
            </div>
            <div id="tampilinputscan">
                
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
                          include "apps/Transaction/Scan/sourcedatascan_pro.php";
                        
                       ?>

                    </tbody>
                  </table>
                
            </div>
      

        <input type="hidden" id="view" name="view" value="<?php echo $last; ?>">
        <input type="hidden" id="conf" name="conf" value="">
        <input type="hidden" name="kode" id="kode" value="<?php echo $kode; ?>"  />
        <input type="hidden" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" />
        <input type="hidden" id="hal" name="hal" value="<?php echo $get; ?>">
        <input type="hidden" name="getpage" id="getpage" value="<?php echo $page; ?>">
        <input type="hidden" id="scan_type" name="scan_type" value="">
        <input type="hidden" id="getd" name="getd" value="<?php echo $_GET[d]; ?>"> <!-- jika process scan garment dry akan terisi !-->
        <?php if ($_GET['d'] == '42'){ ?>
          <input type="hidden" id="typescan_value" name="typescan_value" value="2">
        <?php } else { ?>
          <input type="hidden" id="typescan_value" name="typescan_value" value="">
        <?php } ?>
        <input type="hidden" id="defect_value" name="defect_value" value="">
        <input type="hidden" id="rework_value" name="rework_value" value="<?php echo $rework_seq; ?>">
        
    </form>