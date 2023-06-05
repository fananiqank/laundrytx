<?php 
  $no = 1;
  if ($_GET['c'] != ''){
    require( '../../../funlibs.php' );
    $con=new Database;
    session_start();
    if ($_GET['c'] == 'Z'){
        $process = "";
    } else {
        $process = "and lot_no ilike '%$_GET[c]%'";
    }
    // echo "select lot_no,qty,status,id,wo_master_dtl_proc_id,types from (select lot_no as lot_no, lot_qty as qty, lot_status as status, lot_id as id,wo_master_dtl_proc_id,lot_createdate as createdate,lot_type as types from laundry_lot_number UNION select rec_no as lotno, rec_qty as qty, rec_break_status as status, rec_id as id,wo_master_dtl_proc_id,rec_createdate as createdate,'A' as types from laundry_receive) as a where wo_master_dtl_proc_id = '".$_GET['id']."' $process";
    $sellot = $con->select("(select lot_no as lot_no, lot_qty as qty, lot_status as status, lot_id as id,wo_master_dtl_proc_id,lot_createdate as createdate,lot_type as types from laundry_lot_number UNION select rec_no as lotno, rec_qty as qty, rec_break_status as status, rec_id as id,wo_master_dtl_proc_id,rec_createdate as createdate,'A' as types from laundry_receive) as a","lot_no,qty,status,id,wo_master_dtl_proc_id,types","wo_master_dtl_proc_id = '".$_GET['id']."' $process","status,lot_no");
  } else {
    $sellot = $con->select("(select lot_no as lot_no, lot_qty as qty, lot_status as status, lot_id as id,wo_master_dtl_proc_id,lot_createdate as createdate,lot_type as types from laundry_lot_number UNION select rec_no as lotno, rec_qty as qty, rec_break_status as status, rec_id as id,wo_master_dtl_proc_id,rec_createdate as createdate,'A' as types from laundry_receive) as a","lot_no,qty,status,id,wo_master_dtl_proc_id,createdate,types","wo_master_dtl_proc_id = '".$_GET['id']."'","status,lot_no");
       // echo "select lot_no,qty,status,id,wo_master_dtl_proc_id,createdate,types from (select lot_no as lot_no, lot_qty as qty, lot_status as status, lot_id as id,wo_master_dtl_proc_id,lot_createdate as createdate,lot_type as types from laundry_lot_number UNION select rec_no as lotno, rec_qty as qty, rec_break_status as status, rec_id as id,wo_master_dtl_proc_id,rec_createdate as createdate,substring(rec_no from 9 for 1) as types from laundry_receive) as a where wo_master_dtl_proc_id = '$_GET[id]' order by createdate";
  }
    foreach ($sellot as $lot) {
      if ($lot['status'] == 1) {
        if($lot['types'] == 'R'){
          $status = "<span style='color:#FFA500;'><b>Reject</b></span>";
          $coldetail = "class='label label-warning'";
        } else {
          $status = "<span style='color:#006400;'><b>Active</b></span>";
          $coldetail = "class='label label-success'";
        }
      } else if($lot['status'] == 2) {
        $status = "<span style='color:#6495ED;'><b>Done</b></span>";
        $coldetail = "class='label label-primary'";
      } else if($lot['status'] == 3) {
        $status = "<span style='color:##0000FF;'><b>Lot Making</b></span>";
        $coldetail = "class='label label-info'";
      } else {
        $status = "<span style='color:#800000;'><b>Break</b></span>";
      	$coldetail = "class='label label-danger'";
      }

      $explot = explode('-',$lot['lot_no']);
      $type = substr($explot[0], -4,1);
      if ($type == 'A'){
         $qty_now = $lot['qty'];
      } else {
        foreach ($con->select("laundry_lot_number","lot_qty_good_upd as qty_now","lot_no = '".$lot['lot_no']."'") as $qtynow){}
          $qty_now = $qtynow['qty_now'];
      }
?>    
          <tr>
              <td><?=$no?></td>
              <td><?=$lot['lot_no']?></td>
              <td><?=$lot['qty']?></td>
              <td><?=$qty_now;?></td>
              <td><?=$status;?></td>
              <td><a href="javascript:void(0)" <?=$coldetail?> onclick="cekdetail('<?php echo $lot[id]; ?>','<?php echo $lot[lot_no]; ?>','<?php echo $type; ?>','<?php echo $lot[wo_master_dtl_proc_id]; ?>')"><i class="fa fa-list"></i></a></td>
          </tr>
<?php 
     $no++;
  }
?>