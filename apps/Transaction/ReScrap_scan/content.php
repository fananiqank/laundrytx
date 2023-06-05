<section class="panel">
    <div class="panel-body">
      <div class="form-group">
          <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
              <div class="row">
                <div class="col-sm-2"> 
                  <b> Type Sequence:</b>
                </div>
                <div class="col-sm-3">
                    <select data-plugin-selectTwo class="form-control populate"  name="status_seq" id="status_seq" onchange="changestatus(this.value)">
                        <option value='1' <?php if($_GET[type] == 1){echo "selected";} ?>>New</option>
                        <option value='2' <?php if($_GET[type] == 2){echo "selected";} ?>>Rework</option>
                    </select>
                </div>
                <div class="col-sm-1">&nbsp;</div>
                <div class="col-sm-1"> 
                  <b> User :</b>
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="usercode" id="usercode" placeholder="fill user">
                </div>
              </div>
              <hr>
              <div class="row" align="center">
                <h4><b>Rework & Scrap Data</b></h4>
              </div>
                <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" id="datatable-ajax2" width="100%">
                    <thead>
                      <tr >
                        <th width="20%">Wo No</th>
                        <th width="20%">Colors</th>
                        <th width="15%">Ex Fty Date</th>
                        <th width="10%">Qty</th>
                        <th width="10%">Status</th>
                        <th width="15%">Create Lot</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
          </div>
          
      </div>
   
      <input id="conf" name="conf" value="" type="hidden">
      <input class="form-control" name="kode" id="kode" value="<?=$kode?>" type="hidden" />
      <input class="form-control" name="getp" id="getp" value="option=Transaction&task=rescrap_scan&act=ugr_transaction" type="hidden" />
      <input type="hidden" name="getpage" id="getpage" value="<?=$page?>">
   
  </div>
</section>
