<?php 
session_start();
include "../../../funlibs.php";
$con = new Database();
?>
<table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax" style="font-size: 13px;">
     <thead>
       <tr>
         <th width="5%">No</th>
         <th width="15%">No CMT</th>
         <th width="15%">Colors</th>
         <th width="5%">Inseams</th>
         <th width="5%">Sizes</th>
         <th width="5%">Qty Send</th>
         <th width="5%">Qty Receive</th>
         <th width="5%">Qty Good</th>
         <th width="5%">Act</th>  
       </tr>
     </thead>
     <tbody id="tampildata">
       <?php //include "sourcedatasewing.php"; 
         // select a.wip_dtl_id,a.wo_no,a.garment_colors,a.garment_inseams,a.garment_sizes,b.bpo,
         //   CONCAT(a.quantity,'_',a.wip_dtl_id) as qty,
         //   CONCAT(a.wo_no,'_',a.garment_colors,'_',a.garment_inseams,'_',a.garment_sizes) as bpo
         //   from wip_dtl a join 
         //   (select string_agg(buyer_po_number, ';') as bpo,wo_no,garment_colors,garment_inseams,garment_sizes 
         //   from wo_sb GROUP BY wo_sb.wo_no,wo_sb.garment_colors,wo_sb.garment_inseams,wo_sb.garment_sizes) as b on a.wo_no=b.wo_no and a.garment_colors=b.garment_colors and a.garment_inseams=b.garment_inseams
         //   and a.garment_sizes=b.garment_sizes
         //   where a.wip_dtl_status = 0

    //    select a.wip_dtl_id,a.wo_no,a.garment_colors,a.garment_inseams,a.garment_sizes,string_agg(b.buyer_po_number, ';'),
    // CONCAT(a.quantity,'_',a.wip_dtl_id) as qty,
    // CONCAT(a.wo_no,'_',a.garment_colors,'_',a.garment_inseams,'_',a.garment_sizes) as bpo
    // from wip_dtl a join wo_sb b on a.wo_no=b.wo_no and a.garment_colors=b.garment_colors and a.garment_inseams=b.garment_inseams
    // and a.garment_sizes=b.garment_sizes 
    // where wip_dtl_status =  
    // GROUP BY a.wip_dtl_id
       ?>
       
     </tbody>
 </table>