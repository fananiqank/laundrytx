<?php 
$no = 1;

if ($_GET['c'] != ''){
    require( '../../../funlibs.php' );
    $con=new Database;
    session_start();
    if ($_GET['c'] == '0'){
        $process = "";
    } else {
        $process = "and master_process_name ILIKE '%$_GET[c]%'";
    }
    foreach ($con->select("laundry_wo_master_dtl_proc","role_wo_master_id","wo_master_dtl_proc_id = '".$lotnum['wo_master_dtl_proc_id']."'") as $roleid) {}
    $id = $roleid['role_wo_master_id'];

    if ($_GET['type'] == 'A'){
        $tablerole = "laundry_wo_master_dtl_proc F
                JOIN laundry_receive G ON F.wo_master_dtl_proc_id = G.wo_master_dtl_proc_id
                JOIN laundry_role_child H ON G.rec_no = H.lot_no and lot_type = '1' 
                JOIN laundry_role_wo A ON H.role_wo_id = A.role_wo_id
                LEFT JOIN laundry_role_dtl_wo B ON H.role_dtl_wo_id = B.role_dtl_wo_id
                LEFT JOIN laundry_master_process C ON b.master_process_id = C.master_process_id 
                LEFT JOIN (select max(process_type)process_type,role_wo_id,master_type_process_id,lot_no,process_qty_good, process_qty_reject
                             from laundry_process 
                           where role_wo_master_id = '".$_GET['id']."' and 
                                 master_type_process_id not between 4 and 5 and
                               lot_no = '".$_GET['lot']."'
                               GROUP BY role_wo_id,master_type_process_id,lot_no,process_qty_good, process_qty_reject    
                ) as D on D.role_wo_id=A.role_wo_id 
                LEFT JOIN (select max(process_type)process_type,role_wo_id,m.master_process_id,role_dtl_wo_id,lot_no,
                            n.master_process_name as change_name,change_process
                            from laundry_process m LEFT JOIN laundry_master_process n on m.change_process=n.master_process_id 
                            where role_wo_master_id = '".$_GET['id']."' and 
                                master_type_process_id between 4 and 5 and
                                    lot_no = '".$_GET['lot']."'
                                    GROUP BY role_dtl_wo_id,role_wo_id, m.master_process_id,lot_no,change_process,n.master_process_name
                                    ORDER BY process_type DESC
                ) as E on E.role_dtl_wo_id=B.role_dtl_wo_id";

        $fieldrole = "DISTINCT E.master_process_id,
                      role_wo_name,master_process_name,
                      H.role_wo_id,
                      COALESCE(E.role_dtl_wo_id,0) as role_dtl_wo_id,
                      D.lot_no as lot_number,
                      E.lot_no as dtl_lot_number,
                      B.master_process_id as master_process_id_before,
                      A.role_wo_seq,
                      A.role_wo_name_seq,
                      B.role_dtl_wo_seq,
                      D.master_type_process_id,
                      A.master_type_process_id as master_type_id,
                      H.role_wo_seq as wo_seq_child,
                      H.role_dtl_wo_seq as dtl_wo_seq_child,
                      H.role_child_modifydate,
                      D.process_type,
                      E.process_type as process_type_dtl,
                      COALESCE(E.change_process,0) as change_process,
                      E.change_name";
          
        $whererole = "A.role_wo_master_id = '".$_GET['id']."' and G.rec_no = '".$_GET['lot']."' $process";

    } else {
    
        $tablerole = "laundry_wo_master_dtl_proc F
                JOIN laundry_receive G ON F.wo_master_dtl_proc_id = G.wo_master_dtl_proc_id
                JOIN laundry_role_child H ON G.rec_no = H.lot_no and lot_type = '1' 
                JOIN laundry_role_wo A ON H.role_wo_id = A.role_wo_id
                LEFT JOIN laundry_role_dtl_wo B ON H.role_dtl_wo_id = B.role_dtl_wo_id
                LEFT JOIN laundry_master_process C ON b.master_process_id = C.master_process_id 
                LEFT JOIN (select max(process_type)process_type,role_wo_id,master_type_process_id,lot_no,process_qty_good, process_qty_reject
                             from laundry_process 
                           where role_wo_master_id = '".$_GET['id']."' and 
                                 master_type_process_id not between 4 and 5 and
                               lot_no = '".$_GET['lot']."'
                               GROUP BY role_wo_id,master_type_process_id,lot_no,process_qty_good, process_qty_reject    
                ) as D on D.role_wo_id=A.role_wo_id 
                LEFT JOIN (select max(process_type)process_type,role_wo_id,m.master_process_id,role_dtl_wo_id,lot_no,
                            n.master_process_name as change_name,change_process
                            from laundry_process m LEFT JOIN laundry_master_process n on m.change_process=n.master_process_id 
                            where role_wo_master_id = '".$_GET['id']."' and 
                                master_type_process_id between 4 and 5 and
                                    lot_no = '".$_GET['lot']."'
                                    GROUP BY role_dtl_wo_id,role_wo_id, m.master_process_id,lot_no,change_process,n.master_process_name
                                    ORDER BY process_type DESC
                ) as E on E.role_dtl_wo_id=B.role_dtl_wo_id";

        $fieldrole = "DISTINCT E.master_process_id,
                      role_wo_name,master_process_name,
                      H.role_wo_id,
                      COALESCE(E.role_dtl_wo_id,0) as role_dtl_wo_id,
                      D.lot_no as lot_number,
                      E.lot_no as dtl_lot_number,
                      B.master_process_id as master_process_id_before,
                      A.role_wo_seq,
                      A.role_wo_name_seq,
                      B.role_dtl_wo_seq,
                      D.master_type_process_id,
                      A.master_type_process_id as master_type_id,
                      H.role_wo_seq as wo_seq_child,
                      H.role_dtl_wo_seq as dtl_wo_seq_child,
                      H.role_child_modifydate,
                      D.process_type,
                      E.process_type as process_type_dtl,
                      COALESCE(E.change_process,0) as change_process,
                      E.change_names";
          
        $whererole = "A.role_wo_master_id = '".$_GET['id']."' and G.lot_no = '".$_GET['lot']."' and 
                      CONCAT(B.master_process_id,'_',A.role_wo_id,'_',E.lot_no) NOT IN  
                          ( select CONCAT(COALESCE(master_process_id,0),'_',role_wo_id,'_',lot_no) as master_process_id
                            from laundry_process 
                            where role_wo_master_id = '".$_GET['id']."' and lot_type = 1
                          GROUP BY master_process_id,role_wo_id,lot_no) and 
                          CONCAT(A.master_type_process_id,'_',A.role_wo_id,'_',E.lot_no) NOT IN  
                          (select CONCAT(COALESCE(master_type_process_id,0),'_',role_wo_id,'_',lot_no) as master_type_process_id
                            from laundry_process 
                            where role_wo_master_id = '".$_GET['id']."' and lot_type = 1) $process";
    }

} else {
    
    foreach ($con->select("laundry_wo_master_dtl_proc","role_wo_master_id","wo_master_dtl_proc_id = '".$lotnum['wo_master_dtl_proc_id']."'") as $roleid) {}
    $id = $roleid['role_wo_master_id'];

    if ($_GET['type'] == 'A'){
        $tablerole = "laundry_wo_master_dtl_proc F
                JOIN laundry_receive G ON F.wo_master_dtl_proc_id = G.wo_master_dtl_proc_id
                JOIN laundry_role_child H ON G.rec_no = H.lot_no and lot_type = '1' 
                JOIN laundry_role_wo A ON H.role_wo_id = A.role_wo_id
                LEFT JOIN laundry_role_dtl_wo B ON H.role_dtl_wo_id = B.role_dtl_wo_id
                LEFT JOIN laundry_master_process C ON b.master_process_id = C.master_process_id 
                LEFT JOIN (select max(process_type)process_type,role_wo_id,master_type_process_id,lot_no,process_qty_good, process_qty_reject
                             from laundry_process 
                           where role_wo_master_id = '".$id."' and 
                                 master_type_process_id not between 4 and 5 and
                               lot_no = '".$_GET['lot']."'
                               GROUP BY role_wo_id,master_type_process_id,lot_no,process_qty_good, process_qty_reject    
                ) as D on D.role_wo_id=A.role_wo_id 
                LEFT JOIN (select max(process_type)process_type,role_wo_id,m.master_process_id,role_dtl_wo_id,lot_no,
			                      n.master_process_name as change_name,change_process
			                      from laundry_process m LEFT JOIN laundry_master_process n on m.change_process=n.master_process_id 
			                      where role_wo_master_id = '".$id."' and 
			                      		master_type_process_id between 4 and 5 and
		                                lot_no = '".$_GET['lot']."'
		                                GROUP BY role_dtl_wo_id,role_wo_id,	m.master_process_id,lot_no,change_process,n.master_process_name
		                                ORDER BY process_type DESC
                ) as E on E.role_dtl_wo_id=B.role_dtl_wo_id";

        $fieldrole = "DISTINCT E.master_process_id,
                      role_wo_name,master_process_name,
                      H.role_wo_id,
                      COALESCE(E.role_dtl_wo_id,0) as role_dtl_wo_id,
                      D.lot_no as lot_number,
                      E.lot_no as dtl_lot_number,
                      B.master_process_id as master_process_id_before,
                      A.role_wo_seq,
                      A.role_wo_name_seq,
                      B.role_dtl_wo_seq,
                      D.master_type_process_id,
                      A.master_type_process_id as master_type_id,
                      H.role_wo_seq as wo_seq_child,
                      H.role_dtl_wo_seq as dtl_wo_seq_child,
                      H.role_child_modifydate,
                      D.process_type,
                      E.process_type as process_type_dtl,
                      COALESCE(E.change_process,0) as change_process,
                      E.change_name";
          
        $whererole = "A.role_wo_master_id = '".$id."' and G.rec_no = '".$_GET['lot']."'";

    } else {
    
        $tablerole = "laundry_wo_master_dtl_proc F 
                      JOIN laundry_lot_number G ON F.wo_master_dtl_proc_id = G.wo_master_dtl_proc_id
                      JOIN laundry_role_child H ON G.lot_no = H.lot_no
                      JOIN laundry_role_wo A ON H.role_wo_id = A.role_wo_id
                      LEFT JOIN laundry_role_dtl_wo B ON H.role_dtl_wo_id = B.role_dtl_wo_id
                      LEFT JOIN laundry_master_process C ON B.master_process_id = C.master_process_id
                      LEFT JOIN (select max(process_type)process_type,role_wo_id,master_type_process_id,lot_no 
                             from laundry_process 
                           where role_wo_master_id = '".$id."' and 
                                 master_type_process_id not between 4 and 5 and
                               lot_no = '".$_GET['lot']."'
                               GROUP BY role_wo_id,master_type_process_id,lot_no    
                      ) as D on D.role_wo_id=A.role_wo_id 
                      LEFT JOIN (select max(process_type)process_type,role_wo_id,m.master_process_id,role_dtl_wo_id,lot_no,
			                      n.master_process_name as change_name,change_process
			                      from laundry_process m LEFT JOIN laundry_master_process n on m.change_process=n.master_process_id 
			                      where role_wo_master_id = '".$id."' and 
			                      		master_type_process_id between 4 and 5 and
		                                lot_no = '".$_GET['lot']."'
		                                GROUP BY role_dtl_wo_id,role_wo_id,	m.master_process_id,lot_no,change_process,n.master_process_name
		                                ORDER BY process_type DESC
                      ) as E on E.role_dtl_wo_id=B.role_dtl_wo_id";

        $fieldrole = "DISTINCT E.master_process_id,
                      role_wo_name,master_process_name,
                      H.role_wo_id,
                      COALESCE(E.role_dtl_wo_id,0) as role_dtl_wo_id,
                      D.lot_no as lot_number,
                      E.lot_no as dtl_lot_number,
                      B.master_process_id as master_process_id_before,
                      A.role_wo_seq,
                      A.role_wo_name_seq,
                      B.role_dtl_wo_seq,
                      D.master_type_process_id,
                      A.master_type_process_id as master_type_id,
                      H.role_wo_seq as wo_seq_child,
                      H.role_dtl_wo_seq as dtl_wo_seq_child,
                      H.role_child_modifydate,
                      D.process_type,
                      E.process_type as process_type_dtl,
                      COALESCE(E.change_process,0) as change_process,
                      E.change_name";
          
        $whererole = "A.role_wo_master_id = '".$id."' and G.lot_no = '".$_GET['lot']."' and 
                      CONCAT(B.master_process_id,'_',A.role_wo_id,'_',E.lot_no) NOT IN  
                          ( select CONCAT(COALESCE(master_process_id,0),'_',role_wo_id,'_',lot_no) as master_process_id
                            from laundry_process 
                            where role_wo_master_id = '".$id."' and lot_type = 1
                          GROUP BY master_process_id,role_wo_id,lot_no) and 
                          CONCAT(A.master_type_process_id,'_',A.role_wo_id,'_',E.lot_no) NOT IN  
                          (select CONCAT(COALESCE(master_type_process_id,0),'_',role_wo_id,'_',lot_no) as master_type_process_id
                            from laundry_process 
                            where role_wo_master_id = '".$id."' and lot_type = 1)";
        //select untuk lotmaking END
        foreach($con->select("laundry_process","lot_no,role_wo_id,master_type_process_id,'2' as type,lot_type,'0' as change_process,process_qty_good","lot_no = '".$_GET['lot']."' and process_type = 5") as $endlm){}
    }
}
  
