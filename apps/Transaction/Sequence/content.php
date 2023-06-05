<section class="panel">
<?php
  //perpindahan menu
  $exppage = explode('_',$_GET['task']);
  $page = $exppage[0];  
  $last = $exppage[1]; 
  $selcmt = $con->select("laundry_wo_master_dtl_proc","*","wo_master_dtl_proc_id = '".$_GET['idm']."'");
  foreach ($selcmt as $cmt) {}

  // status sequence 
    if($cmt['wo_master_dtl_proc_status'] == 1){
      $statusseqedit = "New Sequence";
    } else {
      $statusseqedit = "Rework Sequence";
    }

  //mendapatkan jumlah wo rework di keranjang wo master.
  $countrework = $con->selectcount("laundry_wo_master_keranjang","wo_master_keranjang_id","status_seq = '2'");
  
  if($countrework > 0) {
    $statusseq = "<option value='2'>Rework</option>";
  } else {
    $statusseq = "<option value='1'>New</option>
                  <option value='2'>Rework</option>";
  }

  if($last == "v") { 
?>
      <a href="content.php?option=Transaction&task=sequence&act=ugr_transaction" class='btn btn-success' style='margin-bottom: 0%;'>Input Data</a>
      <a href="javascript:void(0)" class='btn btn-danger' style='margin-bottom: 0%;' onclick="viewdata('a')">Need Approve</a>
<?php 
       include "view_sequence.php";
  } else if($last == "a") { 
?>
      <a href="content.php?option=Transaction&task=sequence&act=ugr_transaction" class='btn btn-success' style='margin-bottom: 0%;'>Input Data</a>
      <a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('v')">View Data</a>
<?php 
       include "view_approve.php";
  } else { 
      if ($_GET['d'] == ''){ 
?>
        <a class='btn btn-warning' style='margin-bottom: 0%;' data-toggle='collapse' data-target='#demo'>Filter WO No</a>
<?php } ?>
        <a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('v')">View Data</a>
        <a href="javascript:void(0)" class='btn btn-danger' style='margin-bottom: 0%;' onclick="viewdata('a')">Need Approve</a>
<?php 
      $selrolegrupmas2 = $con->select("laundry_role_grup_master","role_grup_master_id","role_grup_master_status = 1");
      foreach ($selrolegrupmas2 as $grupmas2) {}

      $seltemplate = $con->select("laundry_role_wo_master A JOIN laundry_wo_master_dtl_proc B ON A.role_wo_master_id=B.role_wo_master_id","A.role_wo_master_id,A.role_wo_master_name,B.rework_seq","role_wo_master_status = 1 and DATE(role_wo_master_createdate) 
between (now() - interval '5 months')::date and (now() + interval '1 day')::date","wo_no,rework_seq");
?>
  <header class="panel-heading" id="search"> 
    	<?php if (!$_GET['idm']) { include "search.php"; } ?>
  </header>
  <hr>

  <div class="panel-body">
    
    <form class="form-user" id="formku" method="post" action="content.php?option=Transaction&task=simpan_sequence&act=ugr_transaction" enctype="multipart/form-data">
    			
    			<div class="form-group" id="contentwip">
            		<?php include "content_wip.php"; ?>
                </div>
                <hr>

<?php if($_GET['l'] != "a"){ ?>
                <div class="form-group">
                    <h4>Sequence Process &emsp; 
                    	<span id="addbutton"><?php include "addbutton.php"; ?></span>
                
<?php if ($_GET['d'] == ''){ ?>
                          <a href='javascript:void(0)' data-toggle='collapse' data-target='#coltemp' id='coptemp' class='label label-warning' style='font-size:12px;background-color:#FF0000;'>Copy Template</a>
<?php } ?>
                    </h4> 
                    <div class="collapse" id="coltemp">
                        <div class="col-sm-2 col-md-2 col-lg-2">
                               Choose Template
                        </div>           
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="temp_name" id="temp_name" onchange="temp(this.value)">
                                <option value=""></option>
<?php 
                                    foreach ($seltemplate as $stemp ) {
                                      if($stemp['rework_seq'] == 0) {
                                        $rewok = "(NEW)";
                                      } else {
                                        $rewok = "(REWORK ".$stemp['rework_seq'].")";
                                      }
?>                
                                        <option value="<?php echo $stemp[role_wo_master_id]; ?>"><?php echo $stemp['role_wo_master_name']." : ".$rewok; ?></option>
                                    <?php } ?>
                                </select>
                        </div>  
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="repeat_order" id="repeat_order">
                                <option value=""></option>
                                <option value="0">New</option>
                                <option value="1">Repeat Order</option>
                            </select>
                        </div>
                        <div class="col-sm-3 col-md-3 col-lg-3" style="display: block" id="buttontemplate">
                            <button type="submit" onClick="return confirm('are you sure Use Template??')" class="btn btn-warning">Use Template</button> &nbsp;
                            <a href="javascript:void(0)" onclick="resettemplate()" class="btn btn-default" id="reset">Reset</a>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                         		<hr>
                        </div> 
                    </div>                     
                </div>
                <div class="form-group" align="center" id="tampildata">

<?php 
                    if($_GET['idm']) { 
                        include "tampileditwo.php";
                    } else {
                        include "tampil.php"; 
                    }
?>

                </div>
<?php } ?>               
                <div class="form-group">
                    <h4>Washing Approve &emsp; <span style="float: right;"><input type="checkbox" value="1" onclick="cekapp(this)" id="checkwip"> All &emsp;</span></h4> 
                    <div class="row pre-scrollable" style="height: 200px;background-color: white; " id="tampilapp">
<?php 
                          include "tampilapp.php" 
?>
                    </div>
                </div>
                
                <div class="form-group">

                    <div class="col-sm-12 col-md-12 col-lg-12" align="center">
                    	<a href="javascript:void(0)" id="simpan-all" style="display: block;width: 20%;" name="simpan-all" class="btn btn-primary" type="submit" onClick="simpan('<?php echo $_GET[l]; ?>')">Submit</a>
                	</div>
                </div>
            </div>
                      <input type="hidden" id="naik" name="naik" value="">
                      <input type="hidden" id="turun" name="turun" value="" >
                      <input type="hidden" id="idnya" name="idnya" value="" >
                      <input type="hidden" id="idnyaedit" name="idnyaedit" value="" >
                      <input type="hidden" id="sub" name="sub" value="" >
                      <input type="hidden" id="cmtwo" name="cmtwo" value="" >
                      <input type="hidden" id="cmtwo2" name="cmtwo2" value="" >
                      <input type="hidden" id="temple" name="temple" value="">
                      <input type="hidden" id="isid" name="isid" value="<?php echo $_GET[d]; ?>" >
                      <input type="hidden" id="rolegrupmas2" name="rolegrupmas2" value="<?php echo $grupmas2[role_grup_master_id]; ?>">
                      <input type="hidden" name="kode" id="kode" value="<?php echo $kode; ?>" />
                      <input type="hidden" name="hal" id="hal" value="<?php echo $get; ?>">
                      <input type="hidden" name="no_style" id="no_style" value="">
                      <input type="hidden" name="no_cmt" id="no_cmt" value="">
                      <input type="hidden" name="no_color" id="no_color" value="" onchange="color(this.value)">
                      <input type="hidden" name="tanggal1" id="tanggal1" value="">
                      <input type="hidden" name="tanggal2" id="tanggal2" value="">
                      <input type="hidden" name="status_seq" id="status_seq" value="">
                      <input type="hidden" name="no_buyer" id="no_buyer" value="">
                      <input type="hidden" name="simpanan" id="simpanan" value="">
                      <input type="hidden" name="idm" id="idm" value="<?php echo $_GET[idm]; ?>">
                      <input type="hidden" name="getlast" id="getlast" value="<?php echo $_GET[l]; ?>" />
                      <input type="hidden" name="view" id="view" value="">
                      <input type="hidden" name="pager" id="pager" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" />
                      <input type="hidden" name="getidm" id="getidm" value="<?php echo $_GET[idm]; ?>">
                      <input type="hidden" name="editseqwo" id="editseqwo" value="" >
                      <input type="hidden" name="validasitime" id="validasitime" value="" >

                       
   </form>
  </div>
<?php } ?>

 <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
 <input class="form-control" name="getpage" id="getpage" value="<?php echo $page; ?>" type="hidden" />
</section>

    
