<!-- Jika Process Edit Sequence !-->
<?php if ($_GET['idm'] == ''){ ?>

    <h4>WO Number</h4>
    <div class="col-sm-6 col-md-6 col-lg-6" style="background-color: #FFFFFF;">
      <div class="row">
        <h5><b>Choose WO Number</b> &nbsp;<span style="float: right"><input type="checkbox" value="1" onclick="cekwip(this)" id="checkwip"> All &emsp;</span></h5>
      </div>
      <div class="row pre-scrollable" style="height: 200px;" id="tampilwip">
        <?php //include "tampilwip.php" ?>
      </div>
    </div>

    <div class="col-sm-1 col-md-1 col-lg-1" style="height: 200px;" id="submit-control">
        <br>
        <br>
        <br>
        <a href="javascript:void(0)" onclick="savewo(1,'<?php echo $_GET[statusseq]; ?>')" class="btn btn-success" style="font-size: 16px;vertical-align: middle;"><b>>></b></a>
    </div>
    <div class="col-sm-5 col-md-5 col-lg-5" style="background-color: #FFFFFF;">
      <div class="row">
        <h5><b>On Plan</b> &nbsp;<span style="float: right"><input type="checkbox" onclick="cekwo(this)" id="checkwo"> All &ensp;<a href="javascript:void(0)" class='label label-default' style="border: none; font-size: 14px;" onclick="hapusker(2)"><i class="fa fa-trash"></i></a>&emsp;</span> </h5>
      </div>
      <div class="row pre-scrollable" style="height: 200px;" id="tampilwo">
      <?php include "tampilwo.php";?>
      </div>
    </div>

<!-- Jika Pembuatan Awal Sequence !-->                   
<?php } else { ?>
                         
   	<h4>CMT Number </h4>
    <div class="col-sm-12 col-md-12 col-lg-12" style="">
        <b><?php echo $cmt['wo_no'].'_'.$cmt['garment_colors'].' : '.$statusseqedit.' '.$cmt['rework_seq']; ?></b>
    </div>

<?php } ?>