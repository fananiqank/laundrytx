<?php 
  
if ($_GET['d']){
    session_start();
    include "../../../funlibs.php";
    $con = new Database();
}

    if ($_GET['d'] == '42'){
      $typescan = 2;
      $section = "Dry";
    } else {
      $typescan = 1;
      $section = "Receive";
    }

?>
<header>
          <table width="100%" style="font-size: 16px;">
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
                    <span class="badge" style='margin-bottom: 0%;float: right;' id="cart">
                        <?php include "apps/Transaction/Scan/cart.php";?>            
                    </span>
                    <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelscan('<?php echo $typescan; ?>','<?php echo $rec_id; ?>','<?php echo $_GET[usercode]; ?>')">Confirm</a>
                </td>
            </tr>
          </table>
    </header>
    <hr>
    <form class="form-user" id="formku" method="post" onsubmit="return false" enctype="multipart/form-data">
        <div class="form-group" onload="document.getElementById('scanner').focus();">
            
          <div class="form-group">      
              <div class="col-sm-2 col-md-2 col-lg-2"/>
                <h5 align="center"><b>User</b></h5>
              </div>
              <div class="col-sm-4 col-md-4 col-lg-4" align="center"/>
                <input type="text" class="form-control" name="usercode" id="usercode" style="font-size: 14px;" onkeypress="onEnter(event)" required><br>
              </div>
              <div class="col-sm-2 col-md-2 col-lg-2" style="background-color: #FFFFFF; ">
                <h5 align="center"><b>Factory</b></h5>
              </div>
              <div class="col-sm-4 col-md-4 col-lg-4" style="background-color: #FFFFFF; ">
                <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="factory" id="factory" onchange="ftypescan(this.value)" readonly>
                      <option value='4'>Laundry</option>              
                </select>
              </div>
          </div>
          <div class="form-group">
              <div class="col-sm-2 col-md-2 col-lg-2"/>
                <h5 align="center"><b>Scan QR Code</b></h5>
              </div>
              <div class="col-sm-6 col-md-6 col-lg-6" align="center"/>
                <input type="text" class="form-control" name="scanner" id="scanner" style="font-size: 16px;height:40px;" onkeypress="onEnter(event)"><br>
                <a href="javascript:void(0)" class="btn btn-primary" id="inputtype" name="inputtype" onclick="scaninput(<?php echo $typescan; ?>)" style="display: none">input</a>
              </div>
              <div class="col-sm-4 col-md-4 col-lg-4"/>
                &nbsp;
              </div>
          </div>
          <hr>
        </div>
        
        <div class="form-group">
                <div class="row" align="center">
                <h4><b>Scanned Data</b></h4>
                </div>
            
                  <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax4" style="font-size: 11px;height: 50px">
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
                        if($_GET['d'] == '42'){
                          include "apps/Transaction/Scan/sourcedatascandry.php";
                        } else {
                          include "apps/Transaction/Scan/sourcedatascan.php";
                        }
                       ?>

                    </tbody>
                  </table>
                
            </div>
        </div> 
   
        <input type="hidden" id="view" name="view" value="<?php echo $last; ?>">
        <input type="hidden" id="conf" name="conf" value="">
        <input type="hidden" name="kode" id="kode" value="<?php echo $kode; ?>"  />
        <input type="hidden" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?> ?>" />
        <input type="hidden" id="hal" name="hal" value="<?php echo $get; ?>">
        <input type="hidden" name="getpage" id="getpage" value="<?php echo $page; ?>">
        <input type="hidden" id="scan_type" name="scan_type" value="">
        <input type="hidden" id="getd" name="getd" value="<?php echo $_GET[d]; ?>"> <!-- jika process scan garment dry akan terisi !-->
        <input type="hidden" id="getd" name="getd" value="<?php echo $_GET[roleid]; ?>">
    </form>