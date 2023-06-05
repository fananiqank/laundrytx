<?php
session_start();
include "../../../funlibs.php";
$con = new Database;

$typelot = 2;

?>
		<span class="separator"></span>
				<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
					<div class="row">
                    	 <div class="form-group">
	                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
	                        	
	                            <div class="row" align="center">
	                            <h4><b>Cart Data</b></h4>
	                            </div>
	                       
	                            <div class="row" style="margin-left: 4%;">
	                            	<h5><b>No. Lot : <?php echo $_GET['lot']; ?></b></h5>
	                            </div>
	                     	                           <!--  <div class="pre-scrollable"> -->
	                            <table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax3" style="font-size: 13px;">
	                                <thead>
	                                  <tr>
	                                    <th width="3%" style="text-align: center">No</th>
	                                    <th width="15%">WO No</th>
	                                    <th width="15%">Colors</th>
	                                    <th width="10%">EX Fty Date</th>
	                                    <th width="10%">No Lot</th>
	                                    <th width="7%" align="center">Qty</th>
	                                    <th width="3%">Act</th>
	                                  </tr>
	                                </thead>
	                                <tbody id="tampilmodcart">
	                                  	<?php include "sourcedatamodqc.php"; ?>
	                                </tbody>
	                            </table>

		                        <input type="hidden" name="conf" id="conf" value="">
		                        <input type="hidden" name="getpmod" id="getpmod" value="<?=$_GET[p]?>">
	                           
	                            <div id="sumbit" class="col-sm-12 col-md-12 col-lg-12" align="center" style="display: block">
	                            <?php if ($totalall != ''){ ?>
	                        			<div class="col-sm-12 col-md-12 col-lg-12" id="sumbit"><a href="	javascript:void(0)" class="btn btn-primary" onclick="savetoreceivemanual('<?=$_GET[type]?>')">Submit</a></div>
	                        	<?php } ?>
	                        	</div>
	                            <div class="col-sm-4 col-md-4 col-lg-4" id="sumbit" style="display: block;vertical-align: middle">
	                        	</div>
	                        </div>
	                      </div>
					</div>
				</form>
			
					<!-- end: page -->
				