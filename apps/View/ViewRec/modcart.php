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
	                              <input type="hidden" name="getpmodcart" id="getpmodcart" value="<?=$_GET[p]?>">
	                            </div>
	                           	<!-- <div class="col-sm-12 col-md-12 col-lg-12">
	                            	&nbsp;
	                        	</div>
	                            <div class="col-sm-1 col-md-1 col-lg-1">
	                            	<b>Note:</b>
	                        	</div>
	                            <div class="col-sm-4 col-md-4 col-lg-4">
	                            	<textarea class="form-control" id="note" name="note"></textarea>
	                        	</div>
	                        	 <div class="col-sm-2 col-md-2 col-lg-2">
	                            	<b>Cut Qty:</b>
	                        	</div>
	                        	<div class="col-sm-2 col-md-2 col-lg-2">
	                        		<input type="text" name="cutqty" id="cutqty" class="form-control" value="<?=$cutproc[cutting_qty]?>"  onkeydown='return hanyaAngka(this, event)'>
	                        	</div>
	                        	<div class="col-sm-3 col-md-3 col-lg-3">
	                        		<input type="checkbox" name="lastrec" id="lastrec" value="1">&nbsp;<b>Last Receive</b>
	                        	</div>
	                        	<div class="col-sm-12 col-md-12 col-lg-12">
	                            	&nbsp;
	                        	</div> -->
	                        <?php if($wipe['jmlwip'] > 0) { ?>
	                        	<div class="col-sm-12 col-md-12 col-lg-12" align="center" id="sumbit" style="display: block">
	                        		<a href="javascript:void(0)" class="btn btn-primary" onclick="saverec(<?=$num?>)">Submit</a>
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
				