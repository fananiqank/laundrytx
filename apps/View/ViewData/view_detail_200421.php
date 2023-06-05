<?php 
require( '../../../funlibs.php' );
$con=new Database;
session_start();
$selproc = $con->select("laundry_wo_master_dtl_proc","wo_no,garment_colors,to_char(ex_fty_date, 'DD-MM-YYYY') as ex_fty_date_show, DATE(ex_fty_date) as ex_fty_date,role_wo_master_id","wo_master_dtl_proc_id = '".$_GET['id']."'");
foreach ($selproc as $pro) {}
?>
<br>
<table border="0" width="100%" id="datatable-ajax" style="font-size: 13px;">
    <tr>
      <td width="20%"><b>WO No</b></td>
      <td width="50%"><b>: <?php echo $pro['wo_no']; ?></b></td>
      <td width="30%" rowspan="3" align="right"><a href="javascript:void(0)" class="btn btn-warning" data-toggle='modal' data-target='#funModal' id='mod' onclick='modelview(<?php echo $pro['role_wo_master_id']; ?>)'>Role Process</a></td>
    </tr>
    <tr>
      <td><b>Color No </b></td>
      <td><b>: <?php echo $pro['garment_colors']; ?></b></td>
    </tr>
    <tr>
      <td><b>Ex Fty Date </b></td>
      <td><b>: <?php echo $pro['ex_fty_date_show']; ?></b></td>
    </tr>
</table>
<HR>
<div class="col-sm-12 col-md-6 col-lg-6">
  <div class="col-sm-2 col-md-2 col-lg-2">
    Lot No :
  </div>
  <div class="col-sm-6 col-md-6 col-lg-6">
    <input id="cari" name="cari" value="" type="text" class="form-control">
    <input id="proc_id" name="proc_id" value="<?php echo $_GET[id]; ?>" type="hidden" class="form-control">
  </div>
  <div class="col-sm-1 col-md-1 col-lg-1">
    <a href="javascript:void(0)" class="btn btn-primary" onClick="caridata(cari.value)">Go</a>
  </div>
  <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax2" style="font-size: 13px;">
      <thead>
        <tr>
          <th width="5%">No</th>
          <th width="13%">No Lot</th>
          <th width="10%">Qty First</th>
          <th width="10%">Qty Now</th>
          <th width="5%">Status</th>
          <th width="5%">Detail</th>
        </tr>
      </thead>
      <tbody id="isidetail">
        <?php include "view_detail_isi.php"; ?>
      </tbody>
  </table>
</div>
<div class="col-md-6" id="detail">
</div>

