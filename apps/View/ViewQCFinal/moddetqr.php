<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();

foreach($con->select("laundry_wo_master_dtl_proc","wo_no,garment_colors,ex_fty_date","wo_master_dtl_proc_id = '$_GET[lotno]'") as $ck){}

$selqctype = $con->select("laundry_scan_qrcode","scan_status_garment,COUNT(scan_status_garment) as jmlscan","wo_no = '$ck[wo_no]' and garment_colors = '$ck[garment_colors]' and ex_fty_date = '$ck[ex_fty_date]'  and scan_type = 3 GROUP BY scan_status_garment");

foreach($con->select("(
							(select COUNT(*) as good,wo_no,garment_colors,ex_fty_date from laundry_scan_qrcode where scan_type = 3 and scan_status_garment = 1 and wo_no = '$ck[wo_no]' and garment_colors = '$ck[garment_colors]' and ex_fty_date = '$ck[ex_fty_date]' GROUP BY wo_no,garment_colors,ex_fty_date) as CU
						LEFT JOIN 
							(select COUNT(*) as reject,wo_no,garment_colors,ex_fty_date from laundry_scan_qrcode where scan_type = 3 and scan_status_garment = 2 and wo_no = '$ck[wo_no]' and garment_colors = '$ck[garment_colors]' and ex_fty_date = '$ck[ex_fty_date]' GROUP BY wo_no,garment_colors,ex_fty_date,scan_id) as CE
								ON CU.wo_no=CE.wo_no and CU.garment_colors=CE.garment_colors and CU.ex_fty_date=CE.ex_fty_date
						LEFT JOIN 
							(select COUNT(*) as rework,wo_no,garment_colors,ex_fty_date from laundry_scan_qrcode where scan_type = 3 and scan_status_garment = 3 and wo_no = '$ck[wo_no]' and garment_colors = '$ck[garment_colors]' and ex_fty_date = '$ck[ex_fty_date]' GROUP BY wo_no,garment_colors,ex_fty_date,scan_id) as CA
								ON CU.wo_no=CA.wo_no and CU.garment_colors=CA.garment_colors and CU.ex_fty_date=CA.ex_fty_date
						) as groud","ISNULL(good,0) as good,ISNULL(reject,0) as reject,ISNULL(rework,0) as rework") 
as $scanned){}
?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
		
		<span class="separator"></span>
				<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
					<div class="row">
                    	 <div class="form-group">
	                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
	                            <div class="row" align="center">
	                            	<h4><b>Detail QC Final Data QR Code</b></h4>
	                            </div>
	                            <table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax3" style="font-size: 13px;">
	                            	<thead>
	                            		<tr>
	                                    	<th>QC Rec Good</th>
	                                    	<th>Scanned Good</th>
	                                    	<th>QC Rec Reject</th>
	                                    	<th>Scanned Reject</th>
	                                    	<th>QC Rec Rework</th>                                   	
	                                    	<th>Scanned Rework</th>
	                                    </tr>
	                            	</thead>
	                            	<tbody>
	                            		<tr align="right">
	                            			<td><?=$_GET['g']?></td>
	                            			<td><?=$scanned['good']?></td>
	                            			<td><?=$_GET['rj']?></td>
	                            			<td><?=$scanned['reject']?></td>
	                            			<td><?=$_GET['rw']?></td>
	                            			<td><?=$scanned['rework']?></td>
	                            		</tr>
	                            	</tbody>
	                            </table>
	                            <div class="pre-scrollable">
	                              <table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax3" style="font-size: 13px;">
	                                <thead>
	                                  <tr>
	                                    <th width="3%">No</th>
	                                    <th width="10%">WO No</th>
	                                    <th width="12%">Qr Code Key</th>
	                                    <th width="10%">Colors</th>
	                                    <th width="5%">Ex Fty Date</th>
	                                    <th width="5%">Sizes</th>
	                                    <th width="5%">Inseams</th>
	                                    <th width="5%">QC Date</th>
	                                    <th width="5%">Login</th>
	                                    <th width="5%">User</th>
	                                  </tr>
	                                </thead>
	                                <tbody id="tampilmodcart">
	                                  	<?php include "sourcedatamoddetqr.php"; ?>
	                                </tbody>
	                              </table>
	                              <input type="hidden" name="getmoddetqrc" id="getmoddetqrc" value="<?=$_GET[p]?>">
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
			
					<!-- end: page -->
				