<?php

//error_reporting(0);
require( 'funlibs.php' );
//echo "bik";

$con=new Database;
// header("Content-type: application/octet-stream");
// header("Content-Disposition: attachment; filename=Ticket_Report.xls");//ganti nama sesuai keperluan
// header("Pragma: no-cache");
// header("Expires: 0");


// if($_GET['aksi']=="xls")
// {
// 	header("Content-Type: application/vnd.ms-excel");
	
// 	}


 if ($_GET['tg'] && $_GET['tg2']){
      if ($_GET['tg'] != 'A' && $_GET['tg2'] != 'A'){
      $a=explode('-',$_GET['tg']);
      $gg=$a[1]."/".$a[2]."/".$a[0];
      $jtg=$a[2]."-".$a[1]."-".$a[0];
      $b=explode("-",$_GET['tg2']);
      $wp=$b[1]."/".$b[2]."/".$b[0];
      $jtg2=$b[2]."-".$b[1]."-".$b[0];
      }
    }
?>
  <table class="tableku datatables-basic" id="datatable-ajax1" width="150%">
      <thead>
          <tr>
              <th colspan="12" style="text-align:center" ><h4>TASKS REPORT<br />
               PERIODE <?php echo "$jtg s/d $jtg2"; ?></h4></th>
          </tr>
          <tr>
              <th width="5%">No</th>
              <th width="10%">No Ticket</th>
              <th width="10%">Name</th>
              <th width="10%">Assign</th>
              <th width="10%">Category</th> 
              <th width="5%">Created Date Tasks</th>
              <th width="5%">Start Date Tasks</th>
              <th width="5%">End Date Tasks</th>
              <th width="5%">Priority</th>
              <th width="15%">Status</th> 
              <th width="10%">Days</th>
              <th width="5%">Remark</th>              
          </tr>
      </thead>
      
      <tbody style="overflow-x: auto">
       <?php
        if ($_GET['tg']) {
          if($_GET['c']==''){
            $cat="";
          }else{
            $cat="and a.id_kategori = $_GET[c]";
          }

          if($_GET['y']==''){
            $pri="";
          }else{
            $pri="and a.priority_tasks='$_GET[y]'";
          }

          if($_GET['t']==''){
            $sta="";
          }else{
            $sta="and b.status_tasks='$_GET[t]'";
          }

          if($_GET['g']==''){
            $asi="";
          }else{
            $asi="and a.id_agent='$_GET[g]'";
          }

          if($_GET['tg']== 'A' && $_GET['tg2']== 'A'){
            $tgl="";
          }else{
            $tgl="and DATE(a.created_date_tasks) BETWEEN '$_GET[tg]' AND '$_GET[tg2]'";
          }
        }
            $no = 1;
            $selectall = $con->select("trtasks a
            left join mtkategori d on a.id_kategori = d.id_kategori
            left join mtagent e on a.id_agent = e.id_agent
            left join mtpegawai f on e.id_pegawai = f.id_pegawai", 
            "a.id_tasks,a.no_tasks,a.nama_tasks,f.nama_pegawai,d.nama_kategori,a.created_date_tasks,a.start_date_tasks,
            a.end_date_tasks,a.priority_tasks,a.status_tasks,concat((DATEDIFF(CURDATE(),a.end_date_tasks)),'_',a.status_tasks,'_',IFNULL((DATEDIFF(a.closed_date_tasks,a.start_date_tasks)),0)) as stla,a.remark_tasks",
            "a.status_tasks != '' $cat $pri $sta $asi $tgl");
            // echo "select a.id_tasks,a.no_tasks,a.nama_tasks,f.nama_pegawai,d.nama_kategori,a.created_date_tasks,a.start_date_tasks,
            // a.end_date_tasks,a.priority_tasks,a.status_tasks,concat((DATEDIFF(CURDATE(),a.end_date_tasks)),'_',a.status_tasks) as stla,a.remark_tasks
            // from trtasks a
            // left join mtkategori d on a.id_kategori = d.id_kategori
            // left join mtagent e on a.id_agent = e.id_agent
            // left join mtpegawai f on e.id_pegawai = f.id_pegawai
            //   where b.status_tasks != '' $cat $pri $sta $asi $tgl";
            foreach($selectall as $all) {
           
            if ($all['status_data'] == 1 ){
            	$sta = "Open";
            } else if ($all['status_data'] == 2 ){
            	$sta = "On Progress";
            } else if ($all['status_data'] == 3 ){
            	$sta = "Hold";
            } else if ($all['status_data'] == 4 ){
            	$sta = "Closed";
            } else if ($all['status_data'] == 5 ){
            	$sta = "Hold Request";
            } else if ($all['status_data'] == 6 ){
            	$sta = "Assigned";
            } else if ($all['status_data'] == 0 ){
            	$sta = "Reject";
            } 

            if ($all['priority_wo'] == 1 ){
            	$pri = "Low";
            } else if ($all['priority_wo'] == 2 ){
            	$pri = "Middle";
            } else if ($all['priority_wo'] == 3 ){
            	$pri = "High";
            } 

            $exsla = explode('_',$all['stla']);
  					$sla = $exsla[0];
  					$st = $exsla[1];
  					$clo = $exsla[2];
  					if ($st == 4) {
  						if ($sla > 0){
  							$isijum = "<b>+ $clo</b>";
  						} else if ($clo == 0){
  							$isijum = "<b>$clo</b>";
  						} else if ($clo < 0){
  							$isijum = "<b>$clo</b>";
  						}
  					} else {
  						if ($sla > 0){
  							$isijum = "<b>+ $sla</b>";
  						} else if ($sla == 0){
  							$isijum = "<b>$sla</b>";
  						} else if ($sla < 0){
  							$isijum = "<b>$sla</b>";
  						}
  					}
        ?>
        <tr>
              <td><?=$no?></td>
              <td><?=$all['no_tasks']?></td>
              <td><?=$all['nama_tasks']?></td>
              <td><?=$all['nama_pegawai']?></td>
              <td><?=$all['nama_kategori']?></td>
              <td><?=$all['created_date_tasks']?></td>
              <td><?=$all['start_date_tasks']?></td>
              <td><?=$all['end_date_tasks']?></td>
              <td><?=$pri?></td>
              <td><?=$sta?></td>
              <td><?=$isijum?></td>
              <td><?=$all['remark_tasks']?></td>
        </tr>
        <?php $no++; } ?>
      </tbody>
      <input type="hidden" name="hal" id="hal" value="<?=$_GET[p]?>">
  </table>

<script>
	   window.close();
</script>
