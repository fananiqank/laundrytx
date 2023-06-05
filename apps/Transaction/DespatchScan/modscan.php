<?php
session_start();
include "../../../funlibs.php";
$con = new Database;

foreach ($seltotscan = $con->select("laundry_scan_qrcode","wo_no,garment_colors,garment_inseams,garment_sizes,SUM(scan_qty) as totqty","scan_status = '0' and scan_createdby = '$_SESSION[ID_LOGIN]' and scan_type = '$_GET[type]' and lot_no = '$_GET[lot]' GROUP BY wo_no,garment_colors,garment_inseams,garment_sizes") as $hslscan ){}

//mendapatkan cutting qty
foreach ($selcutqty = $con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id,cutting_qty,wo_master_dtl_proc_qty_rec","wo_no = '$hslscan[wo_no]' and garment_colors = '$hslscan[garment_colors]'") as $cutqty ){}

	$datawono = $hslscan['wo_no'].'_'.$hslscan['garment_colors'];

?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
		
		<span class="separator"></span>
				<form class="form-user" id="formku" method="post" action="content.php?p=<?=$_GET[p]?>_s" enctype="multipart/form-data">
					<div class="row">
                    	 <div class="form-group">
	                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
	                        	
	                            <div class="row" align="center">
	                            <h4><b>Cart Data</b></h4>
	                            </div>
	                        <?php if($_GET['type'] == 4) { ?>
	                            <div class="row" style="margin-left: 4%;">
	                            	<h5><b>No. Lot : <?php echo $_GET['lot']; ?></b></h5>
	                            </div>
	                        <?php } ?>
	                           <!--  <div class="pre-scrollable"> -->
	                              <table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax3" style="font-size: 13px;">
	                                <thead>
	                                  <tr>
	                                    <th width="5%" style="text-align: center">No</th>
	                                    <th width="15%">No CMT</th>
	                                    <th width="15%">Colors</th>
	                                    <th width="5%">Inseams</th>
	                                    <th width="5%">Sizes</th>
	                                    <!-- <th width="10%">Seq Cutting</th> -->
	                                    <th width="5%">Qty</th>
	                                  </tr>
	                                </thead>
	                                <tbody id="tampilmodcart">
	                                  	<?php include "sourcedatamodcart.php"; ?>
	                                </tbody>
	                              </table>
	                              <input type="hidden" name="confirm" id="confirm" value="">
	                              <input type="hidden" name="getpmod" id="getpmod" value="<?=$_GET[p]?>">
	                              <input type="hidden" name="nolot" id="nolot" value="<?=$_GET[lot]?>">
	                              <input type="hidden" name="datawono" id="datawono" value="<?=$datawono?>">
	                              <input type="hidden" name="idlast" id="idlast" value="">
	                              <input type="hidden" name="womasterdtlprocid" id="womasterdtlprocid" value="<?=$cutqty[wo_master_dtl_proc_id]?>">
	                              <input type="hidden" name="types" id="types" value="<?=$_GET[type]?>">
	                              <input type="hidden" name="role_wo_id" id="role_wo_id" value="<?=$_GET[rolewoid]?>">
	                              <input type="hidden" name="typelot" id="typelot" value="<?=$_GET[typelot]?>">
	                            <!-- </div> -->
	                            <div class="col-sm-12 col-md-12 col-lg-12" align="center" style="display: block">
	                        

	                        	<?php if ($totalall != '' && $totalall == $_GET['qtylast']){ ?>
	                        		<div class="col-sm-12 col-md-12 col-lg-12" id="sumbit"><a href="javascript:void(0)" class="btn btn-primary" onclick="savetoreceive('4')">Submit</a></div>
	                        	<?php } ?>
	                        	
	                        	</div>
	                            <div class="col-sm-4 col-md-4 col-lg-4" id="sumbit" style="display: block;vertical-align: middle">
	                        		
	                        	</div>
	                        </div>
	                      </div>
					</div>
				</form>
			
					<!-- end: page -->
				