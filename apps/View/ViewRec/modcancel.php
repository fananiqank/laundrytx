<?php
session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();
// echo "select * from (select a.rec_no as lot_no,a.rec_qty as lot_qty,a.wo_no,a.garment_colors,b.color_wash,DATE(a.ex_fty_date) as ex_fty_date,b.wo_master_dtl_proc_id from laundry_receive a JOIN laundry_wo_master_dtl_proc b using(wo_master_dtl_proc_id) where a.rec_no = '$_GET[lot]' UNION select a.lot_no,a.lot_qty,a.wo_no,a.garment_colors,b.color_wash,DATE(b.ex_fty_date) as ex_fty_date,b.wo_master_dtl_proc_id from laundry_lot_number a JOIN laundry_wo_master_dtl_proc b using(wo_master_dtl_proc_id) where a.lot_no = '$_GET[lot]') ";
$selrec = $con->select("(select a.rec_no as lot_no,a.rec_qty as lot_qty,a.wo_no,a.garment_colors,b.color_wash,DATE(a.ex_fty_date) as ex_fty_date,b.wo_master_dtl_proc_id,a.rec_id as lot_id,1 as lotep from laundry_receive a JOIN laundry_wo_master_dtl_proc b using(wo_master_dtl_proc_id) where a.rec_no = '$_GET[lot]' UNION select a.lot_no,a.lot_qty,a.wo_no,a.garment_colors,b.color_wash,DATE(b.ex_fty_date) as ex_fty_date,b.wo_master_dtl_proc_id,a.lot_id,2 as lotep from laundry_lot_number a JOIN laundry_wo_master_dtl_proc b using(wo_master_dtl_proc_id) where a.lot_no = '$_GET[lot]') as asi","*");
foreach ($selrec as $rec) {}
?>

<!-- Theme CSS -->
		<!-- Vendor CSS -->
<script type="text/javascript">
	$("#usercode").focus();
</script>		
<span class="separator"></span>
<form class="form-user" id="formku" method="post" enctype="multipart/form-data">
	<div class="row">
    	 <div class="form-group">
            <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
            	
                <div class="row" align="center">
                <h4><b>Cancel Lot</b></h4>
                </div>
                <div class="pre-scrollable">
                  <table class="table datatable-basic table-bordered table-striped table-hover pre-scrollable" width="100%" id="datatable-ajax3" style="font-size: 13px;">
                    <thead>
                      <tr>
                        <th width="10%">Lot</th>
                        <th width="10%">WO No</th>
                        <th width="15%">Color EIS</th>
                        <th width="10%">Color Wash</th>
                        <th width="10%">Ex Fty date</th>
                        <th width="10%">Qty</th>
                      </tr>
                    </thead>
                    <tbody >
                    	<tr>
                    		<td><?=$_GET['lot']?></td>
                    		<td><?=$rec['wo_no']?></td>
                    		<td><?=$rec['garment_colors']?></td>
                    		<td><?=$rec['color_wash']?></td>
                    		<td><?=$rec['ex_fty_date']?></td>
                    		<td><?=$rec['lot_qty']?></td>
                    	</tr>
                    </tbody>
                  </table>
                  <input type="hidden" name="wo_master_dtl_proc_id" id="wo_master_dtl_proc_id" value="<?=$rec[wo_master_dtl_proc_id]?>">
                  <input type="hidden" name="lot_no" id="lot_no" value="<?=$_GET[lot]?>">
                  <input type="hidden" name="lotid" id="lotid" value="<?=$rec[lot_id]?>">
                  <input type="hidden" name="type_cancel" id="type_cancel" value="<?=$_GET[typecancel]?>">
                  <input type="hidden" name="lot_qty" id="lot_qty" value="<?=$rec[lot_qty]?>">
                  <input type="hidden" name="lotep" id="lotep" value="<?=$rec[lotep]?>">
                  <input type="hidden" name="getpmodcart" id="getpmodcart" value="<?=$_GET[p]?>">
                </div>
                <hr>
            </div>
            <div class="form-group">
                <label class="col-md-2">User</label>
                <div class="col-md-5">
                	<input type="text" class="form-control" id="usercode" name="usercode">
            	</div>
            	<div class="col-sm-5 col-md-5 col-lg-5" align="center" id="sumbit" style="display: block">
            		<a href="javascript:void(0)" class="btn btn-primary" onclick="cancelprocess('<?=$_GET[lot]?>')">Submit</a>
            	</div>
            	<input type="hidden" name="idlast" id="idlast" value="">
            </div>
            <div class="form-group">
                <label class="col-md-2">Remark</label>
                <div class="col-md-5">
                    <textarea type="text" class="form-control" id="cancelremark" name="cancelremark"></textarea>
                </div>
            </div>
        </div>
	</div>
</form>
