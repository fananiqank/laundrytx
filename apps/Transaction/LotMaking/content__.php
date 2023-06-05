<?php
    //perpindahan menu
  $exppage = explode('_',$_GET['p']);
  $page = $exppage[0];  
  $last = $exppage[1];    
 
?>
<section class="panel"> 
  <div class="panel-body">
      <div id="tampilcontent">
          <div class="form-group" align="center" style="margin-top: 15%;margin-bottom: 15%;">
              <div class="col-sm-6 col-md-6 col-lg-6">
                <a href="javascript:void(0)" onclick="reclot('lot')">
                    <button style="padding: 8%;" class="btn btn-primary"><b>Receive Lot</b></button>
                </a>
              </div>
              <div class="col-sm-6 col-md-6 col-lg-6">
                <a href="javascript:void(0)" onclick="enterlot('lot')">
                    <button style="padding: 8%;" class="btn btn-warning" ><b>Create Lot Number</b></button>
                </a>
              </div>
          </div>
      </div>
  </div>
</section>

    
