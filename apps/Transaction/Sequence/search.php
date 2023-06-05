
<div id="demo" class="collapse in" >
    <hr>
        <div class="form-group">
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Denim</b></label>
            <div class="col-md-4">
                <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="denim_no" id="denim_no" onchange="denim(this.value)">
                      <option value=""></option>
                      <option value="1">Denim</option>
                      <option value="2">Non Denim</option>
                </select>
            </div>
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>New/Rework</b></label>
            <div class="col-md-4">
                <select data-plugin-selectTwo class="form-control populate"  name="status_seq" id="status_seq">
                      <?php echo $statusseq; ?>
                </select>
            </div>
        </div>
        <div id="groupdenim" style="display: none">
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Buyer</b></label>
                  <div class="col-md-4">
                      <input type="text" name="buyer_name" id="buyer_name" class="form-control" value="">
                      <input type="hidden" name="buyer_no" id="buyer_no" class="form-control" value="">
                  </div>
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Wo No</b></label>
                  <div class="col-md-4">
                      <input type="text" name="cmt_no" id="cmt_no" class="form-control" value="">
                  </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Style</b></label>
                  <div class="col-md-4">
                      <input type="text" name="style_no" id="style_no" class="form-control" value="">
                  </div>
                <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Colors</b></label>
                  <div class="col-md-4">
                      <input type="text" name="color_no" id="color_no" class="form-control" value="">
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Ex fty Date Periode</b></label>
                  <div class="col-md-4">
                      <i class="fa fa-calendar" style="height:30px;"></i>
                    <input type="text" id="tglship" name="tglship" data-plugin-datepicker style="border-radius:4px; -moz-border-radius:4px; height:30px;" value="<?php echo $gg; ?>" required>
                     <b>s / d</b> <br>
                     <i class="fa fa-calendar" style="height:30px;"></i>
                    <input type="text" id="tglship2" name="tglship2" data-plugin-datepicker style="border-radius:4px; -moz-border-radius:4px; height:30px;" value="<?php echo $wp; ?>" required>
                    
                  </div>
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Color Wash</b></label>
                  <div class="col-sm-4 col-md-4 col-lg-4">
                      <input type="text" name="color_wash" id="color_wash" class="form-control" value="">
                      <span style="font-size: 11px;"><b><i>*Manual Input</i></b></span>
                  </div>
              </div>
              <!-- <div class="form-group">
                        <label class="col-md-3 control-label"><b>Shipment Date Periode</b></label>
                        <div class="col-md-6">
                          <div class="input-daterange input-group" data-plugin-datepicker>
                            <span class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" class="form-control" id="tglship" name="tglship" value="<?=$gg?>">
                              <input id="tglsatu" name="tglsatu" value="<?=$_GET['tg']?>" type="hidden">
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control" id="tglship2" name="tglship2" value="<?=$wp?>">
                              <input id="tgldua" name="tgldua" value="<?=$_GET['tg2']?>" type="hidden">
                          </div>
                        </div>
                      </div> -->
              <div class="form-group">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <a href="javascript:void(0)" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default" onclick="Reset()">Reset</a>
                    <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" onClick="pindahData2(denim_no.value,buyer_no.value,style_no.value,cmt_no.value,color_no.value,tglship.value,tglship2.value,status_seq.value,color_wash.value)">
                    
                </div>
              </div>
        </div>
</div>