<?php 
  $no = 1;
  if($_GET['cm'] != ''){
     require( '../../../funlibs.php' );
    $con=new Database;
    session_start();

    $cmt = $con->searchseqcmt($_GET['cm']);
    $colors = $con->searchseq($_GET['co']);
    $exftydate = $_GET['xty'];
  } else {
    $cmt = $pro['wo_no'];
    $colors = $pro['garment_colors'];
    $exftydate = $pro['ex_fty_date'];
  }

  if ($_GET['c'] != ''){
 
    $process = "and lot_no ilike '%$_GET[c]%'";
    
    $sellot = $con->select("(select lot_no as lot_no, lot_qty as qty, lot_status as status, lot_id as id,a.wo_master_dtl_proc_id,lot_createdate as createdate,lot_type as types,a.wo_no,a.garment_colors,b.ex_fty_date from laundry_lot_number a JOIN laundry_wo_master_dtl_proc b ON a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id  UNION select rec_no as lot_no, rec_qty as qty, rec_break_status as status, rec_id as id,c.wo_master_dtl_proc_id,rec_createdate as createdate,'A' as types,c.wo_no,c.garment_colors,d.ex_fty_date from laundry_receive c JOIN laundry_wo_master_dtl_proc d ON c.wo_master_dtl_proc_id=d.wo_master_dtl_proc_id) as a","lot_no,qty,status,id,wo_master_dtl_proc_id,types,wo_no,garment_colors,ex_fty_date","wo_no = '".$cmt."' and garment_colors = '".$colors."' and DATE(ex_fty_date) = '".$exftydate."' $process","lot_no DESC");
    // echo "select lot_no,qty,status,id,wo_master_dtl_proc_id,types,wo_no,garment_colors,ex_fty_date from (select lot_no as lot_no, lot_qty as qty, lot_status as status, lot_id as id,a.wo_master_dtl_proc_id,lot_createdate as createdate,lot_type as types,a.wo_no,a.garment_colors,b.ex_fty_date from laundry_lot_number a JOIN laundry_wo_master_dtl_proc b ON a.wo_master_dtl_proc_id=b.wo_master_dtl_proc_id  UNION select rec_no as lot_no, rec_qty as qty, rec_break_status as status, rec_id as id,c.wo_master_dtl_proc_id,rec_createdate as createdate,'A' as types,c.wo_no,c.garment_colors,d.ex_fty_date from laundry_receive c JOIN laundry_wo_master_dtl_proc d ON c.wo_master_dtl_proc_id=d.wo_master_dtl_proc_id) as a where wo_no = '".$cmt."' and garment_colors = '".$colors."' and DATE(ex_fty_date) = '".$exftydate."' $process";
  } else {
    // $sellot = $con->select("(select lot_no as lot_no, lot_qty as qty, lot_status as status, lot_id as id,wo_master_dtl_proc_id,lot_createdate as createdate,lot_type as types from laundry_lot_number where lot_type = 'M' UNION select rec_no as lot_no, rec_qty as qty, rec_break_status as status, rec_id as id,wo_master_dtl_proc_id,rec_createdate as createdate,'A' as types from laundry_receive) as a","lot_no,qty,status,id,wo_master_dtl_proc_id,createdate,types","wo_master_dtl_proc_id = '".$_GET['id']."'","createdate");
    $table = "(
            SELECT
              lot_no AS lot_no,
              lot_qty AS qty,
              lot_status AS status,
              lot_id AS ID,
              lot_createdate AS createdate,
              lot_type AS types,
              wo_no,
              garment_colors 
            FROM
              laundry_lot_number 
            WHERE
              lot_type = 'M' UNION
            SELECT
              rec_no AS lot_no,
              rec_qty AS qty,
              rec_break_status AS status,
              rec_id AS ID,
              rec_createdate AS createdate,
              'A' AS types,
              wo_no,
              garment_colors 
            FROM
              laundry_receive 
            )
            AS A JOIN ( SELECT wo_no, garment_colors, DATE ( ex_fty_date ) AS ex_fty_date FROM laundry_wo_master_dtl_proc GROUP BY wo_no, garment_colors, ex_fty_date ) as b ON A.wo_no = b.wo_no AND A.garment_colors = b.garment_colors ";
    $field = "lot_no,qty,status,id,createdate,types,a.wo_no,a.garment_colors, ex_fty_date";
    $where = "a.wo_no = '".$cmt."' and a.garment_colors = '".$colors."' and DATE(b.ex_fty_date) = '".$exftydate."'";
    //echo "select $field from $table where $where";
    $sellot = $con->select($table,$field,$where,"lot_no DESC");
  
  }
    foreach ($sellot as $lot) {
      if ($lot['status'] == 1) {
        $status = "<span style='color:#006400;'><b>Active</b></span>";
        $coldetail = "class='label label-success'";
      } else {
        $status = "<span style='color:#800000;'><b>Break</b></span>";
        $coldetail = "class='label label-danger'";
      }
  
?>    
          <tr class="panel-group" id="accordion">
              <td><?=$no?></td>
              <td><a href="javascript:void(0)" data-toggle="collapse" data-parent="#accordion" data-target="<?='#'.$lot[lot_no]?>"><?=$lot['lot_no']?></a></td>
              <td><?=$lot['qty']?></td>
              <td><?=$lot['qty']?></td>
              <td><?=$status;?></td>
              <td><a href="javascript:void(0)" <?=$coldetail?> onclick="cekdetail('<?php echo $lot[id]; ?>','<?php echo $lot[lot_no]; ?>','<?=$lot[types]?>')"><i class="fa fa-list"></i></a></td>
          </tr>
          <tr id="<?=$lot[lot_no]?>" class="collapse pre-scrollable">
            <td colspan="7" >
            <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer">
      <?php  
            $nod = 1;
            $sellotdetail = $con->select("laundry_lot_number b JOIN (select DISTINCT log_lot_tr,log_lot_receive from laundry_log ) a ON b.lot_no = a.log_lot_tr 
              LEFT JOIN (select DISTINCT lot_id_parent as lot_id_split from laundry_lot_number_dtl where create_type = 2) c 
              ON b.lot_id=c.lot_id_split
              LEFT JOIN (select DISTINCT lot_id as lot_id_combine from laundry_lot_number_dtl where create_type = 3) d 
              ON b.lot_id=d.lot_id_combine",
              "b.lot_id,b.lot_no,b.lot_qty,lot_qty_good_upd,lot_status,wo_master_dtl_proc_id,b.lot_type as types,c.lot_id_split,d.lot_id_combine","a.log_lot_receive = '$lot[lot_no]' and master_type_lot_id not between 7 and 9","lot_createdate");
            // echo "select b.lot_id,b.lot_no,b.lot_qty,lot_qty_good_upd,lot_status,wo_master_dtl_proc_id,b.lot_type as types,c.lot_id_split,d.lot_id_combine from laundry_lot_number b JOIN (select DISTINCT log_lot_tr,log_lot_receive from laundry_log ) a ON b.lot_no = a.log_lot_tr 
            //   LEFT JOIN (select DISTINCT lot_id_parent as lot_id_split from laundry_lot_number_dtl where create_type = 2) c 
            //   ON b.lot_id=c.lot_id_split
            //   LEFT JOIN (select DISTINCT lot_id as lot_id_combine from laundry_lot_number_dtl where create_type = 3) d 
            //   ON b.lot_id=d.lot_id_combine where a.log_lot_receive = '$lot[lot_no]'";
            foreach ($sellotdetail as $lotd) {
                if ($lotd['lot_status'] == 1) {
                  $status = "<span style='color:#006400;'><b>Active</b></span>";
                  $coldetail = "class='label label-success'";
                } else if ($lotd['lot_status'] == 2) {
                  $status = "<span style='color:#0088cc;'><b>Done</b></span>";
                  $coldetail = "class='label label-primary'";
                } else {
                  $status = "<span style='color:#800000;'><b>Break</b></span>";
                  $coldetail = "class='label label-danger'";
                }
      ?>

              <tr>
                  <td colspan="2"><a href="javascript:void(0)" data-toggle="collapse" data-target="<?='#'.$lotd[lot_no]?>">&emsp;<?=$nod?>. &ensp;<?=$lotd['lot_no']?></a></td>
                  <td><?=$lotd['lot_qty']?></td>
                  <td><?=$lotd['lot_qty_good_upd']?></td>
                  <td><?=$status;?></td>
                  
                  <td>
                    <a href="javascript:void(0)" <?=$coldetail?> onclick="cekdetail('<?php echo $lotd[lot_id]; ?>','<?php echo $lotd[lot_no]; ?>','<?=$lotd[types]?>')"><i class="fa fa-list"></i></a>
                    
                  </td>
              </tr>
              <tr id="<?=$lotd[lot_no]?>" class="collapse pre-scrollable">
                  <td colspan="7" >
                  <table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer">
            <?php  
                  $nod2 = 1;
                  $sellotdetail2 = $con->select("(SELECT   b.lot_id, b.lot_no, b.lot_qty,b.lot_createdate,lot_qty_good_upd,lot_status,wo_master_dtl_proc_id,b.lot_type AS types,c.lot_id_parent
                    FROM  laundry_lot_number b JOIN ( SELECT DISTINCT lot_id,lot_id_parent FROM laundry_lot_number_dtl ) C ON b.lot_id = C.lot_id
                    where lot_id_parent = '$lotd[lot_id]'
                    ) as aso",
                    "*","","lot_createdate");
                  // echo "SELECT  *
                  //       FROM  (SELECT   b.lot_id, b.lot_no, b.lot_qty,b.lot_createdate,lot_qty_good_upd,lot_status,wo_master_dtl_proc_id,b.lot_type AS types,c.lot_id_parent
                  //   FROM  laundry_lot_number b JOIN ( SELECT DISTINCT lot_id,lot_id_parent FROM laundry_lot_number_dtl WHERE create_type = 2 ) C ON b.lot_id = C.lot_id
                  //   where lot_id_parent = '$lotd[lot_id]'
                  //   UNION 
                  //   SELECT f.lot_id, f.lot_no,f.lot_qty,f.lot_createdate,f.lot_qty_good_upd,f.lot_status,f.wo_master_dtl_proc_id,f.lot_type AS types,g.lot_id_parent
                  //   FROM laundry_lot_number f JOIN
                  //         (SELECT b.lot_id,b.lot_no,b.lot_qty,b.lot_createdate,lot_qty_good_upd,lot_status,wo_master_dtl_proc_id,
                  //           b.lot_type AS types,C.lot_id_parent 
                  //           FROM
                  //               laundry_lot_number b
                  //               JOIN ( SELECT DISTINCT lot_id, lot_id_parent FROM laundry_lot_number_dtl WHERE create_type = 3 ) C ON b.lot_id = C.lot_id 
                  //           WHERE
                  //               b.lot_id = '$lotd[lot_id]' 
                  //         ) as g
                  //   ON f.lot_id =g.lot_id_parent) as aso order by lot_createdate";
                  foreach ($sellotdetail2 as $lotd2) {
                      if ($lotd2['lot_status'] == 1) {
                        $status = "<span style='color:#006400;'><b>Active</b></span>";
                        $coldetail = "class='label label-success'";
                      } else if ($lotd2['lot_status'] == 2) {
                        $status = "<span style='color:#0088cc;'><b>Done</b></span>";
                        $coldetail = "class='label label-primary'";
                      } else {
                        $status = "<span style='color:#800000;'><b>Break</b></span>";
                        $coldetail = "class='label label-danger'";
                      }
            ?>

                    <tr>
                        <td colspan="2"><a href="javascript:void(0)" data-toggle="collapse" data-target="<?=$lotd2[lot_no]?>">&emsp;<?=$nod2?>. &ensp;<?=$lotd2['lot_no']?></a></td>
                        <td><?=$lotd2['lot_qty']?></td>
                        <td><?=$lotd2['lot_qty_good_upd']?></td>
                        <td><?=$status;?></td>
                        
                        <td>
                          <a href="javascript:void(0)" <?=$coldetail?> onclick="cekdetail('<?php echo $lotd2[lot_id]; ?>','<?php echo $lotd2[lot_no]; ?>','<?=$lotd2[types]?>')"><i class="fa fa-list"></i></a>
                          
                        </td>
                    </tr>
      <?php 
                   $nod2++; }
      ?>
                </table>
                </td>
              </tr>
<?php 
             $nod++; }
?>
          </table>

          </td>
        </tr>
<?php
     $no++;
  }
?>