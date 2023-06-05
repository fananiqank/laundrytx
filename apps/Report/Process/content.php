<?php 
if ($last != '') {
  //jika view
  $onc = "onClick='pindahData2(nocmt.value,nocolor.value,exftydate.value,conpro.value,tgl1.value,tgl2.value)'";
} else {
  //jika bukan view
  $onc = "onClick='pindahData2(nocmt.value,nocolor.value,exftydate.value,conpro.value,tgl1.value,tgl2.value)'";
}

if ($_GET['cm'] != ''){
  $cmt = $con->searchseqcmt($_GET['cm']);
  $ncmt = $cmt;
} else {
  $ncmt = '';
}
if ($_GET['co'] != ''){
  $colors = $con->searchseq($_GET['co']);
  $ncol = $colors;
} else {
  $ncol = '';
}

if ($_GET['xty'] != ''){
  $xftydate = $_GET['xty'];
  $xtyd = $xftydate;
} else {
  $xtyd = '';
} 

if ($cmt != ''){
  $cm = "and wo_no = '$cmt'";
} else {
  $cm = "";
}

if ($colors != ''){
  $co = "and garment_colors = '$colors'";
} else {
  $co = "";
}

if ($xftydate != ''){
  $xdate = "and DATE(ex_fty_date) = '$xftydate'";
} else {
  $xdate = "";
}

$pageexcel = base64_encode('lapproc');

?>


<section class="panel">
  <header class="panel-heading"> 
    <div class="panel-body">
      <a class='btn btn-warning' style='margin-bottom: 0%;' data-toggle='collapse' data-target='#demo'>Filter</a>

      <?php if($last == 'v') { ?>
          <a href="content.php?option=View&task=receive&act=ugr_view" class='btn btn-success' style='margin-bottom: 0%;'>View Sewing Data</a>
      <?php } else { ?> 
         <!--  <a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('v')">View Receive Data</a> -->
         <!--  <span class="badge" style='margin-bottom: 0%;float: right;' id="cart">
            <?php include "cart.php";?>            
          </span>
          <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelcart()"><i class="fa fa-shopping-cart"></i></a> -->
      <?php } ?>

        <?php if ($_GET['cm'] || $_GET['co'] || $_GET['xty']){ ?>
          <div id="demo" class="collapse in" >
        <?php } else { ?>
          <div id="demo" class="collapse in" >
        <?php } ?>
          <hr>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>WO No</b></label>
            <div class="col-md-4">
                <input type="text" name="nocmt" id="nocmt" class="form-control" value="<?php echo $ncmt; ?>" required>
            </div>
            <label class="col-md-2 control-label" for="profileLastName"><b>Colors</b></label>
            <div class="col-md-4">
                <input type="text" name="nocolor" id="nocolor" class="form-control" value="<?php echo $ncol; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Ex fty Date</b></label>
            <div class="col-md-4">
                <input type="text" name="exftydate" id="exftydate" class="form-control" value="<?php echo $xtyd; ?>" required>
            </div>
            <label class="col-md-2 control-label" for="profileLastName"><b>Process <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <select data-plugin-selectTwo class="form-control populate"  name="conpro" id="conpro">
                    <option value="0">Choose</option>
                  <?php $selconpro = $con->select("laundry_master_type_process a left join laundry_master_process b on a.master_type_process_id=b.master_type_process","master_type_process_id,master_type_process_name,COALESCE(master_process_id,0) as master_process_id,master_process_name","master_type_process_id between 3 and 6","master_type_process_id");
                        foreach($selconpro as $conpro){
                          if($conpro['master_type_process_id'] == $_GET['tpro'] && $conpro['master_process_id'] == $_GET['dpro']){$s = "selected";}else {$s="";}
                   ?>
                        <option value="<?=$conpro[master_type_process_id].'_'.$conpro[master_process_id]?>" <?=$s?>><?=$conpro['master_type_process_name'].' - '.$conpro['master_process_name']?></option>
                  <?php } ?>
                    </select>
            </div>
            <input type="hidden" name="tpro" id="tpro" value="<?=$_GET[tpro]?>" required>
            <input type="hidden" name="dpro" id="dpro" value="<?=$_GET[dpro]?>" required>
          </div>
          <div class="form-group">
              	<label class="col-md-2 control-label" for="profileLastName"><b>Periode <font style="color:#ff0000;">*</font> </b></label>
	            <div class="col-md-2">
	                <input type="date" name="tgl1" id="tgl1" class="form-control" value="<?=$_GET[tgl1]?>" required>
                  <span style="font-size: 10px">Day 1 - 06:00:00</span>
                   <input type="hidden" name="tgl1js" id="tgl1js" class="form-control" value="<?=$_GET[tgl1]?>" required>
	            </div>
	            <div class="col-md-1">
	            	s/d
	            </div>
	            <div class="col-md-2">
	                <input type="date" name="tgl2" id="tgl2" class="form-control" value="<?=$_GET[tgl2]?>" required>
                  <span style="font-size: 10px">Day 2 - 06:00:00</span>
                   <input type="hidden" name="tgl2js" id="tgl2js" class="form-control" value="<?=$_GET[tgl2]?>" required>
	            </div>
                <a href="content.php?option=Report&task=process&act=ugr_laundry_report" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" <?php echo $onc; ?> >
              </div>
           <hr>
        </div>

    </header>
