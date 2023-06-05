<?php
session_start();
include "../../../funlibs.php";
$con = new Database;

$selstatusgrm = $con->select("laundry_scan_qrcode","scan_status_garment","scan_status = '0' and scan_createdby = '".$_SESSION['ID_LOGIN']."' and scan_type = '".$_GET['type']."' GROUP BY scan_status_garment");

foreach ($seltotscan = $con->select("laundry_scan_qrcode","wo_no,garment_colors,garment_inseams,garment_sizes,scan_status_garment,SUM(scan_qty) as totqty,DATE(ex_fty_date) as ex_fty_date","scan_status = '0' and scan_createdby = '".$_SESSION['ID_LOGIN']."' and scan_type = '".$_GET['type']."' GROUP BY wo_no,garment_colors,ex_fty_date,garment_inseams,garment_sizes,scan_status_garment") as $hslscan ){}

$subs= substr($_GET['lot'], -4,1);
if ($subs == 'A'){
	$typelot = 1;
} else {
	$typelot = 2;
}
//mendapatkan role_Wo_id
foreach ($rolewoidchild = $con->select("laundry_role_child a JOIN laundry_role_wo b ON a.role_wo_id=b.role_wo_id","role_child_id,role_wo_id,role_dtl_wo_id,role_wo_master_id","lot_no = '$_GET[lot]' and master_type_process_id=3 and role_child_status = 0")as $rolechild){}

//mendapatkan id
foreach ($selcutqty = $con->select("laundry_wo_master_dtl_proc","wo_master_dtl_proc_id,cutting_qty,wo_master_dtl_proc_qty_rec,wo_master_dtl_proc_status,rework_seq","wo_no = '".$hslscan['wo_no']."' and garment_colors = '".$hslscan['garment_colors']."' and DATE(ex_fty_date) = '".$hslscan['ex_fty_date']."'") as $cutqty ){}

$datawono = $hslscan['wo_no'].'_'.$hslscan['garment_colors'].'_'.$hslscan['ex_fty_date'];

?>
		<span class="separator"></span>
				<form class="form-user" id="formku" method="post" action="content.php?p=<?php echo $_GET[p]; ?>_s" enctype="multipart/form-data">
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
	                                    <th width="5%" style="text-align: center">No</th>
	                                    <th width="15%">WO No</th>
	                                    <th width="10%">Colors</th>
	                                    <th width="10%">Ex Fty Date</th>
	                                    <th width="5%">Sizes</th>
	                                    <th width="5%">Inseams</th>
	                                    <th width="5%">Qty Receive</th>
	                                  </tr>
	                                </thead>
	                                <tbody id="tampilmodcart">
	                                  	<?php include "sourcedatamodcart_pro.php"; ?>
	                                </tbody>
	                            </table>
		                              <input type="hidden" name="confirm" id="confirm" value="">
		                              <input type="hidden" name="getpmod" id="getpmod" value="<?php echo $_GET[p]; ?>">
		                              <input type="hidden" name="datawono" id="datawono" value="<?php echo $datawono; ?>">
		                              <input type="hidden" name="idlast" id="idlast" value="">
		                              <input type="hidden" name="womasterdtlprocid" id="womasterdtlprocid" value="<?php echo $cutqty[wo_master_dtl_proc_id]; ?>">
		                              <input type="hidden" name="types" id="types" value="<?php echo $_GET[type]; ?>">
		                              <input type="hidden" name="noreceive" id="noreceive" value="<?php echo $_GET[lot]; ?>">
		                              <input type="hidden" name="role_dtl_wo_id" id="role_dtl_wo_id" value="<?php echo $rolechild[role_dtl_wo_id]; ?>">
		                              <input type="hidden" name="role_wo_id" id="role_wo_id" value="<?php echo $rolechild[role_wo_id]; ?>">
		                              <input type="hidden" name="role_wo_master_id" id="role_wo_master_id" value="<?php echo $rolechild[role_wo_master_id]; ?>">
		                              <input type="hidden" name="role_child_id" id="role_child_id" value="<?php echo $rolechild[role_child_id]; ?>">
		                              <input type="hidden" name="typelot" id="typelot" value="<?php echo $typelot; ?>">
		                              <input type="hidden" name="createtype" id="createtype" value="<?php echo $cutqty[wo_master_dtl_proc_status]; ?>">
		                              <input type="hidden" name="reworkseq" id="reworkseq" value="<?php echo $cutqty[rework_seq]; ?>">
		                              <input type="hidden" name="mastertypelotid" id="mastertypelotid" value="<?php echo $subs; ?>">
		                              <input type="hidden" name="usercode" id="usercode" value="<?php echo $_GET[usercode]; ?>">
		                              <input type="hidden" name="wo_no" id="wo_no" value="<?php echo $hslscan[wo_no]; ?>">
		                              <input type="hidden" name="colors" id="colors" value="<?php echo $hslscan[garment_colors]; ?>">
		                              <input type="hidden" name="ex_fty_date" id="ex_fty_date" value="<?php echo $hslscan[ex_fty_date]; ?>">


	                            <!-- </div> -->
	                            <div class="col-sm-12 col-md-12 col-lg-12" align="center" style="display: block">
	                            <?php if ($supertotalall == $_GET['qtylast']){ ?>
	                        			<div class="col-sm-12 col-md-12 col-lg-12" id="sumbit"><a href="javascript:void(0)" class="btn btn-primary" onclick="savetoreceiveqc('<?=$_GET[type]?>')">Submit</a></div>
	                        	<?php } ?>
	                        	</div>
	                            <div class="col-sm-4 col-md-4 col-lg-4" id="sumbit" style="display: block;vertical-align: middle">
	                        	</div>
	                        </div>
	                      </div>
					</div>
				</form>
			
					<!-- end: page -->
				