<?php
require( '../../../funlibs.php' );
$con=new Database;
session_start();
$date = date("Y-m-d_H-i");
// header("Content-type: application/octet-stream");
// header("Content-Disposition: attachment; filename=Ticket_Report.xls");//ganti nama sesuai keperluan
// header("Pragma: no-cache");
// header("Expires: 0");
header("Content-type: application/octet-stream");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Report_DailyWIP_$date.xls");

$cmt = $con->searchseqcmt($_GET['cm']);
$colors = $con->searchseq($_GET['co']);
$exftydate = $_GET['xty'];

if ($cmt != ''){
  $cm = "and wo_no = '".$cmt."' ";
} else {
  $cm = "";
}

if ($colors != ''){
  $co = "and garment_colors = '".$colors."' ";
} else {
  $co = "";
}
if ($exftydate != ''){
  $xty = "and DATE(ex_fty_date_asli) = '".$exftydate."'";
} else {
  $xty = "";
}

?>
  <table class="table table-bordered table-hover pre-scrollable no-footer" id="datatable-ajax" width="175%">
      <thead align="center">
          <tr>
              <th colspan="18" style="text-align:center" ><h4>REPORT DAILYWIP <?=$date?></h4></th>
          </tr>
          <tr>
              <th width="5%" >No</th>
              <th width="10%" >Weeks</th>
              <th width="10%" >Place</th>
              <th width="10%" >Ex Fty Date</th>
              <th width="10%" >Wo No</th>
              <th width="10%" >Colors</th> 
              <th width="10%" >Buyer</th>
              <th width="10%">Wash Category</th>
              <th width="10%" >Manual / Laser</th>
              <th width="10%" >First Incoming<br> Date</th>
              <th width="10%" >Status</th>
              <th width="5%" >First Bulk<br> Approval</th>
              <th width="15%" >Lead Time<br> Prebulk</th> 
              <th width="10%" >Cut Qty</th>
              <th width="15%" >Receive</th>
              <th width="10%" >Sewing Balance</th>
              <th width="10%" >Take Out Sewing</th>
              <th width="10%" >Last Incoming <br> Date</th>
              <th width="10%" >Total WIP</th>
          </tr>
      </thead>
      <tbody style="overflow-x: auto">
        <?php 
            $no = 1;
            $table = "(SELECT DISTINCT (a.wo_no),EXTRACT(WEEK FROM a.ex_fty_date) as week,      
              TO_CHAR(a.ex_fty_date,'DD-MM-YYYY') as ex_fty_date,a.ex_fty_date as ex_fty_date_asli,
              a.garment_colors,a.buyer_id,c.wash_category_desc,
              CASE 
                WHEN master_type_process_id = 4 
                THEN 'DP'
                ELSE 'NON DP'
              END as master_type_process_id,
              DATE(firstdate) as firstdate,DATE(fbdate) as fbdate,a.cutting_qty,
              CASE 
                WHEN g.lot_type = 'P' THEN 'Pre Bulk'
                WHEN g.lot_type = 'F' THEN 'First Bulk'
                WHEN g.lot_type != '' and g.lot_type = 'F' and g.lot_type = 'P' THEN 'Production'
              END as status_wo,g.lot_type,
              DATE_PART('day', fbdate - firstdate) AS lead_bulk,wo_master_dtl_proc_qty_rec,
              (cutting_qty - wo_master_dtl_proc_qty_rec) as sewing_balance,h.lastdate,
              concat(COALESCE((wo_master_dtl_proc_qty_rec - j.despatch_qty),0),'_',wo_master_dtl_proc_qty_rec) as total_wip,k.factory_name, 
              k.totaloutsew,m.totalout,NULL as aza
              from laundry_wo_master_dtl_proc a 
                JOIN laundry_data_wo b ON a.wo_no=b.wo_no
              JOIN m_wash_category c ON b.wash_category_id=c.wash_category_id
              JOIN (select COALESCE(master_type_process_id,0) as master_type_process_id,role_wo_master_id 
                    from laundry_role_wo d where master_type_process_id = '4') as d
                    ON a.role_wo_master_id=d.role_wo_master_id
              JOIN (SELECT factory_name,wo_no, color, DATE(ex_fty_date) as ex_fty_date,sum(gdp_goods) as totaloutsew
                    FROM qrcode_gdp a
                      join qrcode_ticketing_master b ON a.ticketid=b.ticketid
                      join qrcode_factory_master c ON a.factory_id = c. factory_id
                    WHERE step_id_detail = 3 GROUP BY wo_no, color, ex_fty_date,factory_name) k 
                    ON a.wo_no = k.wo_no and a.garment_colors = k.color and DATE(a.ex_fty_date)=k.ex_fty_date
              JOIN (SELECT MIN ( rec_createdate ) AS firstdate,rec_status,wo_master_dtl_proc_id FROM laundry_receive 
                    GROUP BY wo_master_dtl_proc_id, rec_status) as e
                    on a.wo_master_dtl_proc_id = e.wo_master_dtl_proc_id
              LEFT JOIN (SELECT MIN( approve_bulk_createdate ) AS fbdate, wo_no,garment_colors
                    FROM laundry_approve_bulk WHERE lot_type = 'F'  
                    GROUP BY wo_no,garment_colors) as f
                    on a.wo_no = f.wo_no and a.garment_colors = f.garment_colors
              LEFT JOIN (SELECT wo_master_dtl_proc_id,lot_type
                    FROM laundry_lot_number a 
                    JOIN (select max(lot_id) as lot_id from laundry_lot_number 
                          where lot_type = 'P' OR lot_type = 'F' and lot_status != 0) as b 
                          ON a.lot_id=b.lot_id) as g
                    on a.wo_master_dtl_proc_id = g.wo_master_dtl_proc_id
              LEFT JOIN (select (rec_createdate) as lastdate,wo_master_dtl_proc_id
                    from laundry_receive where rec_status = 2) as h
                    on a.wo_master_dtl_proc_id = h.wo_master_dtl_proc_id
              LEFT JOIN (SELECT SUM ( wo_master_dtl_desp_qty ) AS despatch_qty, u.wo_master_dtl_proc_id
                    FROM laundry_wo_master_dtl_despatch t JOIN laundry_wo_master_dtl_proc u 
                    on t.wo_master_dtl_proc_id=u.wo_master_dtl_proc_id
                    GROUP BY u.wo_master_dtl_proc_id) as j 
                    on a.wo_master_dtl_proc_id = j.wo_master_dtl_proc_id
              LEFT JOIN (select sum(gdp_goods) totalout,wo_no,color,DATE(ex_fty_date) as ex_fty_date
                     from 
                     (SELECT A.qrcode_key,workcenter_id,gdp_datetime,b.wo_no,b.color,b.ex_fty_date,to_timestamp( FLOOR ( ( EXTRACT ( 'epoch' FROM gdp_datetime ) / 300 ) ) * 300 ) AT TIME ZONE'UTC' + INTERVAL '5 minute' AS interval_alias,
                  COALESCE ( smv_minute :: NUMERIC, '0' :: NUMERIC ) AS smv,gdp_goods 
                     FROM qrcode_gdp A 
                        JOIN qrcode_ticketing_master b ON A.qrcode_key = b.qrcode_key
                        LEFT JOIN qrcode_master_smv C ON C.smv_wono = b.wo_no 
                     WHERE workcenter_id = 'WAS1'
                    ) A 
                    LEFT JOIN qrcode_schedulesshift b ON A.interval_alias BETWEEN (to_timestamp( FLOOR ( ( EXTRACT ( 'epoch' FROM b.schedulemasuk1 ) / 300 ) ) * 300 ) AT TIME ZONE'UTC' + INTERVAL '1 second' ) AND b.schedulemasuk2 AND SECTION = 'WASHING' 
                    WHERE b.scheduledate = date(now()) 
                    GROUP BY wo_no,color,ex_fty_date
              ) as m ON a.wo_no = m.wo_no and a.garment_colors = m.color and DATE(a.ex_fty_date)=m.ex_fty_date
            ) as asi ";
            $where = "wo_no ilike '%' $cm $co $xty";
            $selectall = $con->select($table,"*",$where);
           // echo "select * from $table where $where";
            foreach($selectall as $all) {
              $expwip = explode('_', $all['total_wip']);
              if($expwip[0] == 0){
                $wip = $expwip[1];
              } else {
                $wip = $expwip[0];
              }

        ?>
        <tr>
              <td><?=$no?></td>
              <td><?=$all['week']?></td>
              <td><?=$all['factory_name']?></td>
              <td><?=$all['ex_fty_date']?></td>
              <td><?=$all['wo_no']?></td>
              <td><?=$all['garment_colors']?></td>
              <td><?=$all['buyer_id']?></td>
              <td><?=$all['wash_category_desc']?></td>
              <td><?=$all['master_type_process_id']?></td>
              <td><?=$all['firstdate']?></td>
              <td><?=$all['status_wo']?></td>
              <td><?=$all['fbdate']?></td>
              <td><?=$all['lead_bulk']?></td>
              <td><?=$all['cutting_qty']?></td>
              <td><?=$all['totalout']?></td>
              <td><?=$all['wo_master_dtl_proc_qty_rec']?></td>
              <td><?=$all['sewing_balance']?></td>
              <td><?=$all['totaloutsew']?></td>
              <td><?=$all['lastdate']?></td>
              <td><?=$wip?></td>
        </tr>
        <?php $no++; } ?>
      </tbody>
  </table>

<script>
     window.close();
</script>
