<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();
$selectdata = $con->select("laundry_wo_master_dtl_proc","*","");

?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
		
		<span class="separator"></span>
				<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
					<div class="row">
                    	 <div class="form-group">
	                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
	                        	
	                            <div class="row" align="center">
	                            <h4><b>Cart Despatch Data</b></h4>
	                            </div>
	                            <div class="pre-scrollable">
	                              <table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax3" style="font-size: 13px;">
	                                <thead>
	                                  <tr>
	                                    <th width="5%">No</th>
	                                    <th width="15%">WO No</th>
	                                    <th width="15%">Colors</th>
	                                    <th width="10%">Ex_Fty_Date</th>
	                                    <th width="5%">Inseams</th>
	                                    <th width="5%">Sizes</th>
	                                    <th width="5%">Qty Good</th>
	                                    <th width="5%">Act</th>
	                                  </tr>
	                                </thead>
	                                <tbody id="tampilmodcart">
	                                  	<?php include "sourcedatamodcart.php"; ?>
	                                </tbody>
	                              </table>
	                              <input type="hidden" name="confmodcart" id="confmodcart" value="">
	                              <input type="hidden" name="hpsmodcart" id="hpsmodcart" value="">
	                              <input type="hidden" name="getpmodcart" id="getpmodcart" value="option=Transaction&task=despatch&act=ugr_transaction">
	                              <input type="hidden" name="typelot" id="typelot" value="<?=$_GET[typelot]?>">
	                              <input type="hidden" name="typelot" id="typelot" value="<?=$_GET[usercode]?>">
	                            </div>
	                           
	                        </div>
	                    </div>
						
					</div>
				</form>
			
					<!-- end: page -->
				