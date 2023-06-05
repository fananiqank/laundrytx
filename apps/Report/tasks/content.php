<style>
.noprint {display:none;}
</style>

<div align="right"><!-- <a href="apps/wo/modal2.php?st=tambah" type="button" class="simple-ajax-modal btn btn-warning" ><i class="fa fa-cloud"></i>Tambah Request</a> --></div><br>
<header class="panel-heading">
  <a class='btn btn-warning' style='margin-bottom: 0%;' data-toggle='collapse' data-target='#demo'>Filter Data</a>

  <?php 
    if ($_GET['tg'] && $_GET['tg2']){
      if ($_GET['tg'] != 'A' && $_GET['tg2'] != 'A'){
      $a=explode('-',$_GET['tg']);
      $gg=$a[1]."/".$a[2]."/".$a[0];
      $jtg=$a[2]."-".$a[1]."-".$a[0];
      $b=explode("-",$_GET['tg2']);
      $wp=$b[1]."/".$b[2]."/".$b[0];
      $jtg2=$b[2]."-".$b[1]."-".$b[0];
      }
    }
  ?>
<?php if ($_GET['c'] || $_GET['s'] || $_GET['y'] || $_GET['t'] || $_GET['tg'] || $_GET['tg2']){ ?>
  <div id="demo" class="collapse in" >
<?php } else {?>
  <div id="demo" class="collapse" >
<?php } ?>
<hr>
<div class="form-group">
    <label class="col-md-1 control-label" for="profileLastName"><b>Category</b></label>
    <div class="col-md-3">
      <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="kategori" id="kategori" onchange="kate(this.value)">
          <option value="">All</option>
          <?php 
              $selcat = $con->select("mtkategori","*","status_kategori = 1");
              foreach ($selcat as $cat) { 
              if($_GET['c'] == $cat['id_kategori']){
                $s="selected";
              } else{
                $s="";
              }
          ?>
            <option value="<?=$cat[id_kategori]?>" <?=$s?>><?=$cat['nama_kategori']?></option>
          <?php } ?>
      </select>
     </div>
     <label class="col-md-1 control-label" for="profileLastName"><b>Priority</b></label>
     <div class="col-md-3">
      <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="priority" id="priority">
            <option value="">All</option>
            <option value="1" <?php if($_GET['y'] == '1'){echo "selected";} ?>>Low</option>
            <option value="2" <?php if($_GET['y'] == '2'){echo "selected";} ?>>Medium</option>
            <option value="3" <?php if($_GET['y'] == '3'){echo "selected";} ?>>High</option>
      </select>
     </div>
</div>
<div class="form-group">
  <label class="col-md-1 control-label" for="profileLastName"><b>Status</b></label>
    <div class="col-md-3">
      <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="lstatus" id="lstatus">
          <option value="">All</option>
          <option value="1" <?php if($_GET['t'] == '1'){echo "selected";} ?>>Open</option>
          <option value="2" <?php if($_GET['t'] == '2'){echo "selected";} ?>>On Progress</option>
          <option value="3" <?php if($_GET['t'] == '3'){echo "selected";} ?>>Hold</option>
          <option value="4" <?php if($_GET['t'] == '4'){echo "selected";} ?>>Closed</option>
          <option value="5" <?php if($_GET['t'] == '5'){echo "selected";} ?>>Hold Request</option>
          <option value="6" <?php if($_GET['t'] == '6'){echo "selected";} ?>>Assign</option>
          <option value="0" <?php if($_GET['t'] == '0'){echo "selected";} ?>>Reject</option>
      </select>
     </div>
     
     <label class="col-md-1 control-label" for="profileLastName"><b>PIC</b></label>
     <div class="col-md-3">
      <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="ass" id="ass">
        <option value="">All</option>
        <?php 
          $selagent = $con->select("mtagent a join mtpegawai b on a.id_pegawai=b.id_pegawai","id_agent,nama_pegawai","id_status = 1");
          foreach ($selagent as $age) { 
          if($_GET['g'] == $age['id_agent']){
            $s="selected";
          } else{
            $s="";
          }
          $selwoag = $con->select("trwo a join trdata b on a.id_data=b.id_data","count(id_wo) as jmlwoag","a.id_agent = '$age[id_agent]' and b.status_data != 4");
          foreach ($selwoag as $woag) {}
            if ($woag['jmlwoag'] > 0){
              $persen1 = (($woag['jmlwoag']/$jmlwo['jmlwo'])*100);
              $persen = number_format($persen1);
            } else {
              $persen = '0';
            }
        ?>
          <option value="<?=$age[id_agent]?>" <?=$s?>><?=$age['nama_pegawai']?></option>
        <?php } ?>
      </select>
     </div>