if($_GET['type'] != 'R' && $_GET['type'] != 'S' && $_GET['type'] != 'W'){

// jika lot telah di split atau di combine
  if ($cekparent > 0 && $lotnum['statuslot'] == 0 && $lotnum['lot_parent'] == 1){
      foreach($con->select("laundry_lot_number a join laundry_process b on a.lot_no=b.lot_no join laundry_master_process c on b.master_process_id=c.master_process_id JOIN laundry_lot_event d on a.lot_no = d.lot_no","c.master_process_name,b.master_process_id,a.lot_no as lot_number,lot_qty as qty,b.role_wo_id,b.role_dtl_wo_id,d.event_type","a.lot_no = '".$_GET['lot']."'","process_id") as $lot){}
        if ($lot['event_type'] == 2){
          $keterangan = "Split";
        } else if ($lot['event_type'] == 3){
          $keterangan = "Combine";
        }
      
?>
    <tr>
        <td>1</td>
        <td><?=$lot['master_process_name']?></td>
        <td><?=$lot['qty']?></td>
        <td>0</td>
        <td>0</td>
        <td>
            <a href="javascript:void(0)" class="label label-info" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $_GET[lot]; ?>','<?php echo $lot[role_wo_id]; ?>','<?php echo $lot[role_dtl_wo_id]; ?>','<?php echo $lot[master_process_id]; ?>',5,'<?php echo $_GET[type]; ?>')">Done</a>
        </td>
    </tr>
<?php
  }
// end split atau combine ===============
  else { 
        //jika ada lot making end =============
        if($endlm['lot_no'] != ''){
?>
        <tr>
            <td>-</td>
            <td>Lot Making End</td>
            <td><?php echo $endlm['process_qty_good']; ?></td>
            <td>0</td>
            <td>0</td>
            <td><a href="javascript:void(0)" class="label label-info" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $_GET[lot]; ?>','<?php echo $endlm[role_wo_id]; ?>','0','<?php echo $endlm[master_type_process_id]; ?>',6,'<?php echo $_GET[type]; ?>')">Done</a></td>
        </tr>
<?php 
        } //end ada lot making end =============

      $sellot = $con->select($tablerole,$fieldrole,$whererole,"role_child_modifydate,a.role_wo_seq,b.role_dtl_wo_seq");
       //echo "select $fieldrole from $tablerole where $whererole order by role_child_modifydate,a.role_wo_seq,b.role_dtl_wo_seq";
      foreach ($sellot as $lot) {
      
        if ($lot['master_process_name'] == ''){
          $name = $lot['role_wo_name'];
          $whereprocess = "and master_type_process_id = '".$lot['master_type_process_id']."' and role_wo_id = '".$lot['role_wo_id']."'";
        } else {
            if($lot['master_type_id'] == 4){
                $wp = "DP".$lot['role_wo_name_seq']."-";
            } else {
                $wp = "WP".$lot['role_wo_name_seq']."-";
            }

            if($lot['change_name'] != ''){
            	$name = $wp.$lot['master_process_name']."<br><i>Changed to <b>".$lot['change_name']."</b></i>";
            } else {
            	$name = $wp.$lot['master_process_name'];
            }
            
            $whereprocess = "and role_wo_id = '".$lot['role_wo_id']."' and role_dtl_wo_id = '".$lot['role_dtl_wo_id']."' and master_process_id = '".$lot['master_process_id']."'";
        }

        if ($lot['lot_number'] == ''){
          $lotnumber = $lot['dtl_lot_number'];
        } else {
          $lotnumber = $lot['lot_number'];
        }


        //select qty terakhir dari process sesuai dengan process terakhir.
        foreach ($con->select("laundry_process","process_qty_good,process_qty_reject,process_qty_repair","lot_no = '".$lotnumber."' $whereprocess","process_type DESC","1") as $qty) {}
        // ===========================
          
        
            if($lot['master_process_id'] != '') {
              $qtygood = $qty['process_qty_good'];
              $qtyreject = $qty['process_qty_reject'];
              $qtyrework = $qty['process_qty_repair'];
            } else {
                if ($lot['master_type_process_id'] != '') {
                  $qtygood = $qty['process_qty_good'];
                  $qtyreject = $qty['process_qty_reject'];
                  $qtyrework = $qty['process_qty_repair'];
                }
                else { 
                  $qtygood = 0;
                  $qtyreject = 0;
                  $qtyrework = 0;
                }
            }
      
?>      
 
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $qtygood; ?></td>
                <td><?php echo $qtyreject; ?></td>
                <td><?php echo $qtyrework; ?></td>
                <td>
<?php 
                if($lot['master_process_id'] != '') {
                    
                      if ($lot['process_type_dtl'] == '4'){
?>
                        <a href="javascript:void(0)" class="label label-info" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $_GET[lot]; ?>','<?php echo $lot[role_wo_id]; ?>','<?php echo $lot[role_dtl_wo_id]; ?>','<?php echo $lot[master_process_id]; ?>',1,'<?php echo $_GET[type]; ?>','<?php echo $lot[process_type_dtl]; ?>','<?php echo $lot[change_process]; ?>')">Done</a>
<?php 
                      } else if ($lot['process_type_dtl'] == '1'){
?>
                        <a href="javascript:void(0)" class="label label-warning" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $_GET[lot]; ?>','<?php echo $lot[role_wo_id]; ?>','<?php echo $lot[role_dtl_wo_id]; ?>','<?php echo $lot[master_process_id]; ?>',1,'<?php echo $_GET[type]; ?>','<?php echo $lot[process_type_dtl]; ?>','<?php echo $lot[change_process]; ?>')">IN</a>
<?php 
                      } else if ($lot['process_type_dtl'] == '2' || $lot['process_type_dtl'] == '3' ){
?>
                        <a href="javascript:void(0)" class="label label-success" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $_GET[lot]; ?>','<?php echo $lot[role_wo_id]; ?>','<?php echo $lot[role_dtl_wo_id]; ?>','<?php echo $lot[master_process_id]; ?>',1,'<?php echo $_GET[type]; ?>','<?php echo $lot[process_type_dtl]; ?>','<?php echo $lot[change_process]; ?>')">Onprogress</a>
<?php 
                      } else {
                        echo "";
                      }
                } else {
                      if ($lot['master_type_process_id'] != '') {
                          if ($lot['process_type'] == '4'){
?>
                              <a href="javascript:void(0)" class="label label-info" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $_GET[lot]; ?>','<?php echo $lot[role_wo_id]; ?>','<?php echo $lot[role_dtl_wo_id]; ?>','<?php echo $lot[master_type_process_id]; ?>',2,'<?php echo $_GET[type]; ?>','<?php echo $lot[process_type]; ?>')">Done</a>
<?php 
                          } else if ($lot['process_type'] == '1'){
?>
                              <a href="javascript:void(0)" class="label label-warning" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $_GET[lot]; ?>','<?php echo $lot[role_wo_id]; ?>','<?php echo $lot[role_dtl_wo_id]; ?>','<?php echo $lot[master_type_process_id]; ?>',2,'<?php echo $_GET[type]; ?>','<?php echo $lot[process_type]; ?>')">IN</a>
<?php 
                          } else if ($lot['process_type'] == '2' || $lot['process_type'] == '3' ){
?>
                              <a href="javascript:void(0)" class="label label-success" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $_GET[lot]; ?>','<?php echo $lot[role_wo_id]; ?>','<?php echo $lot[role_dtl_wo_id]; ?>','<?php echo $lot[master_type_process_id]; ?>',2,'<?php echo $_GET[type]; ?>','<?php echo $lot[process_type]; ?>')">Onprogress</a>
<?php 
                          } else {
                            echo "";
                          } 
                      } else {
                          echo "";
                      }
                }
?>
                  
                </td>
            </tr>
<?php 
       $no++;
    }
  }
} else if ($_GET['type'] == 'S'){
      foreach($con->select("laundry_lot_number","lot_no as lot_number,lot_qty as qty","lot_no = '".$_GET['lot']."'") as $lot){}
       // echo "select b.role_wo_name,a.lot_no as lot_number,lot_qty as qty,b.role_wo_id,a.reject_from_lot,b.master_type_process_id from laundry_lot_number a join laundry_role_wo b on a.reject_from_role=b.role_wo_id where a.lot_no = '".$_GET['lot']."'";
?>
    <tr>
        <td>1</td>
        <td>QC Inspc</td>
        <td>0</td>
        <td><?php echo $lot['qty']; ?></td>
        <td>0</td>
        <td>
            <a href="javascript:void(0)" class="label label-info" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $lot[lot_number]; ?>','<?php echo $lot[role_wo_id]; ?>','<?php echo $lot[role_dtl_wo_id]; ?>','<?php echo $lot[master_type_process_id]; ?>',2,'<?php echo $_GET[type]; ?>')">Scrap</a>
        </td>
    </tr>
<?php
} else if ($_GET['type'] == 'W'){
      foreach($con->select("laundry_lot_number","lot_no as lot_number,lot_qty as qty","lot_no = '".$_GET['lot']."'") as $lot){}
       
?>
    <tr>
        <td>1</td>
        <td>QC Inspc</td>
        <td>0</td>
        <td>0</td>
        <td><?php echo $lot['qty']; ?></td>
        <td>
            <a href="javascript:void(0)" class="label label-info" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $lot[reject_from_lot]; ?>','<?php echo $lot[role_wo_id]; ?>','<?php echo $lot[role_dtl_wo_id]; ?>','<?php echo $lot[master_type_process_id]; ?>',2,'<?php echo $_GET[type]; ?>')">Rework</a>
        </td>
    </tr>
<?php
} 
else {
      foreach($con->select("laundry_lot_number a join laundry_role_dtl_wo b on a.role_dtl_reject=b.role_dtl_wo_id join laundry_master_process c on b.master_process_id=c.master_process_id","c.master_process_name,b.master_process_id,a.lot_no as lot_number,lot_qty as qty,b.role_wo_id,a.reject_from_lot","a.lot_no = '".$_GET['lot']."'") as $lot){}
?>
    <tr>
        <td>1</td>
        <td><?php echo $lot['master_process_name']; ?></td>
        <td>0</td>
        <td><?php echo $lot['qty']; ?></td>
        <td>0</td>
        <td>
            <a href="javascript:void(0)" class="label label-info" data-toggle="modal" data-target="#funModalrec" id="mod" onclick="modeldtlview('<?php echo $lot[reject_from_lot]; ?>','<?php echo $lot[role_wo_id]; ?>','<?php echo $lot[role_dtl_wo_id]; ?>','<?php echo $lot[master_process_id]; ?>',3,'<?php echo $_GET[type]; ?>')">Reject</a>
        </td>
    </tr>
<?php
}

?>