<div >
<!-- <a href="javascript:void(0)"onClick="window.open('cetak.php?page=wo&id=<?=$_GET[d]?>')"  style="cursor:pointer">
    <img src="assets/images/print-icon.png" alt="Print PDF" width="4%" style="margin-top: 2%" />
</a> -->
<!-- <a href="javascript:void(0)" onclick="tableToExcel('datatable-ajax')"  style="cursor:pointer">
    <img src="assets/images/excel_icon_50.gif" alt="Excel" width="4%" style="margin-top: 2%" /> -->
  <a href="javascript:void(0)" onClick="window.open('apps/Report/Process/lap.php?cm=<?=$_GET[cm]?>&co=<?=$_GET[co]?>&xty=<?=$_GET[xty]?>&tpro=<?=$_GET[tpro]?>&dpro=<?=$_GET[dpro]?>&tgl1=<?=$_GET[tgl1]?>&tgl2=<?=$_GET[tgl2]?>')"  style="cursor:pointer">
    <img src="assets/images/excel_icon_50.gif" alt="Excel" width="4%" style="margin-top: 2%" />
</a>
<hr>
<table class="table table-bordered table-hover pre-scrollable no-footer" id="datatable-ajax" width="175%">
      <thead align="center">
          <tr>
              <th colspan="17" style="text-align:center" ><h4>REPORT TODAY<br />
               PERIODE <?php echo "$_GET[tgl1] s/d $_GET[tgl2]"; ?></h4></th>
          </tr>
          <tr>
              <th width="5%" rowspan="2">Lot No</th>
              <th width="10%" rowspan="2">Wo No</th>
              <th width="10%" rowspan="2">Color</th>
              <th width="10%" rowspan="2">Ex Fty Date</th>
              <th width="5%" rowspan="2">Seq</th>
              <th width="10%" rowspan="2">Qty</th>
              <th width="10%" colspan="3">IN</th> 
              <th width="10%" colspan="3">Start</th>
              <th width="10%" colspan="3">End</th>
              <th width="10%" colspan="3">Remark</th>
          </tr>
          <tr>
              <th width="15%">Time</th>
              <th width="15%">Pengirim</th>
              <th width="15%">Penerima</th>
              <th width="15%">Time</th>
              <th width="15%">MC</th>
              <th width="15%">Operator</th>
              <th width="15%">Time</th>
              <th width="15%">MC</th>
              <th width="15%">Operator</th>
              <th width="15%">Good</th>
              <th width="15%">Reject</th>
              <th width="15%">Repair</th>
          </tr>
      </thead>
      
      <tbody style="overflow-x: auto">
      </tbody>
  </table>  
 <input class="form-control" name="getp" id="getp" value="option=<?=$_GET[option]?>&task=<?=$_GET[task]?>&act=<?=$_GET[act]?>" type="hidden" />
 <script>

  var tableToExcel = (function() {
    
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))

  }
})()


</script>  