</div>   
<hr>
<div class="form-group">
    <label class="col-md-1 control-label" for="profileLastName"><b>Periode</b></label>
    <div class="col-md-3">
      <i class="fa fa-calendar" style="height:30px;"></i>
      <input type="text" id="tgl" name="tgl" data-plugin-datepicker style="border-radius:4px; -moz-border-radius:4px; height:30px;" value="<?=$gg?>" required>
      <input id="tglsatu" name="tglsatu" value="<?=$_GET['tg']?>" type="hidden">
    </div>
    <label class="col-md-2 control-label" for="profileLastName" style="text-align: center"><b>s / d</b> </label>
    <div class="col-md-3">
      <i class="fa fa-calendar" style="height:30px;"></i>
      <input type="text" id="tgl2" name="tgl2" data-plugin-datepicker style="border-radius:4px; -moz-border-radius:4px; height:30px;" value="<?=$wp?>" required>
      <input id="tgldua" name="tgldua" value="<?=$_GET['tg2']?>" type="hidden">
    </div>

    <div class="col-md-12">
      <a href="content.php?p=<?=$_GET[p]?>" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
      <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" onClick="pindahData2(kategori.value,priority.value,lstatus.value,ass.value,tgl.value,tgl2.value)">
      
    </div>
</div>
</div>
</header>
<div >
<!-- <a href="javascript:void(0)"onClick="window.open('cetak.php?page=wo&id=<?=$_GET[d]?>')"  style="cursor:pointer">
    <img src="assets/images/print-icon.png" alt="Print PDF" width="4%" style="margin-top: 2%" />
</a> -->
<!-- <a href="javascript:void(0)" onclick="tableToExcel('datatable-ajax')"  style="cursor:pointer">
    <img src="assets/images/excel_icon_50.gif" alt="Print PDF" width="4%" style="margin-top: 2%" />
</a> -->
 <a href="javascript:void(0)" onClick="window.open('spk.php?page=lapta&c=<?=$_GET[c]?>&y=<?=$_GET[y]?>&t=<?=$_GET[t]?>&g=<?=$_GET[g]?>&tg2=<?=$_GET[tg2]?>&tg=<?=$_GET[tg]?>')"  style="cursor:pointer">
    <img src="assets/images/excel_icon_50.gif" alt="Excel" width="4%" style="margin-top: 2%" />
</a>
<hr>
<table class="table table-bordered table-hover pre-scrollable" id="datatable-ajax" width="180%">
      <thead>
          <tr>
              <th colspan="12" style="text-align:center" ><h4>TASKS REPORT<br />
               PERIODE <?php echo "$jtg s/d $jtg2"; ?></h4></th>
          </tr>
          <tr>
              <th width="5%">No</th>
              <th width="5%">No Tasks</th>
              <th width="10%">Name_Tasks</th>
              <th width="5%">Assign</th>
              <th width="5%">Category</th>
              <th width="5%">Created_Date</th>
              <th width="5%">Start_Date</th>
              <th width="5%">End_Date</th>
              <th width="5%">Priority</th>
              <th width="5%">Status</th> 
              <th width="5%">Days</th>
              <th width="40%">Remarks</th>
          </tr>
      </thead>
      
      <tbody style="overflow-x: auto">
      </tbody>
      <input type="hidden" name="hal" id="hal" value="<?=$_GET[p]?>">
  </table>

<script>
function kate(id){
    $.get('apps/report/request/subcat.php?id='+id, function(data) {
        $('#subkategori').html(data);    
    });
    
  } 

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