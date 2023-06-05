<?php 

$selectuser = $con->select("m_users a join laundry_user_section b on a.user_id=b.user_id","a.user_id,username","a.user_id = '".$_SESSION['ID_LOGIN']."'");
foreach ($selectuser as $user) {}    

?>


<section class="panel">
    <div class="panel-body">
     <!--  <a class='btn btn-warning' style='margin-bottom: 0%;' data-toggle='collapse' data-target='#demo'>Filter</a> -->
      <a href="javascript:void(0)" class='btn btn-success' style='margin-bottom: 0%;' onclick="viewdata('v')">View Data</a>
      <span class="badge" style='margin-bottom: 0%;float: right;' id="cart">
            <?php include "cart.php";?>            
      </span>
      <a href="javacript:void(0)" class="btn btn-primary" style="margin-bottom: 0%;float: right;" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldespatch()">Confirm</a>

      <div id="demo" class="collapse in" >
          <hr>
          <div class="form-group">
            <div class="col-sm-2 col-md-2 col-lg-2" align="center">
              <b>User</b>
            </div>
            <div class="col-sm-5 col-md-5 col-lg-5">
              <input class="form-control" id="username" name="username" onkeypress="onEnterUser(event,this.value)">
              <input type="hidden" name="userid" id="userid" class="form-control" value="<?php echo $_SESSION[ID_LOGIN]; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-2 col-md-2 col-lg-2" align="center">
              <b>Lot Number</b>
            </div>
            <div class="col-sm-5 col-md-5 col-lg-5">
                <input type="text" name="lot_no" id="lot_no" class="form-control" value="" required>
            </div>
            
          </div>
      <hr>
      </div>

    </header>
    
    <form class="form-user" id="formku" method="post" action="content.php?p=<?=$get?>_s" enctype="multipart/form-data">
       
                    <div class="form-group">
                        <div class="col-sm-12 col-md-12 col-lg-12" style="background-color: #FFFFFF; " />

                            <div class="row" align="center">
                            <h4><b>Despatch Manual</b></h4>
                            </div>
                              <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax4" style="font-size: 13px;">
                               
                                <tbody id="tampildespatch">
                                  <?php include "sourcedatadespatch.php"; ?>
                                </tbody>
                              </table>
                        </div>
                       
                    </div>

                      <input id="view" name="view" value="<?php echo $last; ?>" type="hidden">
                      <input id="conf" name="conf" value="" type="hidden">
                      <input class="form-control" name="kode" id="kode" value="<?php echo $kode; ?>" type="hidden" />
                      <input class="form-control" name="getp" id="getp" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>" type="hidden" />
                      <input type="hidden" id="hal" name="hal" value="<?php echo $get; ?>">
                      <input type="hidden" name="getpage" id="getpage" value="option=<?php echo $_GET[option]; ?>&task=<?php echo $_GET[task]; ?>&act=<?php echo $_GET[act]; ?>">
                      <input type="hidden" name="no_sizes" id="no_sizes" value="<?php echo $_GET[si]; ?>">
                      <input type="hidden" name="no_cmt" id="no_cmt" value="<?php echo $_GET[cm]; ?>">
                      <input type="hidden" name="no_colors" id="no_colors" value="<?php echo $_GET[co]; ?>">
                      <input type="hidden" name="no_inseams" id="no_inseams" value="<?php echo $_GET[in]; ?>">
                      <input type="hidden" name="saw" id="saw" value="<?php echo $_GET[saw]; ?>">
    </form>
  </div>
</section>
