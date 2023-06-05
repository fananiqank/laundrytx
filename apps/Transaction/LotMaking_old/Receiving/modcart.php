<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();
$selrec = $con->select("laundry_wo_master","*","wo_no = '".$_GET['wo']."'");
foreach ($selrec as $wp) {}
$selwipe = $con->select("wip_dtl","COUNT(wip_dtl_id) as jmlwip","wip_dtl_status = '9'");
foreach ($selwipe as $wipe) {}
?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
		
		<span class="separator"></span>
				<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
					<div class="row">
                    	 <div class="form-group">
	                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
	                        	
	                            <div class="row" align="center">
	                            <h4><b>Cart Receive Data</b></h4>
	                            </div>
	                            <div class="pre-scrollable">
	                              <table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax3" style="font-size: 13px;">
	                                <thead>
	                                  <tr>
	                                    <th width="5%">No</th>
	                                    <th width="15%">WO No</th>
	                                    <th width="15%">Colors</th>
	                                    <th width="5%">Inseams</th>
	                                    <th width="5%">Sizes</th>
	                                    <th width="5%">Ex Fty Date</th>
	                                    <th width="5%">Qty Send</th>
	                                    <th width="5%">Qty Receive</th>
	                                    <th width="5%">Qty Good</th>
	                                    <th width="5%">Qty Reject</th>
	                                    <th width="5%">Act</th>
	                                  </tr>
	                                </thead>
	                                <tbody id="tampilmodcart">
	                                  	<?php include "sourcedatamodcart.php"; ?>
	                                </tbody>
	                              </table>
	                              <input type="hidden" name="confmodcart" id="confmodcart" value="">
	                              <input type="hidden" name="hpsmodcart" id="hpsmodcart" value="">
	                              <input type="hidden" name="getpmodcart" id="getpmodcart" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>">
	                            </div>
	                          
	                        <?php if($jmldtl > 0) { ?>
	                        	<div class="col-sm-12 col-md-12 col-lg-12" align="center" id="sumbit" style="display: block">
	                        		<a href="javascript:void(0)" class="btn btn-primary" onclick="saverec(<?php echo $num; ?>)">Submit</a>
	                        	</div>
	                        	<input type="hidden" name="idlast" id="idlast" value="">
	                        <?php } ?>
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
				