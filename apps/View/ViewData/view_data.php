<?php 
if($_GET['halreload'] == 1){
  require( '../../../funlibs.php' );
  $con=new Database;
  session_start();
?>
<script type="text/javascript">
 $(document).ready(function(){
        var spintable = $('#datatable-ajax').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "apps/View/ViewData/data.php?cm="+cmts+"&co="+color+"&xty="+xty+"&saw="+saw,
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
          var index = iDisplayIndex + 1;
          $('td:eq(0)', nRow).html(index);
          return nRow;
        },
        "lengthMenu": [
          [10, 25, 50, 100, -1],
          [10, 25, 50, 100, "All"]
        ],
        "order": [ 0, 'desc' ]
      });
   
  });
</script>
<?php
}
?>
<div class="row" align="center">
    <h4><b>View Data</b></h4>
    
</div>
<table class="table datatable-basic table-bordered table-striped table-hover dataTable no-footer" width="100%" id="datatable-ajax" style="font-size: 13px;">
  <thead>
    <tr>
      <th width="5%">No</th>
      <th width="13%">WO No</th>
      <th width="13%">Color QR</th>
      <th width="13%">Color Wash</th>
      <th width="13%">Ex Fty Date</th>
      <th width="5%">Status</th>
      <th width="5%">Qty Receive</th>
      <th width="5%">Qty Cutting</th>
      <th width="5%">Detail</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
