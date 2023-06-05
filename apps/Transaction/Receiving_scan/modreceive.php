
<?php
session_start();
include "../../../funlibs.php";
$con = new Database();
//sequence
foreach($con->select("laundry_wo_master_dtl_proc","*,concat(wo_no,'_',trim(garment_colors),'_',ex_fty_date) datawono","wo_master_dtl_proc_id=$_GET[id]") as $recscan){}
//receive lot
foreach($con->select("laundry_receive","coalesce(sum(rec_qty),0) as recqty","wo_master_dtl_proc_id = $_GET[id]") as $recqty){}
$blncrectocut = (int)$recscan['cutting_qty']-(int)$recqty['recqty'];

foreach($con->select("(select COALESCE(sum(case when COALESCE(DATE(\"WASH_IN_GOOD\")::TEXT,'x')='x' then 0 else 1 end),0) totalbatch from qrcode_gdp a join qrcode_ticketing_detail b on a.ticketid=b.ticketid join qrcode_ticketing_master c on a.ticketid=c.ticketid where gdpbatch = '$_GET[gdp]' and wo_no = '$recscan[wo_no]' and trim(color) = '$recscan[garment_colors]' and step_id_detail = 4) a","*") as $totalbatch){}
$blncnow = (int)$recscan['cutting_qty']-((int)$recqty['recqty']+(int)$totalbatch['totalbatch']);
?>
<div class="col-lg-12 col-md-12">
		<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
			<input type="hidden" name="getp" id="getp" value="option=Transaction&task=receive_scan2&act=ugr_transaction" >
			<input type="hidden" name="getid" id="getid" value="<?=$_GET[id]?>" >
			<input type="hidden" name="rolemaster" id="rolemaster" value="<?=$recscan['role_wo_master_id']?>" >
			<input type="hidden" name="gdpbatch" id="gdpbatch" value="<?=$_GET[gdp]?>">
			<input type="hidden" name="confirm" id="confirm" value="1">
			<input type="hidden" name="uniqseq" id="uniqseq" value="">
			<input type="hidden" name="datawono" id="datawono" value="<?=$recscan['datawono']?>">
			<input type="hidden" name="usercode" id="usercode" value="<?=$_GET[usercode]?>" >
				<fieldset>
					
					<div class="form-group">
						<div class="col-md-12 col-lg-12" style="background-color: #FFFFFF" id="tampilproc">
							<?php include "sourcedatamoddetscan.php"; ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12 col-lg-12" style="background-color: #FFFFFF;text-align: center" id="proc">
							<a href="javascript:void(0)" name="submit" id="submit" onclick="savetoreceive()" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Submit</a>
						</div>					  			
					</div>
				</fieldset>	
		</form>
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
                          	<tr><td colspan="10" align="center"><h5><b>Detail Receive Data QR Code <br>GdpBatch: <?php echo $_GET['gdp']; ?></b></h5></td></tr>
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
                        <?php
                        $num=1;
                        $nim=1;
                        $selrechead = $con->select("(select d.wo_no,d.color,DATE(d.ex_fty_date) ex_fty_date,a.qrcode_key,d.inseam,d.size,gdp_datetime,login_name,a.user_code
from qrcode_gdp a 
join qrcode_ticketing_master d on a.ticketid=d.ticketid
where a.gdpbatch='$_GET[gdp]' and wo_no = '$recscan[wo_no]' and trim(color) = '$recscan[garment_colors]' and step_id_detail = 4) a","*","");
                      	foreach ($selrechead as $head) {					
						$expkey = explode('_',$head[qrcode_key]);
						$qrcode_key = $expkey[0]."_".$expkey[1]."_".$expkey[3];
						?>
						<tr>
							<th width="5%"><?php echo $num?></th>
							<th width="15%"><?php echo $head['wo_no']?></th>
							<th width="15%"><?php echo $qrcode_key?></th>
							<th width="5%"><?php echo $head['color']?></th>
							<th width="5%"><?php echo $head['ex_fty_date']?></th>
							<th width="5%"><?php echo $head['size']?></th>
							<th width="5%"><?php echo $head['inseam']?></th>
							<th width="5%"><?php echo $head['gdp_datetime']?></th>
							<th width="5%"><?php echo $head['login_name']?></th>
							<th width="5%"><?php echo $head['user_code']?></th>
							
						</tr>
						<?php 
							  $total += $nim;
							  $num++; 
							  
							}
						?>
						<tr>
							<th width="5%" colspan="10" style="text-align: center"><b>Total Qty</b> : <?=$total;?></th>
						</tr> 
	                	</tbody>
	                </table>
	            <input type="hidden" name="getmoddetqr" id="getmoddetqr" value="option=View&task=receive&act=ugr_view">
	            </div>
	                       
	        </div>
	</div>
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