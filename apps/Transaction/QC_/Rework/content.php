<section class="panel">
<?php
  //perpindahan menu
  $exppage = explode('_',$_GET['task']);
  $page = $exppage[0];  
  $last = $exppage[1];  
  $selcmt = $con->select("laundry_wo_master_dtl_proc","*","wo_master_dtl_proc_id = '".$_GET['idm']."'");
  foreach ($selcmt as $cmt) {}

  $selseqpro = $con->select("laundry_role_wo_master a join laundry_wo_master_dtl_proc b on a.role_wo_master_id = b.role_wo_master_id","a.role_wo_master_id,a.role_wo_master_name,rework_seq,wo_master_dtl_proc_id","b.wo_master_dtl_proc_status = '2'");
  
  $model = "onclick='model(1)'";

  if ($last == 'v'){
  		include "view_lot.php";
  } else {
?>

  <a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('v')">View Data</a>
  <hr>
  <form class="form-user" id="formku" method="post" enctype="multipart/form-data">
      <header class="panel-heading" id="search"> 
        	<?php include "search.php"; ?>
      </header>
      <div class="panel-body" >
        
        	<div class="form-group" id="contentwip">
                		<?php include "content_wip.php"; ?>
          </div>
          <div class="form-group" id="">
              <div class="col-sm-2 col-md-2 col-lg-2">
                  <b>Total Pcs</b>
              </div>
              <div class="col-sm-3 col-md-3 col-lg-3">
                  <input type="text" class="form-control" name="totalpcs" id="totalpcs" value="" readonly>
              </div>
              <div class="col-sm-2 col-md-2 col-lg-2">
                  <b>Total Kg</b>
              </div>
              <div class="col-sm-3 col-md-3 col-lg-3">
                  <input type="text" class="form-control" name="totalkg" id="totalkg" value="" onkeydown='return hanyaAngka(this, event);'>
              </div>
          </div>
          <hr>
          <div class="form-group">
            <h4>Sequence Process &emsp; </h4>
              <div class="col-sm-6 col-md-6 col-lg-6">
                  <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="seq_pro" id="seq_pro" onChange="seqsubmit(this.value,cmt_no.value,color_no.value,ex_fty_date_asli.value)" required>
                    <option value=""></option>
                    <?php 
                      foreach ($selseqpro as $seqpro ) {
                    ?>
                      <option value="<?php echo $seqpro[role_wo_master_id].'_'.$seqpro[wo_master_dtl_proc_id]; ?>"><?php echo $seqpro['role_wo_master_name'].' - Re '.$seqpro['rework_seq']; ?></option>
                    <?php } ?>
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
     <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
     <input class="form-control" name="getpage" id="getpage" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
  </form>

</section>

    
