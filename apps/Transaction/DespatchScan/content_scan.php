<?php 
  
if ($_GET['d']){
    session_start();
    include "../../../funlibs.php";
    $con = new Database();
}

$selectuser = $con->select("m_users a join laundry_user_section b on a.user_id=b.user_id join laundry_master_type_process c on b.master_type_process_id = c.master_type_process_id","a.user_id,username,c.master_type_process_name","a.user_id = '$_SESSION[ID_LOGIN]'");
  foreach ($selectuser as $user) {}    
    
      $typescan = 4;
      $section = "Despatch";
echo $_SESSION['IPAD'];
echo $_SESSION['USER_NAME'];
?>
<header>
          <table width="100%" style="font-size: 16px;">
            <tr>
                <td width="30%">
                   <a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('v')">View Despatch Data</a>
                </td>
                <td>
                    <span class="badge" style='margin-bottom: 0%;float: right;' id="cart">
                        <?php include "apps/Transaction/DespatchScan/cart.php";?>            
                    </span>
                    <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldespscan('<?=$typescan?>','<?=$rec_id?>')">Confirm</a>
                </td>
            </tr>
          </table>
          <hr>
    </header>
    
    <form class="form-user" id="formku" method="post" onsubmit="return false" enctype="multipart/form-data">
    	 <div class="form-group">
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>User</b></label>
            <div class="col-md-4">
                <input type="text" name="user" id="user" class="form-control" onkeypress="onEnterUser(event,this.value)" required>
                <input type="hidden" name="userid" id="userid" class="form-control" value="<?=$_SESSION[ID_LOGIN]?>" required>
                <input type="hidden" name="mastertypeid" id="mastertypeid" class="form-control" value="<?=$user[master_type_process_id]?>" required>
            </div>
            <label class="col-md-2 control-label" for="profileLastName"><b>Lot No <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="lot_no" id="lot_no" class="form-control" onkeypress="onEnterLot(event,this.value)" required>
            </div>
            
        </div>
        <div id="sourcedespscan">
          <?php //include "sourcedespscan.php"; ?>
        </div>
   
        <input type="hidden" id="view" name="view" value="<?=$last?>">
        <input type="hidden" id="conf" name="conf" value="">
        <input type="hidden" name="kode" id="kode" value="<?=$kode?>"  />
        <input type="hidden" name="getp" id="getp" value="<?=$_GET[p]?>" />
        <input type="hidden" id="hal" name="hal" value="<?=$get?>">
        <input type="hidden" name="getpage" id="getpage" value="<?=$page?>">
        <input type="hidden" id="scan_type" name="scan_type" value="">
        <input type="hidden" id="getd" name="getd" value="<?=$_GET[d]?>"> <!-- jika process scan garment dry akan terisi !-->
        <input type="hidden" id="getd" name="getd" value="<?=$_GET[roleid]?>">
    </form>