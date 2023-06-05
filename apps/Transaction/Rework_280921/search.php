<div >
        <div class="form-group">
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>WO No</b></label>
            <div class="col-md-4">
                <input type="text" name="wo_no_show" id="wo_no_show" class="form-control" value="">
                <input type="hidden" name="wo_no" id="wo_no" class="form-control" value="">
            </div>
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>Colors</b></label>
            <div class="col-md-4">
                <input type="text" name="color_no_show" id="color_no_show" class="form-control" value="">
                <input type="hidden" name="color_no" id="color_no" class="form-control" value="">
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>Ex Fty Date</b></label>
            <div class="col-md-4">
                <input type="text" name="ex_fty_date" id="ex_fty_date" class="form-control" value="">
                <input type="hidden" name="ex_fty_date_asli" id="ex_fty_date_asli" class="form-control" value="">
            </div>
            
           <!--  <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>Shade</b></label>
            <div class="col-md-4">
                <select data-plugin-selectTwo class="form-control populate"  placeholder="Choose One" name="shade" id="shade">
                    <option value=""></option>
                    <?php 
                        $selshade = $con->select("laundry_lot_number","lot_shade","lot_shade ilike '%' GROUP_BY lot_shade");
                        foreach ($selshade as $shd) {
                            echo "<option value'lot_shade'>lot_shade</option>";
                        }
                        if($_GET['lastrec'] == '2'){ 
                            if($lot_no['lot_no']){
                    ?>
                                <option value="MS">Mix Shades</option>
                    <?php   } 
                        }
                    ?>
                </select>
            </div> -->
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Type</b></label>
            <div class="col-md-4">
                 <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="typelot" id="typelot">
                      <option value="">All</option>
                      <option value="F">FirstBulk</option>
                      <option value="N">Normal</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>User</b></label>
            <div class="col-md-4">
                <input type="text" name="usercode" id="usercode" class="form-control" value="">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 col-md-12 col-lg-12">
              <a href="content.php?p=<?=$_GET[p]?>" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" onClick="pindahData2(wo_no.value,color_no.value,ex_fty_date.value,typelot.value,usercode.value)">
            </div>
        </div>
</div>