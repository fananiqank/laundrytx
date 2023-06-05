<?php 
          if ($last == 'v' || $_GET['task']=='lot'){ 
              include "view_lot.php";      
          } else {
        
        ?>      
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
            </div>
            <div id="gruplotmaking" style="display: none">
                  <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Choose WO No</b></label>
				            <div class="col-sm-5 col-md-5 col-lg-5">
				              <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="nocmt" id="nocmt" onclick="ccmt(this.value,typework.value)">
				                     
				              </select>
				             </div> 
                  </div>
                  <div class="form-group">
                      	<label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>WO No</b></label>
				                <div class="col-sm-5 col-md-5 col-lg-5">
				             	    <input id="showcmt" name="showcmt" value="" type="text" class="form-control" readonly>
                          <input id="idcmt" name="idcmt" value="" type="hidden" class="form-control" readonly>
                          <input id="rolecmt" name="rolecmt" value="" type="hidden" class="form-control" readonly>
				                </div>
                  </div>
                  <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Colors</b></label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                          <input id="showcolors" name="showcolors" value="" type="text" class="form-control" readonly>                       
                        </div>
                  </div>
                  <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Ex Fty Date</b></label>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                          <input id="showexftydate" name="showexftydate" value="" type="text" class="form-control" readonly>                       
                        </div>
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
                        <input id="kg" name="kg" type="text" placeholder="Kg" class="form-control" onkeyup='cekkg(this.value)' onkeydown='return hanyaAngka(this, event);' required>
                      </div>
				              <div class="col-sm-2 col-md-2 col-lg-2" >
				             	  <a href="javascript:void(0)" class="btn btn-default" onclick="savelot()">Create Lot</a>
				              </div>
                  </div>
                      
                      <hr>
                  <!-- <div class="form-group" align="center" id="tampildata">
                         	 <div class="col-sm-5 col-md-5 col-lg-5">
                         		<div class="col-sm-12 col-md-12 col-lg-12" style="border: solid;">
                         			&nbsp;
                         		</div>
                         	 </div>
                         	 <div class="col-sm-7 col-md-7 col-lg-7" align="left">
                         		<a href="javascript:void(0)" class="btn btn-default">Read NFC Card</a><br>
                         		<a href="javascript:void(0)" class="btn btn-default">Delete Card</a><br>
                         		<a href="javascript:void(0)" class="btn btn-default">Search CMT</a><br>
                         		<a href="javascript:void(0)" class="btn btn-warning">K</a>
                         	 </div>
                  </div> -->
                       
                     
                      <input id="jenis" name="jenis" value="" type="hidden">
                      <input class="form-control" name="kode" id="kode" value="<?=$kode?>" type="hidden" />
                      <input class="form-control" name="getp" id="getp" value="<?=$_GET[p]?>" type="hidden" />
                      <input type="hidden" id="hal" name="hal" value="<?=$get?>">
                      <input type="hidden" name="no_style" id="no_style" value="">
                      <input type="hidden" name="no_cmt" id="no_cmt" value="">
                      <input type="hidden" name="no_color" id="no_color" value="">
                      <input type="hidden" name="no_buyer" id="no_buyer" value="">
                      <input type="hidden" name="ceknolot" id="ceknolot" value="">
                      <input type="hidden" name="typesequence" id="typesequence" value="">
        <?php } ?>