
<div id="demo" class="collapse in" >
    <hr>
        <div id="groupdenim">
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Wo No</b></label>
                  <div class="col-md-4">
                      <input type="text" name="cmt_no" id="cmt_no" class="form-control" value="">
                  </div>
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>EX Fty Date</b></label>
                  <div class="col-md-4">
                      <input type="text" name="ex_fty_date" id="ex_fty_date" class="form-control" value="">
                      <input type="hidden" id="ex_fty_date_asli" name="ex_fty_date_asli" class="form-control" required>
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Colors</b></label>
                  <div class="col-md-4">
                      <input type="text" name="color_no" id="color_no" class="form-control" value="">
                  </div>
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>&nbsp;</b></label>
                  <div class="col-sm-4 col-md-4 col-lg-4">
                      &nbsp;
                      <input type="hidden" name="color_wash" id="color_wash" class="form-control" value="">
                     <!--  <span style="font-size: 11px;"><b><i>*Manual Input</i></b></span> -->
                  </div>
              </div>
             <!--  <div class="form-group">
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Inseam</b></label>
                  <div class="col-md-4"> -->
                      <input type="hidden" name="inseam" id="inseam" class="form-control" value="">
                 <!--  </div>
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Size</b></label>
                  <div class="col-sm-4 col-md-4 col-lg-4"> -->
                      <input type="hidden" name="sz" id="sz" class="form-control" value="">
                  <!-- </div>
              </div> -->
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Receive Qty</b></label>
                  <div class="col-md-4">
                      <input type="text" name="rcv_qty" id="rcv_qty" class="form-control" value="">
                  </div>

              </div>


              <div class="form-group">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <!-- <a href="javascript:void(0)" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default" onclick="Reset()">Reset</a> -->
                    <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Add" onClick="addreceive()">
                    
                </div>
              </div>
        </div>
</div>