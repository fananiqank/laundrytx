<?php 
  session_start();
  include "../../../funlibs.php";
  $con = new Database();

  $typescan = 3;
  $remark_scan = "<option value=''>Choose one</option>
                        <option value='1'>Good</option>
                        <option value='2'>Reject</option>
                        <option value='3'>Rework</option>";

// mendapatkan qty terakhir
  foreach ($con->select("laundry_lot_number a join laundry_process b on a.lot_no=b.lot_no","a.lot_id,a.lot_no,a.lot_qty,b.process_qty_good", "a.lot_no = '".$_GET['lot']."' and process_type = 4","process_id DESC","1") as $qtydesc) {}
   if ($qtydesc['process_qty_good'] != '') {
      $qtylast = $qtydesc['process_qty_good'];
   } else {
      $qtylast = $qtydesc['lot_qty'];
   }

 
?>
            <div class="form-group">
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  <h5><b>Status</b></h5>
                </div>
                <div class="col-sm-10 col-md-10 col-lg-10" style="background-color: #FFFFFF; ">
                      <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="typescan" id="typescan" onchange="ftypescan(this.value)" required>
                                <option value=''>Choose one</option>
                                <option value='1'>Good</option>
                                <option value='2'>Reject</option>
                                <option value='3'>Rework</option>             
                      </select>
                </div>
            </div>
            <div class="form-group" id="desinfect" style="display: none">
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  <h5><b>Location</b></h5>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" style="background-color: #FFFFFF; ">
                   <input type="text" class="form-control" name="panel_type" id="panel_type" style="font-size: 14px;" onkeypress="onEnterPanel(event)" required>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  <h5><b>Reject Type</b></h5>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4" style="background-color: #FFFFFF; ">
                   <input type="text" class="form-control" name="reject_type" id="reject_type" style="font-size: 14px;" onkeypress="onEnterReject(event,3)" required>
                </div>
            </div>
            <div class="form-group" onload="document.getElementById('scanner').focus();">
                <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                  <h5><b>Scan QR</b></h5>
                </div>
                <div class="col-sm-10 col-md-10 col-lg-10" style="background-color: #FFFFFF; ">
                  <input type="text" class="form-control" name="scanner" id="scanner" style="font-size: 16px;height:40px;" onkeypress="onEnter(event)" required>
                  <a href="javascript:void(0)" class="btn btn-primary" id="inputtype" name="inputtype" onclick="scaninputqc('<?php echo $typescan; ?>','<?php echo $_GET[lot]; ?>')" style="display: none">input</a>
                </div>
                             
            </div>

<!-- tambahan inputan -->
            <input type="hidden" id="qtylast" name="qtylast" value="<?php echo $qtylast; ?>">
            <input type="hidden" id="lotnumber" name="lotnumber" value="<?php echo $qtydesc[lot_no]; ?>">
            <input type="hidden" id="lotid" name="lotid" value="<?php echo $qtydesc[lot_id]; ?>">