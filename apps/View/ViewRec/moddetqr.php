<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();
$selrec = $con->select("laundry_wo_master","*","wo_no = '$_GET[wo]'");
foreach ($selrec as $wp) {}

$selrechead = $con->select("(select b.wo_no,b.color,DATE(b.ex_fty_date) ex_fty_date,a.qrcode_key,d.inseam,d.size,gdp_datetime,login_name,a.user_code,rec_no
from qrcode_gdp a join laundry_qrcodebatch b on a.gdpbatch=b.qrcodebatch_no and a.step_id_detail = 4
join laundry_receive c on c.rec_id=b.rec_id
join qrcode_ticketing_master d on a.ticketid=d.ticketid and d.wo_no=b.wo_no and d.color=b.color
where c.rec_no='$_GET[rec]' ) a","*","");
foreach ($selrechead as $srh) {}

$jumlahqtycut = $con->selectcount("qrcode_ticketing_master","qrcode_key","wo_no = '$srh[wo_no]' and color = '$srh[color]' and DATE(ex_fty_date) = '$srh[ex_fty_date]'  and washtype = 'Wash'");
?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
		
		<span class="separator"></span>
				<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
					<div class="row">
                    	 <div class="form-group">
	                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
	                        	
	                            <div class="row" align="center">
	                            	<div class="col-sm-2 col-md-2 col-lg-2"> 
	                            			<a href="javascript:void(0)" onclick="tableToExcel('datatable-ajax5')"  style="cursor:pointer;float:left;"><img src="assets/images/excel_icon_50.gif" alt="Excel" width="60%" style="float: left;" /></a>
	                            	</div>
	                            	<div class="col-sm-10 col-md-10 col-lg-10">&nbsp;</div>
	                            </div>
	                            <div class="pre-scrollable">
	                              <table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax5" style="font-size: 13px;">
	                                <thead>
	                                  <tr><td colspan="10" align="center"><h5><b>Detail Receive Data QR Code <br>Lot No: <?=$_GET['rec']?></b></h5></td></tr>
	                                  <tr>
	                                    <th width="3%">No</th>
	                                    <th width="10%">WO No</th>
	                                    <th width="12%">Qr Code Key</th>
	                                    <th width="10%">Colors</th>
	                                    <th width="5%">Ex Fty Date</th>
	                                    <th width="5%">Sizes</th>
	                                    <th width="5%">Inseams</th>
	                                    <th width="5%">Rec Date</th>
	                                    <th width="5%">Login</th>
	                                    <th width="5%">User</th>
	                                  </tr>
	                                </thead>
	                                <tbody id="tampilmodcart">
	                                  	<?php include "sourcedatamoddetqr.php"; ?>
	                                </tbody>
	                              </table>
	                              <input type="hidden" name="getmoddetqr" id="getmoddetqr" value="option=View&task=receive&act=ugr_view">
	                            </div>
	                       
	                        </div>
	                    </div>
						<!-- <div class="col-md-12" align="center" style="margin-top: 2%;">
								<button id="btn-simpan" name="simpan" class="btn btn-primary" type="submit" <?=$clicksave?> value="app">
								 Submit
				                </button>
						</div>
						</div> -->
					</div>
				</form>
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
					<!-- end: page -->
				