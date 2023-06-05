<section class="panel">
<?php
  //perpindahan menu
  $exppage = explode('_',$_GET['task']);
  $page = $exppage[0];  
  $last = $exppage[1];  
  $selcmt = $con->select("laundry_wo_master_dtl_proc","*","wo_master_dtl_proc_id = '".$_GET['idm']."'");
  foreach ($selcmt as $cmt) {}

  foreach($con->select("laundry_rework_tmp","sum(lot_qty) as sumpcs")as $spcs){}
  
  $model = "onclick='model(1)'";

  if ($last == 'v'){
  		include "view_lot.php";
  } else {
?>

  <!-- <a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('v')">View Data</a>
  <hr> -->
  <div class="form-group" id="button_rec_lot">
      <?php include "button_rec_lot.php"; ?>
  </div>
  <hr>
  <form class="form-user" id="formku" method="post" enctype="multipart/form-data">
      
      <!-- <header class="panel-heading" id="search">  -->
        	<?php include "search.php"; ?>
      <!-- </header> -->
      <div class="panel-body" >
        
        	<div class="form-group" id="contentwip">
                		<?php //include "tampilcmt2.php"; ?>
          </div>
          <div class="form-group" id="">
              <div class="col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                  <b>Total Pcs</b>
              </div>
              <div class="col-sm-2 col-md-2 col-lg-2" id="tampiltotalpcs">
                  <?php include "totalpcs.php"; ?>
              </div>
              <div class="col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                  <b>Total Kg</b>
              </div>
              <div class="col-sm-2 col-md-2 col-lg-2">
                  <input type="text" class="form-control" name="totalkg" id="totalkg" value="" onkeydown='return hanyaAngka(this, event);'>
              </div>
              <div class="col-sm-2 col-md-2 col-lg-2" style="text-align: right;">
                  <b>Shade</b>
              </div>
              <div class="col-sm-2 col-md-2 col-lg-2">
                  <!-- <select data-plugin-selectTwo class="form-control populate"  placeholder="Choose One" name="shade" id="shade">
                    <option value=""></option>
                      <?php 
                          $selshade = $con->select("laundry_lot_number","lot_shade","lot_shade ilike '%' GROUP BY lot_shade");
                          foreach ($selshade as $shd) {
                              echo "<option value'$shd[lot_shade]'>$shd[lot_shade]</option>";
                          }
                          // if($_GET['lastrec'] == '2'){ 
                          //     if($lot_no['lot_no']){
                      ?>
                             <option value="MS">Mix Shades</option>
                      <?php   //} 
                          //}
                      ?>
                  </select> -->
                  <input type="text" class="form-control" name="shade" id="shade">
              </div>
          </div>
          <hr>
          <div class="form-group">
            <h4>Sequence Process &emsp; </h4>
              <div class="col-sm-6 col-md-6 col-lg-6">
                  <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="seq_pro" id="seq_pro" onchange="seqsubmit(this.value,wo_no.value,color_no.value,ex_fty_date_asli.value)" required>
                    
                  </select>
              </div>
               <div class="col-sm-1 col-md-1 col-lg-1">
                   &nbsp;
              </div>
              <div class="col-sm-5 col-md-5 col-lg-5" id="lotnoshow">
              </div>
          </div>            
          <div class="form-group">
            <div class="col-sm-12 col-md-12 col-lg-12" align="center">
                <a href="javascript:void(0)" id="simpan_all" name="simpan_all" style="display: none;width: 20%;"  class="btn btn-primary" onClick="simpan()">Submit</a>
            </div>
          </div>
            <input type="hidden" name="ceklot" id="ceklot" value="">
            
       
      </div>
    <?php } ?>
     <input class="form-control" name="getp" id="getp" value="option=Transaction&task=rework&act=ugr_transaction" type="hidden" />
     <input class="form-control" name="getpage" id="getpage" value="<?=$page?>" type="hidden" />
  </form>

</section>

    
