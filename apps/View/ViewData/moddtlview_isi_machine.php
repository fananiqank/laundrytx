<table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer " width="100%" id="datatable-ajax2">
    <thead>
        <tr>
          <th width="5%">No</th>
          <th width="10%">Date</th>
          <th width="10%">IN</th>
          <th width="10%">Start</th>
          <th width="10%">End</th>
          <th width="10%">Status</th>
          <th width="6%">Good</th>
          <th width="6%">Reject</th>
          <th width="6%">Rework</th>
          <th width="6%">Std</th>
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
        $selmachine = $con->select("laundry_process_machine_progress a JOIN laundry_master_machine b on a.machine_id=b.machine_id","b.machine_name,a.machine_id,a.master_process_id,a.process_mach_status","a.lot_no = '".$_GET['lot']."' and a.master_process_id = '".$_GET['mpid']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id= '".$_GET['roledtlid']."' and process_mach_onprogress = 3");
    //   echo "select b.machine_name,a.machine_id,a.master_process_id,a.process_machine_status from laundry_process_machine a JOIN laundry_master_machine b on a.machine_id=b.machine_id where a.lot_no = '".$_GET['lot']."' and a.master_process_id = '".$_GET['mpid']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id= '".$_GET['roledtlid']."'";
        foreach ($selmachine as $machine) {
          if ($machine['process_machine_status'] == 1) {
            $styleheader = "style='background-color:#87CEFA;color:#000000'";
          } else {
            $styleheader = "style='background-color:#FFC0CB;color:#000000'";
          }
?>
            <tr>
                <td colspan="11" <?=$styleheader?>><b>Machine : <?php echo $machine['machine_name']; ?></b></td>
            </tr>
            
<?php
     
      $explot = explode('-',$_GET['lot']);
      $lotres = substr($explot[0],-4,1);
      if ($_GET['type'] == 'B' || $_GET['type'] == 'C'){
          $tableselect = "(select lot_no,process_type,master_process_id,master_type_process_id,lot_type,
                           max(process_createdate) as process_createdate,COALESCE(process_qty_good,0) AS process_qty_good,COALESCE(process_qty_reject,0) AS process_qty_reject,COALESCE(process_qty_repair,0) AS process_qty_repair,operator as operator_start,username
                           from laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 2 and machine_id = '".$machine['machine_id']."'
                           GROUP BY lot_no,process_type,master_process_id,master_type_process_id,lot_type.process_qty_good,process_qty_reject,process_qty_repair,operator,username) as b
                          LEFT JOIN
                          (select lot_no,process_type,master_process_id,master_type_process_id,lot_type,max(process_createdate) as process_createdate,COALESCE(process_qty_good,0) AS process_qty_good,COALESCE(process_qty_reject,0) AS process_qty_reject,COALESCE(process_qty_repair,0) AS process_qty_repair,process_remark,operator as operator_broken,username
                           from laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id 
                           where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 3 and machine_id = '".$machine['machine_id']."'
                           GROUP BY lot_no,process_type,master_process_id,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,process_remark,operator,username) as c on b.lot_no=c.lot_no 
                          LEFT JOIN
                          (select lot_no,process_type,master_process_id,master_type_process_id,lot_type,max(process_createdate) as process_createdate,process_qty_good,process_qty_reject,COALESCE(process_qty_repair,0) AS process_qty_repair,COALESCE(process_qty_std,0) AS process_qty_std,process_remark, operator as operator_end,username
                           from laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id
                           where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 4 GROUP BY lot_no,process_type,master_process_id,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,COALESCE(process_qty_std,0),process_remark, operator,username) as d on b.lot_no=d.lot_no
                         ";  
          $fieldselect = "b.lot_no,
                          to_char(d.process_createdate,'DD-MON-YYYY') as datepro,
                          to_char( b.process_createdate, 'DD-MM-YYYY' ) AS date_start,
                          to_char( c.process_createdate, 'DD-MM-YYYY' ) AS date_end,
                          to_char( d.process_createdate, 'DD-MM-YYYY' ) AS date_out,
                          to_char(b.process_createdate,'HH24:MI:SS') as time_start,
                          to_char(c.process_createdate,'HH24:MI:SS') as time_end,
                          to_char(d.process_createdate,'HH24:MI:SS') as time_out,
                          b.operator_start,
                          CASE 
                            WHEN c.process_qty_good != 0 THEN c.process_qty_good
                            ELSE d.process_qty_good
                          END as process_qty_good,
                          CASE 
                            WHEN c.process_qty_reject != 0 THEN c.process_qty_reject
                            ELSE d.process_qty_reject
                          END as process_qty_reject,
                          CASE 
                            WHEN c.process_qty_repair != 0 THEN c.process_qty_repair
                            ELSE d.process_qty_repair
                          END as process_qty_repair,
                          CASE 
                            WHEN C.process_remark != '' THEN c.process_remark
                            ELSE d.process_remark 
                          END as process_remark,
                          CASE 
                            WHEN C.operator_broken != '' THEN C.operator_broken
                            ELSE d.operator_end 
                          END as operator_end,
                          d.process_qty_std,b.username as username_start,
                          CASE 
                            WHEN C.username != '' THEN c.username
                            ELSE d.username 
                          END as username_end
                          ";  
      }
      else {
          $tableselect = "(select lot_no,process_type,master_process_id,master_type_process_id,lot_type,
                           max(process_createdate) as process_createdate,process_qty_good,
                           process_qty_reject,process_qty_repair,sender,receiver,username 
                           from laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id
                           where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 1
                           GROUP BY lot_no,process_type,master_process_id,master_type_process_id,lot_type,process_qty_good,
                           process_qty_reject,process_qty_repair,sender,receiver,username) as a 
                          LEFT JOIN
                          (select lot_no,process_type,master_process_id,master_type_process_id,lot_type,
                          max(process_createdate) as process_createdate,operator as operator_start,username 
                           from laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id
                           where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 2 and machine_id = '".$machine['machine_id']."'
                           GROUP BY lot_no,process_type,master_process_id,master_type_process_id,lot_type,operator,username) as b on a.lot_no=b.lot_no 
                          LEFT JOIN
                          (select lot_no,process_type,master_process_id,master_type_process_id,lot_type, max(process_createdate) as process_createdate,COALESCE(process_qty_good,0) AS process_qty_good,COALESCE(process_qty_reject,0) AS process_qty_reject,COALESCE(process_qty_repair,0) AS process_qty_repair,process_remark,operator as operator_broken,username
                           from laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id 
                           where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 3 and machine_id = '".$machine['machine_id']."' 
                           GROUP BY lot_no,process_type,master_process_id,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,process_remark,operator,username) as c on a.lot_no=c.lot_no 
                          LEFT JOIN
                          (select lot_no,process_type,master_process_id,master_type_process_id,lot_type,max(process_createdate) as process_createdate,COALESCE(process_qty_good,0) as process_qty_good,COALESCE(process_qty_reject,0) as process_qty_reject,COALESCE(process_qty_repair,0) AS process_qty_repair,COALESCE(process_qty_std,0) AS process_qty_std,process_remark,operator as operator_end,username
                           from laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id
                           where lot_no = '".$_GET['lot']."' and role_wo_id = '".$_GET['roleid']."' and role_dtl_wo_id = '".$roledtl."' and master_process_id = '".$_GET['mpid']."' and process_type = 4 GROUP BY lot_no,process_type,master_process_id,master_type_process_id,lot_type,process_qty_good,process_qty_reject,process_qty_repair,COALESCE(process_qty_std,0),process_remark,operator,username) as d on a.lot_no=d.lot_no
                         ";  
          $fieldselect = "a.lot_no,
                          to_char(a.process_createdate,'DD-MON-YYYY') as datepro,
                          to_char( a.process_createdate, 'DD-MM-YYYY' ) AS date_in,
                          to_char( b.process_createdate, 'DD-MM-YYYY' ) AS date_start,
                          to_char( c.process_createdate, 'DD-MM-YYYY' ) AS date_end,
                          to_char( d.process_createdate, 'DD-MM-YYYY' ) AS date_out,
                          to_char(a.process_createdate,'HH24:MI:SS') as time_in, 
                          to_char(b.process_createdate,'HH24:MI:SS') as time_start,
                          to_char(c.process_createdate,'HH24:MI:SS') as time_end,
                          to_char(d.process_createdate,'HH24:MI:SS') as time_out,
                          a.process_qty_good as qtygood_in,a.process_qty_reject as qtyreject_in,
                          a.process_qty_repair as qtyrepair_in,
                          a.sender,a.receiver,b.operator_start,
                          CASE 
                            WHEN c.process_qty_good != 0 THEN c.process_qty_good
                            ELSE d.process_qty_good
                          END as process_qty_good,
                          CASE 
                            WHEN c.process_qty_reject != 0 THEN c.process_qty_reject
                            ELSE d.process_qty_reject
                          END as process_qty_reject,
                          CASE 
                            WHEN c.process_qty_repair != 0 THEN c.process_qty_repair
                            ELSE d.process_qty_repair
                          END as process_qty_repair,
                          CASE 
                            WHEN C.process_remark != '' THEN c.process_remark
                            ELSE d.process_remark 
                          END as process_remark,
                          CASE 
                            WHEN C.operator_broken != '' THEN C.operator_broken
                            ELSE d.operator_end 
                          END as operator_end,
                          d.process_qty_std,
                          a.username as username_in,
                          b.username as username_start,
                          CASE 
                            WHEN C.username != '' THEN c.username
                            ELSE d.username 
                          END as username_end";  
      } 
                $selprocess = $con->select($tableselect,$fieldselect);
              //  echo "select $fieldselect from $tableselect";
                foreach ($selprocess as $pro) {
                            if ($_GET['t'] == '3'){
                              $goodqty = '0';
                              $rejectqty = $pro['process_qty_reject'];
                              $repairqty = $pro['process_qty_repair'];
                            } else if ($_GET['t'] == '5'){
                              $goodqty = $pro['qtygood_in'];
                              $rejectqty = $pro['qtyreject_in'];
                              $repairqty = $pro['qtyrepair_in'];
                            } else {
                              $goodqty = $pro['process_qty_good'];
                              $rejectqty = $pro['process_qty_reject'];
                              $repairqty = $pro['process_qty_repair'];
                            }

                            if($pro['time_end'] != ''){
                              $timeend = $pro['time_end'];
                              $dateend = $pro['date_end'];
                              $statusend = "<b class='label label-danger'>Broken</b>";
                            } else {
                                if($pro['time_start'] != '' && $pro['time_out'] != ''){
                                  $statusend = "<b class='label label-primary'>Done</b>";
                                } else if ($pro['time_start'] != '' && $pro['time_out'] == ''){
                                  $statusend = "<b class='label label-info'>Start</b>";
                                } else {
                                  $statusend = "<b class='label label-warning'>IN</b>";
                                }
                              $timeend = $pro['time_out'];
                              $dateend = $pro['date_out'];
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
                          <?php if($timeend) {echo "Date : ".$dateend."<br> Time : ".$timeend."<br> Opt &ensp;: ".$pro['operator_end']."<br><br>".$pro['username_end'];}?>
                        </td>
                        <td><?php echo $statusend;?></td>
                        <td><?php echo $goodqty;?></td>
                        <td><?php echo $rejectqty;?></td>
                        <td><?php echo $repairqty;?></td>
                        <td><?php echo $pro['process_qty_std'];?></td>
                        <td><?php echo $pro['process_remark'];?></td>
                    </tr>
                
<?php         
                }
?>
                
<?php 
    $no++;
        }
?>
      </tbody>
</table>
