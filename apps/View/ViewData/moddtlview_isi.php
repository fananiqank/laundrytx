<table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer " width="100%" id="datatable-ajax2">
    <thead>
        <tr>
          <th width="5%">No</th>
          <th width="10%">Date</th>
          <th width="10%">IN</th>
          <th width="10%">Start</th>
          <th width="10%">End</th>
          <th width="10%">Status</th>
          <th width="10%">Good</th>
          <th width="10%">Reject</th>
          <th width="10%">Rework</th>
          <th width="10%">Std</th>
          <th width="10%">Remark</th>
        </tr>
      </thead>
      <tbody class="pre-scrollable">
       <?php
            if($_GET['roledtlid'] == '') {
              $roledtl = 0;
            } else {
              $roledtl = $_GET['roledtlid'];
            }
            $no = 1;
            if ($_GET['mpid'] == '42'){
                $tableprocess = "(select lot_no,process_type,master_process_id,master_type_process_id,lot_type,process_createdate,process_qty_good,process_qty_reject,username from laundry_process where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 4) as d";
                $fieldprocess = "d.lot_no,to_char(d.process_createdate,'HH24:MI:SS') as time_out,
                   COALESCE(d.process_qty_good,0) as process_qty_good,COALESCE(d.process_qty_reject,0) as process_qty_reject,to_char(d.process_createdate, 'DD-MON-YYYY') as datepro,username";
            } else {
              if($_GET['t'] == '1' || $_GET['t'] == '3'){
                  $tableprocess = "(select 
                                    lot_no,process_type,master_process_id,master_type_process_id,lot_type,max(process_createdate) as process_createdate,COALESCE(process_qty_good,0) as process_qty_good,COALESCE(process_qty_reject,0) as process_qty_reject,COALESCE(process_qty_repair,0) as process_qty_repair,sender,receiver,username from laundry_process a
                                      LEFT JOIN m_users b ON a.process_createdby=b.user_id where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 1
                                    GROUP BY lot_no,process_type,master_process_id,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,sender,receiver,username) as a 
                                    LEFT JOIN
                                      (select lot_no,process_type,master_process_id,master_type_process_id,lot_type,max(process_createdate) as process_createdate,COALESCE(process_qty_good,0) as process_qty_good,COALESCE(process_qty_reject,0) as process_qty_reject,COALESCE(process_qty_repair,0) as process_qty_repair,operator as operator_start,username  from laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id  where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 2 GROUP BY lot_no,process_type,master_process_id,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,operator,username) as b on a.lot_no=b.lot_no 
                                    LEFT JOIN
                                      (select lot_no,process_type,master_process_id,master_type_process_id,max(process_createdate) as process_createdate,lot_type,COALESCE(process_qty_good,0) as process_qty_good,COALESCE(process_qty_reject,0) as process_qty_reject,COALESCE(process_qty_repair,0) as process_qty_repair,COALESCE(process_qty_std,0) as process_qty_std,process_remark,operator as operator_end,username from laundry_process a
                                      LEFT JOIN m_users b ON a.process_createdby=b.user_id where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 4 GROUP BY lot_no,process_type,master_process_id,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,process_remark,operator,COALESCE(process_qty_std,0),username) as d on a.lot_no=d.lot_no";
                  $fieldprocess = "a.lot_no,
                     to_char(a.process_createdate, 'DD-MON-YYYY') as datepro,
                     to_char( a.process_createdate, 'DD-MM-YYYY' ) AS date_in,
                     to_char( b.process_createdate, 'DD-MM-YYYY' ) AS date_start,
                     to_char( d.process_createdate, 'DD-MM-YYYY' ) AS date_out,
                     to_char(a.process_createdate,'HH24:MI:SS') as time_in, 
                     to_char(b.process_createdate,'HH24:MI:SS') as time_start,
                     to_char(d.process_createdate,'HH24:MI:SS') as time_out,
                      CASE 
                        WHEN (COALESCE ( d.process_qty_good, 0 )) != 0 
                        THEN (COALESCE ( d.process_qty_good, 0 ))
                        WHEN (COALESCE ( b.process_qty_good, 0 )) != 0 
                        THEN (COALESCE ( b.process_qty_good, 0 ))
                        ELSE (COALESCE ( A.process_qty_good, 0 ))
                      END as process_qty_good,
                      CASE 
                        WHEN (COALESCE ( d.process_qty_reject, 0 )) != 0 
                        THEN (COALESCE ( d.process_qty_reject, 0 ))
                        WHEN (COALESCE ( b.process_qty_reject, 0 )) != 0 
                        THEN (COALESCE ( b.process_qty_reject, 0 ))
                        ELSE (COALESCE ( A.process_qty_reject, 0 ))
                      END as process_qty_reject,
                      CASE 
                        WHEN (COALESCE ( d.process_qty_repair, 0 )) != 0 
                        THEN (COALESCE ( d.process_qty_repair, 0 ))
                        WHEN (COALESCE ( b.process_qty_repair, 0 )) != 0 
                        THEN (COALESCE ( b.process_qty_repair, 0 ))
                        ELSE (COALESCE ( A.process_qty_repair, 0 ))
                      END as process_qty_repair,
                     d.process_remark,a.sender,a.receiver,b.operator_start,d.operator_end,d.process_qty_std,
                     a.username as username_in,b.username as username_start,d.username as username_end";
              } else {
                if ($_GET['t'] == '2') {
                  if($_GET['mpid'] == 2) { //jika view lot making 
                      $tableprocess = "(select lot_no,process_type,master_type_process_id,lot_type,max(process_createdate) as process_createdate,process_qty_good,process_qty_reject,process_qty_repair,process_remark,sender,receiver,username from laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_type_process_id = '".$_GET['mpid']."' and process_type = 4 GROUP BY lot_no,process_type,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,process_remark,sender,receiver,username) as d";
                      $fieldprocess = "d.lot_no,
                                     to_char(d.process_createdate, 'DD-MM-YYYY' ) AS date_in,
                                     to_char(d.process_createdate,'HH24:MI:SS') as time_in,
                                     COALESCE(d.process_qty_good,0) as process_qty_good,
                                     COALESCE(d.process_qty_reject,0) as process_qty_reject,
                                     COALESCE(d.process_qty_repair,0) as process_qty_repair,
                                     to_char(d.process_createdate, 'DD-MON-YYYY') as datepro,
                                     process_remark,d.sender,d.receiver,username as username_in";
                  } else {
                      $tableprocess = "(select 
                                    lot_no,process_type,master_type_process_id,lot_type,max(process_createdate) process_createdate,process_qty_good,process_qty_reject,process_qty_repair,sender,receiver,username from laundry_process a
                                      LEFT JOIN m_users b ON a.process_createdby=b.user_id 
                                     where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_type_process_id = '".$_GET['mpid']."' and process_type = 1
                                    GROUP BY lot_no,process_type,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,sender,receiver,username) as a 
                                    LEFT JOIN
                                      (select lot_no,process_type,master_type_process_id,lot_type,max(process_createdate) process_createdate,process_qty_good,process_qty_reject,process_qty_repair,operator as operator_start,username from laundry_process a
                                        LEFT JOIN m_users b ON a.process_createdby=b.user_id 
                                        where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_type_process_id = '".$_GET['mpid']."' and process_type = 2 GROUP BY lot_no,process_type,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,operator,username) as b on a.lot_no=b.lot_no 
                                    LEFT JOIN
                                      (select lot_no,process_type,master_type_process_id,lot_type,max(process_createdate) process_createdate,process_qty_good,process_qty_reject,process_qty_repair,process_remark,COALESCE(process_qty_std,0) as process_qty_std, operator as operator_end,username from laundry_process a
                                        LEFT JOIN m_users b ON a.process_createdby=b.user_id 
                                        where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_type_process_id = '".$_GET['mpid']."' and process_type = 4 GROUP BY lot_no,process_type,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,process_remark,operator,COALESCE(process_qty_std,0),username) as d on a.lot_no=d.lot_no";
                      $fieldprocess = "a.lot_no,
                                       to_char( A.process_createdate, 'DD-MM-YYYY' ) AS date_in,
                                       to_char( b.process_createdate, 'DD-MM-YYYY' ) AS date_start,
                                       to_char( d.process_createdate, 'DD-MM-YYYY' ) AS date_out,
                                       to_char(a.process_createdate,'HH24:MI:SS') as time_in, 
                                       to_char(b.process_createdate,'HH24:MI:SS') as time_start,
                                       to_char(d.process_createdate,'HH24:MI:SS') as time_out,
                                        CASE 
                                          WHEN (COALESCE ( d.process_qty_good, 0 )) != 0 
                                          THEN (COALESCE ( d.process_qty_good, 0 ))
                                          WHEN (COALESCE ( b.process_qty_good, 0 )) != 0 
                                          THEN (COALESCE ( b.process_qty_good, 0 ))
                                          ELSE (COALESCE ( A.process_qty_good, 0 ))
                                        END as process_qty_good,
                                        CASE 
                                          WHEN (COALESCE ( d.process_qty_reject, 0 )) != 0 
                                          THEN (COALESCE ( d.process_qty_reject, 0 ))
                                          WHEN (COALESCE ( b.process_qty_reject, 0 )) != 0 
                                          THEN (COALESCE ( b.process_qty_reject, 0 ))
                                          ELSE (COALESCE ( A.process_qty_reject, 0 ))
                                        END as process_qty_reject,
                                        CASE 
                                          WHEN (COALESCE ( d.process_qty_repair, 0 )) != 0 
                                          THEN (COALESCE ( d.process_qty_repair, 0 ))
                                          WHEN (COALESCE ( b.process_qty_repair, 0 )) != 0 
                                          THEN (COALESCE ( b.process_qty_repair, 0 ))
                                          ELSE (COALESCE ( A.process_qty_repair, 0 ))
                                        END as process_qty_repair,
                                       to_char(d.process_createdate, 'DD-MON-YYYY') as datepro,
                                       process_remark,a.sender,a.receiver,b.operator_start,d.operator_end,d.process_qty_std,
                                       a.username as username_in,
                                       b.username as username_start,
                                       d.username as username_end";
                  }
                  
                }
              }
            } 

            $selprocess = $con->select($tableprocess,$fieldprocess);
         //  echo "select $fieldprocess FROM $tableprocess";
            foreach ($selprocess as $pro) {

              if ($_GET['t'] == '3'){
                $goodqty = '0';
              } else {
                $goodqty = $pro['process_qty_good'];
              }

              if ($_GET['type'] == 'S'){
                $scrap = $pro['process_qty_reject'];
                $goodqty = '0';
                $rework = '0';
              } else if ($_GET['type'] == 'W'){
                $rework = $pro['process_qty_repair'];
                $goodqty = '0';
                $scrap = '0';
              } else {
                $goodqty = $pro['process_qty_good'];
                $scrap = $pro['process_qty_reject'];
                $rework = $pro['process_qty_repair'];
              }

              if ($_GET['t'] == '2' && $_GET['mpid'] == 6) {
                if($pro['time_in'] != '' && $pro['time_out'] != ''){
                  $statusend = "<b class='label label-primary'>Done</b>";
                } else {
                  $statusend = "<b class='label label-warning'>IN</b>";
                }
              } else {
                if($pro['time_start'] != '' && $pro['time_out'] != ''){
                  $statusend = "<b class='label label-primary'>Done</b>";
                } else if ($pro['time_start'] != '' && $pro['time_out'] == ''){
                  $statusend = "<b class='label label-info'>Start</b>";
                } else {
                  $statusend = "<b class='label label-warning'>IN</b>";
                }
              }

      ?>
            <tr>
                <td><?php echo $no;?></td>
                <td><?php echo $pro['datepro'];?></td>
                <td>
                  <?php if($pro['time_in']) {echo "Date : ".$pro['date_in']."<br> Time : ".$pro['time_in']."<br> Send : ".$pro['sender']."<br> Rec &ensp;: ".$pro['receiver']."<br><br>".$pro['username_in'];}?>
                </td>
                <td>
                  <?php if($pro['time_start']) {echo "Date : ".$pro['date_start']."<br> Time : ".$pro['time_start']."<br> Opt &ensp;: ".$pro['operator_start']."<br><br>".$pro['username_start'];}?>
                </td>
                <td>
                  <?php if($pro['time_out']) {echo "Date : ".$pro['date_out']."<br> Time : ".$pro['time_out']."<br> Opt &ensp;: ".$pro['operator_end']."<br><br>".$pro['username_end'];}?>
                </td>
                <td><?php echo $statusend;?></td>
                <td><?php echo $goodqty;?></td>
                <td><?php echo $scrap;?></td>
                <td><?php echo $rework;?></td>
                <td><?php echo $pro['process_qty_std'];?></td>
                <td><?php echo $pro['process_remark'];?></td>
            </tr>
<?php         
            }
?>
      </tbody>
</table>
