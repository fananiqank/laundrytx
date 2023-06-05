<?php 

session_start();
include "../../../funlibs.php";
$con = new Database();

$selectuser = $con->select("m_users a join laundry_user_section b on a.user_id=b.user_id join laundry_master_type_process c on b.master_type_process_id = c.master_type_process_id","a.user_id,username,c.master_type_process_name","a.user_id = '$_SESSION[ID_LOGIN]'");
  foreach ($selectuser as $user) {}    
    
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
                    <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelscan('<?=$typescan?>','<?=$rec_id?>')">Confirm</a>
                </td>
            </tr>
          </table>
    </header>
    
    <form class="form-user" id="formku" method="post" onsubmit="return false" enctype="multipart/form-data">
        <div class="form-group" onload="document.getElementById('scanner').focus();">
            <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
            <hr>
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
                          include "sourcedatascandry_pro.php";
                       ?>

                    </tbody>
                  </table>
            </div>
           
        </div> 
   
        <input type="hidden" id="view" name="view" value="<?=$last?>">
        <input type="hidden" id="conf" name="conf" value="">
        <input type="hidden" name="kode" id="kode" value="<?=$kode?>"  />
        <input type="" name="getp" id="getp" value="<?=$_GET[p]?>" />
        <input type="hidden" id="hal" name="hal" value="<?=$get?>">
        <input type="hidden" name="getpage" id="getpage" value="<?=$page?>">
        <input type="hidden" id="scan_type" name="scan_type" value="">
        <input type="hidden" id="getd" name="getd" value="<?=$_GET[d]?>"> <!-- jika process scan garment dry akan terisi !-->
    </form>