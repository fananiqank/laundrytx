<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();
$selrec = $con->select("laundry_wo_master","*","wo_no = '$_GET[wo]'");
foreach ($selrec as $wp) {}
$selwipe = $con->select("wip_dtl","COUNT(wip_dtl_id) as jmlwip","wip_dtl_status = '9'");
foreach ($selwipe as $wipe) {}
?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
		
		<span class="separator"></span>
				<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
					<div class="row">
                    	 <div class="form-group">
	                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
	                            <div class="row" align="center">
	                            <h4><b>Detail Scan Receive</b></h4>
	                            </div>
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
	                                  	<?php include "sourcedatamoddetscan.php"; ?>
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
				