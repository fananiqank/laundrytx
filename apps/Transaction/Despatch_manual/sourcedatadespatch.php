
<?php 
//if($_GET['reload'] == 1){
  session_start();
  include "../../../funlibs.php";
  $con = new Database();
//}

$no=1;

if ($_GET['lot'] != ''){

  $cekid = $con->selectcount("laundry_wo_master_dtl_qc","wo_master_dtl_qc_id","wo_master_dtl_qc_status = 1 and lot_no = '$_GET[lot]'");

  $cekdesp = $con->selectcount("laundry_wo_master_dtl_despatch","wo_master_dtl_desp_id","wo_master_dtl_desp_status = 1 and lot_no = '$_GET[lot]'");

  if ($cekid == 0){
    echo "<script>
          swal({
            icon: 'info',
            title: 'Data Not Found',
            text: 'Check Lot Number and Sequence',
            timer: '3000',
        });
      </script>";
  } 
  else {
  	if ($cekdesp > 0){
  		echo "<script>
          swal({
            icon: 'info',
            title: 'Already Despatch Process',
            text: 'Data Lot Already Despatch Process',
            timer: '3000', 
        });
      	</script>";
  	} else {
	    foreach ($con->select("laundry_wo_master_dtl_despatch","SUM(wo_master_dtl_desp_qty) as qtydesp","wo_master_dtl_desp_status != 0 and lot_no = '$_GET[lot]'") as $despatch){}

	    // mendapatkan wip QC
	    $selwip = $con->select("laundry_wo_master_dtl_qc","wo_no,garment_colors,SUM(wo_master_dtl_qc_qty) as qty,wo_master_dtl_proc_id,ex_fty_date,to_char(ex_fty_date,'DD-MM-YYYY') as ex_fty_date_show","wo_master_dtl_qc_status = 1 and wo_master_dtl_qc_type =  1 and lot_no = '$_GET[lot]' GROUP BY wo_no,garment_colors,ex_fty_date,wo_master_dtl_proc_id");
	    foreach ($selwip as $wp) {}
	      //echo "select wo_no,garment_colors,SUM(wo_master_dtl_qc_qty) as qty from laundry_wo_master_dtl_qc where wo_master_dtl_qc_status = 1 and wo_master_dtl_qc_type =  1 and lot_no = '$_GET[lot]' GROUP BY lot_no";
	      $qtyall = $wp['qty']-$despatch['qtydesp'];
	    // if( $qtyall == '0'){
	    //    echo "<script>
	    //       swal({
	    //         icon: 'info',
	    //         title: 'Lot Number Exist',
	    //         text: 'Lot Already Despatch Process',
	    //         timer: '3000',
	    //     });
	    //     </script>";
	    // } else {

    
?>  
        <tr>
            <th width="15%">WO No</th>
            <th width="15%"><?php echo $wp['wo_no']?>
              <input name='wono' id='wono' value='<?=$wp[wo_no]?>' type='hidden' class='form-control' readonly>
            </th>
            <th width="15%">Inseams</th>
            <th width="15%"><input name='inseams' id='inseams' value='' type='text' class='form-control' onkeypress="onEnterinseams(event,this.value)"></th>       
        </tr>
        <tr>
            <th width="15%">Colors</th>
            <th width="15%"><?php echo $wp['garment_colors']?>
               <input name='colors' id='colors' value='<?=$wp[garment_colors]?>' type='hidden' class='form-control' readonly>
            </th>
            <th width="15%">Sizes</th>
            <th width="15%"><input name='sizes' id='sizes' value='' type='text' class='form-control' onkeypress="onEntersizes(event,this.value)"></th>
        </tr>
        <tr>
           <th width="15%">Ex Fty Date</th>
           <th width="15%"><?php echo $wp['ex_fty_date_show']?>
               <input name='ex_fty_date' id='ex_fty_date' value='<?=$wp[ex_fty_date]?>' type='hidden' class='form-control' readonly>
           </th>
           <th width="15%">Qty Detail</th>
           <th width="15%"><input name='qty_det' id='qty_det' value='' type='text' class='form-control' onkeyup='hitungin(this.value,<?=$qtyall?>)' onkeydown='return hanyaAngka(this, event);'></th>
        </tr>
        <tr>
            <th width="15%">Qty All</th>
            <th width="15%"><?php echo $qtyall?>
               <input name='qty_all' id='qty_all' value='<?=$qtyall?>' type='hidden' class='form-control' readonly>
            </th>
        <?php if( $qtyall > 0){ ?>
            <th width="15%" colspan="2" rowspan="2" align="center"> <a href='javascript:void(0)' class='btn btn-success' onClick='conf()'>Submit</a>
            </th>
        <?php } ?>
            
        </tr>
        <tr>
            <th width="15%">Lot No</th>
            <th width="15%"><?php echo $_GET['lot']?>
              <input name='lotno' id='lotno' value='<?=$_GET[lot]?>' type='hidden' class='form-control' readonly>
              <input name='procid' id='procid' value='<?=$wp[wo_master_dtl_proc_id]?>' type='hidden' class='form-control'>
            </th>
        </tr>
<?php 
    }
  }
}
?>
 <input name='typelot' id='typelot' value='<?=$_GET[typelot]?>' type='hidden'>