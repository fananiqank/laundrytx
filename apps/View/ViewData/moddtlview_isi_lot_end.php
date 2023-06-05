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
          <th width="10%">Remark</th>
        </tr>
      </thead>
      <tbody class="pre-scrollable">
       <?php
              $tableprocess = "laundry_process a LEFT JOIN m_users b ON a.process_createdby=b.user_id ";
                  $fieldprocess = "lot_no,
                     to_char(process_createdate, 'DD-MM-YYYY' ) AS date_out,
                     to_char(process_createdate,'HH24:MI:SS') as time_out,
                     process_qty_good,process_remark,sender,receiver,username";

            $selprocess = $con->select($tableprocess,$fieldprocess,"lot_no = '$_GET[lot]' and process_type = 5 and role_wo_id = '$_GET[roleid]'");
           //echo "select $fieldprocess FROM $tableprocess";
            foreach ($selprocess as $pro) {

      ?>
            <tr>
                <td>1</td>
                <td><?php echo $pro['date_out'];?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                  <?php if($pro['time_out']) {echo "Date : ".$pro['date_out']."<br> Time : ".$pro['time_out']."<br> Sender &ensp;: ".$pro['sender']."<br> Receiver &ensp;: ".$pro['receiver']."<br><br>".$pro['username'];}?>
                </td>
                <td><b class='label label-primary'>Done</b></td>
                <td><?php echo $pro['process_qty_good'];?></td>
                <td>0</td>
                <td>0</td>
                <td>&nbsp;</td>
            </tr>
<?php         
            }
?>
      </tbody>
</table>
