<style type="text/css">
table.scroll {width:100%;border:1px #a9c6c9 solid;font:13px verdana,arial,sans-serif;color:#333333;}
table.scroll thead {display:table;width:100%;}
table.scroll tbody {display:block;height:250px;overflow-y:scroll;width:100%;}
table.scroll tbody tr {display:table;width:100%;}
/*table.scroll th, td {width:33%;padding:8px;}
table.scroll th {background-color:#000099;color:#ffffff;}
table.scroll tr:hover td {background:#a0a0a0;color:#ffffff;}
table.scroll tr:nth-child(odd) {background-color:#c0c0c0;}
table.scroll tr:nth-child(even) {background-color:#f0f0f0;}*/
</style>
<table class="table table-bordered table-striped table-hover" width="100%" id="datatable-ajax3" style="font-size: 13px;">
		<tr>
	    	<th width="20%" align="left">GdpBatch </th>
	    	<td> : <?php echo $_GET['gdp']; ?></td>
	    	<td width="30%" align="center"><b>Qty Batch</b></td>
	   	</tr>
	   	<tr>
	    	<th width="20%" align="left">Cut Qty </th>
	    	<td> : <?php echo $recscan['cutting_qty']; ?></td>
	    	<td rowspan="4" align="center" style="font-size:72px;"><b><?php echo $totalbatch['totalbatch']; ?></b></td>
	   	</tr>
	   	<tr>
	    	<th width="20%" align="left">Receive Qty </th>
	    	<td> : <?php echo $recqty['recqty']; ?></td>
	   	</tr>
	   	<tr>
	    	<th width="20%" align="left">Balance Receive to Cut </th>
	    	<td> : <?php echo $blncrectocut; ?></td>
	   	</tr>
	   	<tr>
	    	<th width="20%" align="left">Balance Now</th>
	    	<td> : <b><?php echo $blncnow; ?></b></td>
	   	</tr>
</table>