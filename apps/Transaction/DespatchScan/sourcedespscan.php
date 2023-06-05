<?php 
  session_start();
  include "../../../funlibs.php";
  $con = new Database();
  $typescan = 4;

  $cekonprocess = $con->selectcount("laundry_process","process_id","lot_no = '$_GET[lot]' and master_type_process_id = '6' and process_type = '4'");

  if ($cekonprocess > 0) {
    echo "<script>swal({
            icon: 'info',
            title: 'Already Despatch Process',
            text: 'Lot Number have Despatch Process',
            time: 3000,
          })</script>";
  } else {
?>

<div class="form-group" onload="document.getElementById('scanner').focus();">
  <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
  <hr>
    <h3 align="center">Scan QR Code</h3>
    <div class="col-sm-2 col-md-2 col-lg-2"/>
      &nbsp;
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8" align="center"/>
      <input type="text" class="form-control" name="scanner" id="scanner" style="font-size: 18px;height: 60px;" onkeypress="onEnter(event)"><br>
      <a href="javascript:void(0)" class="btn btn-primary" id="inputtype" name="inputtype" onclick="scaninput('<?=$typescan?>','<?=$_GET[lot]?>')" style="display: none">input</a>
    </div>
    <div class="col-sm-2 col-md-2 col-lg-2"/>
      &nbsp;
    </div>
  
  </div>
</div>
<hr>
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
          <?php include "sourcedatascan.php"; ?>

      </tbody>
  </table>
</div>
<input type="hidden" id="typelot" name="typelot" value="<?=$_GET[typelot]?>">
</div> 
<?php } ?>