
<?php

header("Content-type: application/octet-stream");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-type: application/vnd.ms-excel");
        ob_start();
        $page = base64_decode($_GET['pg']);
		if($_GET['pg']=="lapproc"){
		header("Content-Disposition: attachment; filename=Report_Process.xls");
        include('/apps/Report/Process/lap.php');
		}
		if($page=="lapta"){
		header("Content-Disposition: attachment; filename=Tasks_Report.xls");
        include('../apps/Report/tasks/lap.php');
		}
		if($page=="lapreq"){
		header("Content-Disposition: attachment; filename=Request_order_Report.xls");
        include('../apps/report/request/lap.php');
		}
		

    ?>
