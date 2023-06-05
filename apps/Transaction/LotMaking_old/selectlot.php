<?php 
session_start();
include '../../../funlibs.php';
$con = new Database;

if ($_GET['id']){
	foreach ($con->select("laundry_wo_master_dtl_proc","wo_no,garment_colors,buyer_style_no,repeat_order","wo_master_dtl_proc_id = '$_GET[id]'") as $selcekwo) {}
    $ceklotQA = $con->selectcount("laundry_lot_number","lot_id","lot_type = 'Q' and lot_status = 1 and 
        wo_no = '$selcekwo[wo_no]' AND garment_colors = '$selcekwo[garment_colors]'");
    $ceklotpre = $con->selectcount("laundry_lot_number","lot_id","lot_type = 'P' and lot_status = 1 and 
        wo_no = '$selcekwo[wo_no]' AND garment_colors = '$selcekwo[garment_colors]'");
    
    $ceklotfirst = $con->selectcount("laundry_lot_number","lot_id","lot_type = 'F' and lot_status = 1 and
        wo_no = '$selcekwo[wo_no]' AND garment_colors = '$selcekwo[garment_colors]'");
    $cekfbapp = $con->selectcount("laundry_approve_bulk","approve_bulk_id","wo_no = '$selcekwo[wo_no]' and garment_colors = '$selcekwo[garment_colors]' and buyer_style_no = '$selcekwo[buyer_style_no]'");

    // Pre bulk By pass =======
    $cekprebypass = $con->selectcount("laundry_approve_bulk_bypass","approve_bulk_id","wo_no = '$selcekwo[wo_no]' and garment_colors = '$selcekwo[garment_colors]' and buyer_style_no = '$selcekwo[buyer_style_no]' and lot_type = 'P'");
    //=========================


 //echo $ceklotQA.'_'.$ceklotpre.'_'.$ceklotfirst;
    // $tabelwomas1 = "crosstab( $$
    //                         select wo_no::TEXT,lot_type,approve_bulk_status::int
    //                         from laundry_approve_bulk
    //                         where wo_no = '$selcekwo[wo_no]' 
    //                         AND garment_colors = '$selcekwo[garment_colors]'
    //                         order by wo_no, lot_type
    //                     $$,
    //                     $$ select master_type_lot_initial from laundry_master_type_lot where master_type_lot_id between 1 and 3 
    //                     ORDER BY master_type_lot_id $$
    //                     ) as ct (wo_no text,q int,p int,f int)";
    $tabelwomas1 = "crosstab( $$
                            select buyer_style_no::TEXT,lot_type,approve_bulk_status::int
                            from laundry_approve_bulk
                            where buyer_style_no = '$selcekwo[buyer_style_no]' 
                            AND garment_colors = '$selcekwo[garment_colors]'
                            order by buyer_style_no, lot_type
                        $$,
                        $$ select master_type_lot_initial from laundry_master_type_lot where master_type_lot_id between 1 and 3 
                        ORDER BY master_type_lot_id $$
                        ) as ct (buyer_style_no text,q int,p int,f int)";
    $fieldwomas1 = "*";
    //$wherewomas1 = "c.wo_master_dtl_proc_id = '$_GET[id]'";
    
    $selwomas = $con->select($tabelwomas1,$fieldwomas1);
   // echo "select $fieldwomas1 from $tabelwomas1";
    foreach ($selwomas as $wm) {}
    
    //if($ceklotQA == 0) {                                //apakah ada QA sample yang tidak berjalan di process
    //    if($ceklotpre == 0){                            // apakah ada prebulk yang tidak berjalan di process
            if ($wm['p'] == 1){            // apakah prebulk sudah di approve
                //echo "A";
                //if ($ceklotfirst == 0){                 // apakah ada firstbulk yang tidak berjalan di process
                    if ($wm['f'] == 1 && $cekfbapp > 0){
                        $where = "and master_type_lot_id < 5 and master_type_lot_id not between 2 and 3";
                    } else {
                        $where = "and master_type_lot_id < 4 and master_type_lot_id != 2";
                    }
                // } else {
                //     $where = "and master_type_lot_id = 1";
                // }
            } else if ($cekprebypass == 1){            // khusus untuk bypass ke First bulk tanpa pre bulk selama implenetasi
                //echo "B";
                //if ($ceklotfirst == 0){                 // apakah ada firstbulk yang tidak berjalan di process
                    if ($wm['f'] == 1 && $cekfbapp > 0){
                        $where = "and master_type_lot_id < 5 and master_type_lot_id not between 2 and 3";
                    } else {
                        $where = "and master_type_lot_id < 4 and master_type_lot_id != 2";
                    }
                // } else {
                //     $where = "and master_type_lot_id = 1";
                // }
            } else if ($selcekwo[repeat_order] == 1) {
                //echo "C";
                //if ($ceklotfirst == 0){                 // apakah ada firstbulk yang tidak berjalan di process
                    if ($wm['f'] == 1 && $cekfbapp > 0){
                        $where = "and master_type_lot_id < 5 and master_type_lot_id not between 2 and 3";
                    } else {
                        $where = "and master_type_lot_id < 4 and master_type_lot_id != 2";
                    }
                // } else {
                //     $where = "and master_type_lot_id = 1";
                // }
            } else {
                //echo "D";
                $where = "and master_type_lot_id between 1 and 2";
            }
    //    } else {
    //        $where = "and master_type_lot_id = 1";
    //    }
    // } else {                                             // jika ada QA sample yang sedang berjalan di process
    //     if($ceklotpre == 0){
    //         if ($wm['p'] == 1){
    //             if ($ceklotfirst == 0){
    //                 if ($wm['f'] == 1 && $cekfbapp > 0){
    //                     $where = "and master_type_lot_id = 4";
    //                 } else {
    //                     $where = "and master_type_lot_id = 3";
    //                 }
    //             } else {
    //                 $where = "and master_type_lot_id = '0'";
    //             }
    //         } else if ($cekprebypass == 1){                  // khusus untuk bypass ke First bulk tanpa pre bulk selama implenetasi
    //             if ($ceklotfirst == 0){
    //                 if ($wm['f'] == 1 && $cekfbapp > 0){
    //                     $where = "and master_type_lot_id = 4";
    //                 } else {
    //                     $where = "and master_type_lot_id = 3";
    //                 }
    //             } else {
    //                 $where = "and master_type_lot_id = '0'";
    //             }
    //         } else if ($selcekwo[repeat_order] == 1) {
    //             if ($ceklotfirst == 0){                 // apakah ada firstbulk yang tidak berjalan di process
    //                 if ($wm['f'] == 1 && $cekfbapp > 0){
    //                     $where = "and master_type_lot_id < 5 and master_type_lot_id not between 2 and 3";
    //                 } else {
    //                     $where = "and master_type_lot_id < 4 and master_type_lot_id != 2";
    //                 }
    //             } else {
    //                 $where = "and master_type_lot_id = 1";
    //             }
    //         } else {
    //             $where = "and master_type_lot_id = 2";
    //         }
    //     } else {
    //         $where = "and master_type_lot_id = '0'";
    //     }
    // }

    $seltype = $con->select("laundry_master_type_lot","*","master_type_lot_status = 1 $where");
    //echo "select * from laundry_master_type_lot where master_type_lot_status = 1 $where";
?>

<select data-plugin-selectTwo class="form-control populate"  placeholder="Choose One" name="type" id="type" onchange="lotnumber(this.value)">
	<option value="">Choose</option>
	<?php
    	foreach ($seltype as $tp) {
	?>
  		<option value="<?php echo $tp[master_type_lot_id].'_'.$tp[master_type_lot_initial]?>"><?php echo $tp['master_type_lot_name']?></option>
	<?php } ?>
</select>

<?php } ?>