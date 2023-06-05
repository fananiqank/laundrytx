<section class="panel">
  <div class="panel-body">
     <form class="form-user" id="formku" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>User <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="usercode" id="usercode" class="form-control" value="" required>
            </div>
            
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>WO No <font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocmt" id="nocmt" class="form-control" value="" required>
            </div>
            
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Colors<font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="nocolor" id="nocolor" class="form-control" value="" required>
            </div>
           
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Qty<font style="color:#ff0000;">*</font></b></label>
            <div class="col-md-4">
                <input type="text" name="label_qty" id="label_qty" class="form-control" value="" required>
            </div>
           
          </div>
          <div class="form-group">
              <label class="col-md-2 control-label" for="profileLastName"></label>
              <div class="col-md-4">

                <input  type="button" class="btn btn-info" value="Cetak" onclick='simpan(nocmt.value,nocolor.value,usercode.value,label_qty.value)'>
                <a href="content.php?option=Transaction&task=labelcon&act=ugr_laundry_addon" type="button" class="btn btn-default">Reset</a>
                </div>
          </div>
        
    </form>
 </div>
</section>

    
