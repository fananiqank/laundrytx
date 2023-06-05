
<?php 
session_start();
include "../../../funlibs.php";
$con = new Database();

$no=1;
$selwip = $con->select("wip_dtl","*","wip_dtl_status = 9 and wip_dtl_createdby = '$_SESSION[ID_LOGIN]'","wip_dtl_id ASC");
//echo "select * from wip_dtl where wip_dtl_status = 9 and wip_dtl_createdby = '$_SESSION[ID_LOGIN]'","wip_dtl_id ASC";
foreach ($selwip as $wp) {
    $exdate = date('d-m-Y',strtotime($wp['ex_fty_date']));
?>  
    <tr>
        <th width="5%"><?php echo $no?></th>
        <th width="15%"><?php echo $wp['wo_no']?>
          <input name='wo_<?=$wp[wip_dtl_id]?>' id='wo_<?=$wp[wip_dtl_id]?>' value='<?=$wp[wo_no]?>' type='hidden' class='form-control'>
        </th>
        <th width="15%"><?php echo $wp['garment_colors']?>
           <input name='color_<?=$wp[wip_dtl_id]?>' id='color_<?=$wp[wip_dtl_id]?>' value='<?=$wp[garment_colors]?>' type='hidden' class='form-control'>
        </th>
        <th width="15%"><?php echo $exdate; ?>
           <input name='exdate_<?=$wp[wip_dtl_id]?>' id='exdate_<?=$wp[wip_dtl_id]?>' value='<?=$exdate?>' type='hidden' class='form-control'>
        </th>
        <th width="5%"><?php echo $wp['garment_inseams']?></th>
        <th width="5%"><?php echo $wp['garment_sizes']?></th>
        <th width="5%"><?php echo $wp['quantity']?></th>
       
    </tr>

<?php 
    $no++; 
    }
?>
<input name='wo_no' id='wo_no' value='<?php echo $wp[wo_no]; ?>' type='hidden' class='form-control'>
<input name='garment_colors' id='garment_colors' value='<?php echo $wp[garment_colors]; ?>' type='hidden' class='form-control'>
<input name='ex_fty_date' id='ex_fty_date' value='<?php echo $exdate; ?>' type='hidden' class='form-control'>