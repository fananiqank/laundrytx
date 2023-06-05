<?php 

session_start();
include "../../../funlibs.php";
$con = new Database();

$section = "QC Final Insp Manual";

?>
<header>
          <table width="100%" style="font-size: 16px;">
            <tr>
                <td width="30%">
                    <table style="border:0" width="100%">
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
                        <?php include "cartqc.php";?>            
                    </span>
                    <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelqc('<?php echo $typescan; ?>','<?php echo $lotno[lot_id]; ?>','0','<?php echo $_GET[lot]; ?>','<?php echo $_GET[rolewoid]; ?>','<?php echo $_GET[d]; ?>')">Confirm</a>
                </td>
            </tr>
          </table>
          <hr>
    </header>
    
    <form class="form-user" id="formku" method="post" onsubmit="return false" enctype="multipart/form-data">
       <div class="form-group">
            <div class="col-sm-2 col-md-2 col-lg-2" align="center">
              <b>User</b>
            </div>
            <div class="col-sm-5 col-md-5 col-lg-5">
              <input class="form-control" id="username" name="username" onkeypress="onEnterUser(event,this.value)">
            </div>
            
       </div>
       <div class="form-group">
            <div class="col-sm-2 col-md-2 col-lg-2" align="center">
              <b>Lot No</b>
            </div>
            <div class="col-sm-5 col-md-5 col-lg-5" >
              <input class="form-control" id="lot_number" name="lot_number" onkeypress="onEnterLot(event,this.value)">
            </div>
       </div>
       <h3>&nbsp;</h3>
        <div class="form-group" onload="document.getElementById('scanner').focus();" id="tampcontent">
           <?php //include "tampilcontent.php"; ?>
        </div>
    
        <input type="hidden" id="lotnumber" name="lotnumber" value="<?php echo $_GET[lot]; ?>"> 
        <input type="hidden" id="view" name="view" value="<?php echo $last; ?>">
        <input type="hidden" id="confirm" name="confirm" value="">
        <input type="hidden" id="kode" name="kode" value="<?php echo $kode; ?>"  />
        <input type="hidden" id="scan_type" name="scan_type" value="">
        <input type="hidden" id="getd" name="getd" value="<?php echo $_GET[d]; ?>"> <!-- jika process scan garment dry akan terisi !-->
        <input type="hidden" id="rolewoid" name="rolewoid" value="<?php echo $_GET[rolewoid]; ?>">
        <input type="hidden" id="typelot" name="typelot" value="<?php echo $_GET[typelot]; ?>">
    </form>