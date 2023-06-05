<?php 

$exppage = explode('_',$_GET['task']);
$page = $exppage[0];  
$last = $exppage[1];  

?>

<section class="panel">
    <div class="panel-body">

      <?php if($last == 'v') { ?>
          <a href="content.php?option=Transaction&task=receive&act=ugr_transaction" class='btn btn-success' style='margin-bottom: 0%;'>Input Data</a>
      <?php } else { ?> 
          <a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('v')">View Receive Data</a>
          <span class="badge" style='margin-bottom: 0%;float: right;' id="cart">
            <?php include "cart.php";?>            
          </span>
          <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modelcart()"><i class="fa fa-shopping-cart"></i></a>
      <?php } ?>

 <form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
 <?php if ($last != 'v') { ?>
      <div class="form-group">
        <hr>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>WO No</b></label>
            <div class="col-md-4">
                <input type="text" name="nocmt" id="nocmt" class="form-control" value="<?php echo $ncmt; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Colors</b></label>
            <div class="col-md-4">
                <input type="text" name="nocolor" id="nocolor" class="form-control" value="<?php echo $ncol; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Ex Fty Date</b></label>
            <div class="col-md-4">
                <input id="exftydate" name="exftydate" data-plugin-masked-input data-input-mask="99-99-9999" placeholder="__-__-____" class="form-control" required>
                <input type="hidden" id="exftydateasli" name="exftydateasli" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Inseams</b></label>
            <div class="col-md-4">
                <input type="text" name="noinseams" id="noinseams" class="form-control" value="<?php echo $nins; ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Sizes</b></label>
            <div class="col-md-4">
                <input type="text" name="nosizes" id="nosizes" class="form-control" value="<?php echo $nsiz; ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b>Qty</b></label>
            <div class="col-md-4">
                <input type="text" name="qty_in" id="qty_in" class="form-control" value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label" for="profileLastName"><b></b></label>
            <div class="col-md-4">
                <a href="javascript:void(0)" class="btn btn-primary" onclick="conf(nocmt.value,nocolor.value,exftydate.value)">Submit</a>
            </div>
          </div>
           <hr>
        </div>
    </header>
<?php } ?>
        <?php if ($last == 'v') { ?>
                    <div class="form-group">
                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
                            <div class="row" align="center">
                            <h4><b>Receive Data</b></h4>
                            </div>
                              <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax2" style="font-size: 13px;">
                                <thead>
                                  <tr>
                                    <th width="5%">No</th>
                                    <th width="12%">No Receive</th>
                                    <th width="13%">WO No</th>
                                    <th width="13%">Colors</th>
                                    <th width="5%">Qty Receive</th>
                                    <th width="5%">User </th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                        </div>
                    </div>
        <?php } else { ?>
                    <div class="form-group">
                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />
                            <div class="row" align="center">
                            <h4><b>Receive Data</b></h4>
                            </div>
                              <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax4" style="font-size: 13px;">
                                <thead>
                                  <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">WO No</th>
                                    <th width="15%">Colors</th>
                                    <th width="10%">Ex Fty Date</th>
                                    <th width="5%">Inseams</th>
                                    <th width="5%">Sizes</th>
                                    <th width="5%">Qty Receive</th>
                                  </tr>
                                </thead>
                                <tbody id="tampilsewing">
                                  <?php include "sourcedatasewing.php"; ?>
                                </tbody>
                              </table>
                        </div>
                        <div id="cekwip">
                            <?php include "cekwip.php"; ?>
                        </div>
                    </div>
        <?php } ?>          
                       
                      <input id="view" name="view" value="<?php echo $last; ?>" type="hidden">
                      <input id="conf" name="conf" value="" type="hidden">
                      <input class="form-control" name="kode" id="kode" value="<?php echo $kode; ?>" type="hidden" />
                      <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
                      <input type="hidden" id="hal" name="hal" value="<?php echo $get; ?>">
                      <input type="hidden" name="getpage" id="getpage" value="<?php echo $page; ?>">
    </form>
  </div>
</section>
