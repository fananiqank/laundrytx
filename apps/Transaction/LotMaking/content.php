<?php
    //perpindahan menu
  $exppage = explode('_',$_GET['task']);
  $page = $exppage[0];  
  $middle = $exppage[1];
  $last = $exppage[2];  

      $selrolegrupmas2 = $con->select("laundry_role_wo_master","role_wo_master_id","role_wo_master_status = 1");
      foreach ($selrolegrupmas2 as $grupmas2) {}
?>
<section class="panel"> 
  <div class="panel-body" id="loaded">
    <?php 
    	if($last == 'v'){ ?>
	        <a href="content.php?option=Transaction&task=lot_making&act=ugr_transaction" class='btn btn-success' style='margin-bottom: 0%;float: right;'>Create Lot Number</a>
	        &emsp;
	        <span id="button_rec_lot">
	        	<?php include "button_rec_lot.php"; ?>
	        </span>
	        <hr>
      
<?php } else {?>
	        <a href="content.php?option=View&task=lotnumber&act=ugr_view" class='btn btn-success' style='margin-bottom: 0%;float: right;'>View Lot Number</a>
	        &emsp;
	    	<span id="button_rec_lot">
	        	<?php include "button_rec_lot.php"; ?>
	        </span>
<?php } ?>

    <form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">

        <?php 
          if ($last == 'v'){ // tidak digunakan karena perubahan menu
              include "view_lot.php";      
          } else {
        
        ?>  
            <div class="form-group">
                <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>User</b></label>
                  <div class="col-sm-5 col-md-5 col-lg-5">
                      <input id="usercode_lot" name="usercode_lot" value="" type="text" class="form-control" required>
                  </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Type</b></label>
                <div class="col-sm-5 col-md-5 col-lg-5">
                    <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="typework" id="typework" onclick="newrework(this.value)">
                        <option value=""></option>
                    <?php if ($last == 're') { ?>
                        <option value="2">Rework Sequence</option>
                    <?php }  else { ?>
                        <option value="1">New Sequence</option>    
                    <?php } ?>
                    </select>
                </div>
                <label class="col-sm-1 col-md-1 col-lg-1 control-label" for="profileLastName"><b>WO No</b></label>
                <div class="col-sm-4 col-md-4 col-lg-4">
                    <input id="showcmt" name="showcmt" value="" type="text" class="form-control" readonly>
                    <input id="idcmt" name="idcmt" value="" type="hidden" class="form-control" readonly>
                    <input id="rolecmt" name="rolecmt" value="" type="hidden" class="form-control" readonly>
                </div>
            </div>
            <div id="gruplotmaking" style="display: none">
                  <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Choose WO No</b></label>
                    <div class="col-sm-5 col-md-5 col-lg-5">
                      <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="nocmt" id="nocmt" onclick="clot(this.value,typework.value)">
                             
                      </select>
                    </div> 
                    <label class="col-sm-1 col-md-1 col-lg-1 control-label" for="profileLastName"><b>Colors</b></label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                      <input id="showcolors" name="showcolors" value="" type="text" class="form-control" readonly>                       
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Seq Lot making</b></label>
				            <div class="col-sm-5 col-md-5 col-lg-5">
				              <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="allseqlot" id="allseqlot" onclick="ccmt(nocmt.value,typework.value,this.value)">
				                     
				              </select>
				            </div> 
                    <label class="col-sm-1 col-md-1 col-lg-1 control-label" for="profileLastName"><b>XFty Date</b></label>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                          <input id="showexftydate" name="showexftydate" value="" type="text" class="form-control" readonly>                
                          <input id="showexftydateasli" name="showexftydateasli" value="" type="hidden" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                      	
                  </div>
                  <div class="form-group">
                        
                  </div>
                  <div class="form-group">
                        
                  </div>
                  <div class="form-group">
                        <label class="col-sm-12 col-md-12 col-lg-12 control-label" for="profileLastName"><b>WO Details</b></label>
                        <div class="col-sm-12 col-md-12 col-lg-12"  style="background-color: #FFFFFF;" id="tampilcmt">
                          
                        </div>
                  </div>
            </div>
            <hr>
                  <div class="form-group" align="center">
                      <div class="col-sm-2 col-md-2 col-lg-2">
                          &nbsp;
                      </div>
                      <div class="col-sm-2 col-md-2 col-lg-2">
                          &nbsp;
                      </div>
                      <div class="col-sm-2 col-md-2 col-lg-2">
                        <h4>Shade</h4>
                      </div>
                      <div class="col-sm-2 col-md-2 col-lg-2">
                        <h4>Pcs</h4>
                      </div>
                      <div class="col-sm-2 col-md-2 col-lg-2">
                        <h4>Kg</h4>
                      </div>
                      <div class="col-sm-2 col-md-2 col-lg-2">
                          &nbsp;
                      </div>
                  </div>
                  <div class="form-group">
				              <div class="col-sm-2 col-md-2 col-lg-2" id="tampilselectlot">

				              </div>
				              <div class="col-sm-2 col-md-2 col-lg-2" id="tampillot">
				                  
				              </div>
				              <div class="col-sm-2 col-md-2 col-lg-2" id="tampilshade">
				             	  
				              </div>
                      <div class="col-sm-2 col-md-2 col-lg-2">
				             	  <input id="pcs" name="pcs" type="text" placeholder="Pcs" class="form-control" onkeyup='cekpcs(this.value)' onkeydown='return hanyaAngka(this, event);' required>
				              </div>
                      <div class="col-sm-2 col-md-2 col-lg-2">
                        <input id="kg" name="kg" type="text" placeholder="Kg" class="form-control" onkeydown='return hanyaAngka(this, event);' required>
                      </div>
				              <div class="col-sm-2 col-md-2 col-lg-2" >
				             	  <a href="javascript:void(0)" class="btn btn-default" onclick="savelot()">Create Lot</a>
				              </div>
                  </div>
                      
                      <hr>
               
                      <input id="jenis" name="jenis" value="" type="hidden">
                      <input class="form-control" name="kode" id="kode" value="<?=$kode?>" type="hidden" />
                      <input class="form-control" name="getp" id="getp" value="content.php?option=<?php echo $_GET[option]?>&task=<?php echo $_GET[task]?>&act=<?php echo $_GET[act]?>" type="hidden" />
                      <input type="hidden" id="hal" name="hal" value="<?=$get?>">
                      <input type="hidden" name="no_style" id="no_style" value="">
                      <input type="hidden" name="no_cmt" id="no_cmt" value="">
                      <input type="hidden" name="no_color" id="no_color" value="">
                      <input type="hidden" name="no_buyer" id="no_buyer" value="">
                      <input type="hidden" name="ceknolot" id="ceknolot" value="">
                      <input type="hidden" name="typesequence" id="typesequence" value="">
                      <input type="hidden" name="rolewoid" id="rolewoid" value="">
                      <input type="hidden" name="seqlot" id="seqlot" value="">
                      <input type="hidden" name="nextseqlot" id="nextseqlot" value="">
        <?php } ?>
    </form>
  </div>
</section>

    
