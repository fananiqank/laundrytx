
<?php
  //perpindahan menu
  $exppage = explode('_',$_GET['p']);
  $page = $exppage[0];  
  $last = $exppage[1];  
  $selcmt = $con->select("laundry_wo_master_dtl_proc","*","wo_master_dtl_proc_id = '$_GET[idm]'");
  foreach ($selcmt as $cmt) {}

?>
 <section class="panel"> 
  <div class="panel-body">
    
      <form class="form-user" id="formku" method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
              <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" id="datatable-ajax" width="100%">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th width="25%">WO No</th>
                    <th width="25%">Color QR</th>
                    <th width="25%">Color Wash</th>
                    <th width="15%">Ex Fty Date</th>
                    <th width="5%">Seq</th>
                    <th width="15%">Cutting Qty</th>
                    <th width="10%">Detail</th>  
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
        <input type="hidden" id="viewapp" name="viewapp" value="<?=$last?>">
        <input type="hidden" id="getp" name="getp" value="<?=$_GET[option]?>">
        <input type="hidden" name="modsequenceeditid" id="modsequenceeditid" value="">
    </form>
  </div>