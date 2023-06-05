<div >
        <div class="form-group">
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>WO No</b></label>
            <div class="col-md-4">
                <input type="text" name="cmt_no" id="cmt_no" class="form-control" value="">
            </div>
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>Colors</b></label>
            <div class="col-md-4">
                <input type="text" name="color_no" id="color_no" class="form-control" value="">
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>Ex Fty Date</b></label>
            <div class="col-md-4">
                <input type="text" name="ex_fty_date" id="ex_fty_date" class="form-control" value="">
                <input type="hidden" name="ex_fty_date_asli" id="ex_fty_date_asli" class="form-control" value="">
            </div>
            
            <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><font style="color:#ff0000;">*</font><b>Shade</b></label>
            <div class="col-md-4">
                <select data-plugin-selectTwo class="form-control populate"  placeholder="Choose One" name="shade" id="shade">
                    <option value=""></option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <?php 
                        if($_GET['lastrec'] == '2'){ 
                            if($lot_no['lot_no']){
                    ?>
                                <option value="MS">Mix Shades</option>
                    <?php   } 
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
             <label class="col-sm-2 col-md-2 col-lg-2 control-label" for="profileLastName"><b>Type</b></label>
            <div class="col-md-4">
                 <select data-plugin-selectTwo class="form-control populate"  placeholder="None Selected" name="typelot" id="typelot" onchange="denim(this.value)">
                      <option value="">All</option>
                      <option value="R">Reject</option>
                      <option value="W">Rework</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 col-md-12 col-lg-12">
              <a href="content.php?p=<?=$_GET[p]?>" style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-default">Reset</a>
                <input style="height:30px; line-height: 0;float: right; padding: 2%;" type="button" class="btn btn-info" value="Search" onClick="pindahData2(cmt_no.value,color_no.value,ex_fty_date.value,typelot.value,shade.value)">
            </div>
        </div>
</div>