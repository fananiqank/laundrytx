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
header("Content-Disposition: attachment; filename=Report_Process_$date.xls");

$cmt = $con->searchseqcmt($_GET['cm']);
$colors = $con->searchseq($_GET['co']);
$exftydate = $_GET['xty'];
$tpro = $_GET['tpro'];
$dpro = $_GET['dpro'];
$tgl1js = $_GET['tgl1'];
$tgl2js = $_GET['tgl2'];

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
  $xty = "and DATE(ex_fty_date) = '".$exftydate."'";
} else {
  $xty = "";
}
if ($tpro != ''){
  $tpr = "and master_type_process_id = '".$tpro."'";
} else {
  $tpr = "";
}
if ($dpro != ''){
  $dpr = "and master_process_id = '".$dpro."'";
} else {
  $dpr = "";
}
if ($tgl1js != ''){
  $periode = "and DATE(process_createdate) between '".$tgl1js."' and '".$tgl2js."'";
} else {
  $periode = "";
}
?>
  <table class="table table-bordered table-hover pre-scrollable no-footer" id="datatable-ajax" width="175%">
      <thead align="center">
          <tr>
              <th colspan="18" style="text-align:center" ><h4>REPORT PROCESS TODAY<br />
               PERIODE <?php echo "$tgl1js s/d $tgl2js"; ?></h4></th>
          </tr>
          <tr>
              <th width="3%" rowspan="2">No</th>
              <th width="5%" rowspan="2">Lot No</th>
              <th width="10%" rowspan="2">Wo No</th>
              <th width="10%" rowspan="2">Color</th>
              <th width="10%" rowspan="2">Ex Fty Date</th>
              <th width="5%" rowspan="2">Seq</th>
              <th width="10%" rowspan="2">Qty</th>
              <th width="10%" colspan="3">IN</th> 
              <th width="10%" colspan="3">Start</th>
              <th width="10%" colspan="3">End</th>
              <th width="10%" colspan="3">Remark</th>
          </tr>
          <tr>
              <th width="15%">Time</th>
              <th width="15%">Pengirim</th>
              <th width="15%">Penerima</th>
              <th width="15%">Time</th>
              <th width="15%">MC</th>
              <th width="15%">Operator</th>
              <th width="15%">Time</th>
              <th width="15%">MC</th>
              <th width="15%">Operator</th>
              <th width="15%">Good</th>
              <th width="15%">Reject</th>
              <th width="15%">Repair</th>
          </tr>
      </thead>
      
      <tbody style="overflow-x: auto">
        <?php 
        

            $no = 1;
            $table = "(select ae.*,ab.proin,ab.prostart,ab.proend,ac.opstart,ac.opend,ad.machinestart,ad.machineend
          from  (
                SELECT * FROM
                      crosstab (
                        $$ 
                            SELECT
                            concat(lot_no:: TEXT,'_',role_wo_id:: TEXT) :: TEXT, 
                            process_type :: INT,
                            process_createdate :: TIMESTAMP
                            FROM
                            laundry_process 
                            WHERE
                            wo_no ilike '%'
                            $tpr $dpr $periode
                            ORDER BY
                            wo_no
                        $$
                      ) AS ct ( lot_no TEXT, proin TIMESTAMP, prostart TIMESTAMP,proend TIMESTAMP )
            ) as ab 
            LEFT JOIN (
                SELECT * FROM
                      crosstab (
                        $$ 
                            SELECT
                            concat(lot_no:: TEXT,'_',role_wo_id:: TEXT) :: TEXT,  
                            process_type :: INT,
                            operator :: TEXT
                            FROM
                            laundry_process 
                            WHERE
                            wo_no ilike '%'
                            AND process_type != 1
                            $tpr $dpr $periode
                            ORDER BY
                            wo_no
                        $$
                      ) AS ct ( lot_no TEXT, opstart TEXT,opend TEXT )  
            ) as ac ON ab.lot_no=ac.lot_no
              LEFT JOIN ( 
                SELECT * FROM
                      crosstab (
                        $$ 
                          SELECT
                          concat(z.lot_no:: TEXT,'_',role_wo_id:: TEXT) :: TEXT, 
                          process_type :: INT,
                          machine_name :: TEXT
                          FROM
                          laundry_process z 
                          JOIN laundry_master_machine y
                          ON z.machine_id=y.machine_id
                          JOIN ( select lot_no,machine_id 
                                 from laundry_process_machine 
                                 where process_machine_status = 1
                          ) as w
                          ON z.lot_no=w.lot_no and z.machine_id=w.machine_id
                          WHERE
                            wo_no ilike '%'
                            AND process_type != 1
                            $tpr $dpr $periode
                            ORDER BY
                            wo_no
                        $$
                      ) AS ct ( lot_no TEXT, machinestart TEXT,machineend TEXT )
            ) as ad ON ab.lot_no=ad.lot_no
            JOIN (
                SELECT 
                      a.lot_no,
                      concat(A.lot_no,'_',A.role_wo_id) as lotnorole,
                      a.process_qty_total as qty_total,
                      c.wo_no,
                      c.garment_colors,
                      DATE(c.ex_fty_date) as ex_fty_date,
                      process_type,
                      sender,
                      receiver,
                      b.process_qty_good,
                      b.process_qty_reject,
                      b.process_qty_repair,
                      a.role_wo_name_seq
                      FROM
                      laundry_process a
                      LEFT JOIN (select lot_no,process_qty_good,process_qty_reject,process_qty_repair,
                            role_wo_name_seq 
                            from laundry_process where process_type = 4
                            $tpr $dpr $periode 
                            ) as b
                      ON a.lot_no=b.lot_no and a.role_wo_name_seq=b.role_wo_name_seq
                      JOIN laundry_wo_master_dtl_proc c ON a.wo_master_dtl_proc_id=c.wo_master_dtl_proc_id
                      WHERE
                      a.process_type = 1 
                      $cm $co $xty
                      $tpr $dpr $periode 
            ) as ae ON ab.lot_no=ae.lotnorole
          ) as asi";
                      //echo "select * from $table";
            $selectall = $con->select($table,"*");
            
            foreach($selectall as $all) {

        ?>
        <tr>
              <td><?=$no?></td>
              <td><?=$all['lot_no']?></td>
              <td><?=$all['wo_no']?></td>
              <td><?=$all['garment_colors']?></td>
              <td><?=$all['ex_fty_date']?></td>
              <td><?=$all['role_wo_name_seq']?></td>
              <td><?=$all['qty_total']?></td>
              <td><?=$all['proin']?></td>
              <td><?=$all['sender']?></td>
              <td><?=$all['receiver']?></td>
              <td><?=$all['prostart']?></td>
              <td><?=$all['machinestart']?></td>
              <td><?=$all['opstart']?></td>
              <td><?=$all['proend']?></td>
              <td><?=$all['machineend']?></td>
              <td><?=$all['opend']?></td>
              <td><?=$all['process_qty_good']?></td>
              <td><?=$all['process_qty_reject']?></td>
              <td><?=$all['process_qty_repair']?></td>
        </tr>
        <?php $no++; } ?>
      </tbody>
  </table>

<script>
	   window.close();
</script>
