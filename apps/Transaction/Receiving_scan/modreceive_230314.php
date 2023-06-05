
<?php
session_start();
include "../../../funlibs.php";
$con = new Database();

// select wo master dtl proc
foreach ($seltotscan = $con->select("laundry_wo_master_dtl_proc","DATE(ex_fty_date) as ex_fty_date_show, *","wo_master_dtl_proc_id = '$_GET[id]'") as $hslscan) {}

$jumlahqtycut = $con->selectcount("qrcode_ticketing_master","qrcode_key","wo_no = '$hslscan[wo_no]' and trim(color) = '$hslscan[garment_colors]' and DATE(ex_fty_date) = DATE('$hslscan[ex_fty_date]')  and washtype = 'Wash'");

//mendapatkan no. urut max per wo
//$urutcmt = $con->selectcount("laundry_receive", "rec_id", "wo_no = '" . $hslscan['wo_no'] . "'");
$selecturutcmt = $con->select("laundry_receive", "COALESCE(max(rec_no_uniq),0) as max", "wo_no = '" . $hslscan['wo_no'] . "'");
foreach ($selecturutcmt as $urutcmt) {}
$cmtseq = $urutcmt['max'] + 1;

$expwo = explode('/', $hslscan['wo_no']);

//mendapatkan no. urut max per wo & color
//$urutcmtcolor = $con->selectcount("laundry_receive", "rec_id", "wo_no = '" . $hslscan['wo_no'] . "' and garment_colors = '" . $hslscan['garment_colors'] . "'");
$urutcmtcolor = $con->selectcount("laundry_receive", "rec_id", "wo_no = '" . $hslscan['wo_no'] . "'");
$cmtcolseq = $urutcmtcolor + 1;

if($expwo[1] == 'RECUT'){

	$noreceive = 'L' . $expwo[0]."R".$expwo[3] . $expwo[4] . trim($expwo[5]) . 'A' . sprintf('%03s', $cmtseq) . '-' . $cmtcolseq;
} else {

	$noreceive = 'L' . $expwo[0] . $expwo[4] . $expwo[5] . trim($expwo[6]) . 'A' . sprintf('%03s', $cmtseq) . '-' . $cmtcolseq;
}

$datawono = $hslscan['wo_no'] . '_' . $hslscan['garment_colors'] . '_' . $hslscan['ex_fty_date'];


?>
<div class="col-lg-12 col-md-12">
		<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
			<input type="hidden" name="getp" id="getp" value="option=Transaction&task=receive_scan2&act=ugr_transaction" >
			<input type="hidden" name="getid" id="getid" value="<?=$_GET[id]?>" >
			<input type="hidden" name="idlast" id="idlast" value="">
			<input type="hidden" name="confirm" id="confirm" value="">
			<input type="hidden" name="uniqseq" id="uniqseq" value="<?=$cmtcolseq?>">
			<input type="hidden" name="datawono" id="datawono" value="<?php echo $datawono; ?>">
				<fieldset>
					<div class="form-group">
					 		<input type="hidden" name="noreceive" id="noreceive" value="<?=$noreceive?>">
					 		<a href="javascript:void(0)" onclick="tableToExcel('datatable-receivescan')"  style="cursor:pointer;float:left;"><img src="assets/images/excel_icon_50.gif" alt="Excel" width="60%" style="float: left;" /></a>
					</div>
					<div class="form-group">
						<div class="col-md-12 col-lg-12" style="background-color: #FFFFFF" id="tampilproc">
							<?php include "sourcedatamoddetscan.php"; ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12 col-lg-12" style="background-color: #FFFFFF;text-align: center" id="proc">
							<a href="javascript:void(0)" name="submit" id="submit" onclick="savetoreceive('<?=$_GET[id]?>','1')" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Submit</a>
						</div>					  			
					</div>
				</fieldset>	
		</form>
</div>
<script type="text/javascript">
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