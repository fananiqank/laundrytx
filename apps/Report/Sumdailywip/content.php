<?php 
if ($last != '') {
  //jika view
  $onc = "onClick='pindahData2(nocmt.value,nocolor.value,exftydate.value)'";
} else {
  //jika bukan view
  $onc = "onClick='pindahData2(nocmt.value,nocolor.value,exftydate.value)'";
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
            <label class="col-md-2 control-label" for="profileLastName"><b>WO No <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocmt" id="nocmt" class="form-control" value="<?php echo $ncmt; ?>" required>
            </div>
            <label class="col-md-2 control-label" for="profileLastName"><b>Colors<font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocolor" id="nocolor" class="form-control" value="<?php echo $ncol; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Ex fty Date <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="exftydate" id="exftydate" class="form-control" value="<?php echo $xtyd; ?>" required>
            </div>
           
          </div>
          <div class="form-group">
              <div class="col-md-12">
                <a href="content.php?option=Report&task=dailywip&act=ugr_laundry_report" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" <?php echo $onc; ?> >
              </div>
          </div>
           <hr>
        </div>

  </header>
</section>
<div >
<!-- <a href="javascript:void(0)"onClick="window.open('cetak.php?page=wo&id=<?=$_GET[d]?>')"  style="cursor:pointer">
    <img src="assets/images/print-icon.png" alt="Print PDF" width="4%" style="margin-top: 2%" />
</a> -->
<!-- <a href="javascript:void(0)" onclick="tableToExcel('datatable-ajax')"  style="cursor:pointer">
    <img src="assets/images/excel_icon_50.gif" alt="Excel" width="4%" style="margin-top: 2%" /> -->
    <a href="javascript:void(0)" onClick="window.open('apps/Report/Dailywip/lap.php?cm=<?=$_GET[cm]?>&co=<?=$_GET[co]?>&xty=<?=$_GET[xty]?>')"  style="cursor:pointer">
    <img src="assets/images/excel_icon_50.gif" alt="Excel" width="4%" style="margin-top: 2%" />
</a>
<hr>
<table class="table table-bordered table-hover pre-scrollable no-footer" id="datatable-aj" >
      <thead align="center">
          <tr>
              <th colspan="28" style="text-align:center" ><h4>SUMMARY DAYLY WIP REPORT<br />
               <!-- PERIODE <?php echo "$jtg s/d $jtg2"; ?> -->
             </h4>
             </th>
          </tr>
          <tr>
              <th width="5%" rowspan="2">No</th>
              <th width="10%" rowspan="2">WO NO</th>
              <th width="10%" rowspan="2">Color</th>
              <th width="10%" rowspan="2">Ex Fty Date</th>
              <th width="10%" colspan="2">Resin Spray</th>
              <th width="10%" colspan="2">3D crinckle</th> 
              <th width="10%" colspan="2">Laser</th> 
              <th width="10%" colspan="2">whisker</th> 
              <th width="10%" colspan="2">Chevron- Knee bust</th> 
              <th width="10%" colspan="2">Handsand</th> 
              <th width="10%" colspan="2">Wipping & high-lighting</th> 
              <th width="10%" colspan="2">Tacking</th> 
              <th width="10%" colspan="2">Tie -effect</th> 
              <th width="10%" colspan="2">Destroy</th> 
              <th width="10%" colspan="2">Grinding</th> 
              <th width="10%" colspan="2">Cleaning Destory</th> 
              
              
          </tr>
          <tr>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">IN</th>
              <th width="5%">OUT</th>
              <th width="5%">OUT</th>
<th width="5%">OUT</th>
<th width="5%">OUT</th>

          </tr>
      </thead>
      
      <tbody >
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          
        </tr>
        
      </tbody>
      <input type="hidden" name="getp" id="getp" value="option=<?=$_GET[option]?>&task=<?=$_GET[task]?>&act=<?=$_GET[act]?>" type="hidden" />
  </table>  

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