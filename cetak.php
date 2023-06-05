<?php
$namakecil='Eratex Djaja Tbk';
$namabesar='ERATEX DJAJA TBK';
        ob_start();
		if($_GET['page']=="request"){
        include('apps/report/request_order.php');
		}
        if($_GET['page']=="wo"){
        include('apps/report/work_order.php');
        }
        if($_GET['page']=="tasks"){
        include('apps/report/tasks.php');
        }
        if($_GET['page']=="lreq"){
        include('apps/report/request/lrequest.php');
        }
        if($_GET['page']=="lwo"){
        include('apps/report/wo/lwork_order.php');
        }
        if($_GET['page']=="ltas"){
        include('apps/report/tasks/ltasks.php');
        }
	
		// if($_GET['page']=="korpi" and $_GET['jenis']==1){
  //       include('assets/inventory/koresipiutang/report.php');
		// }
		// if($_GET['page']=="korpi" and $_GET['jenis']==2){
  //       include('assets/inventory/koresipiutang/report2.php');
		// }
		// if($_GET['page']=="korpi" and $_GET['jenis']==3){
  //       include('assets/inventory/koresipiutang/report3.php');
		// }

		$content = ob_get_clean();
        require_once('assets/vendor/html2pdf/html2pdf.class.php');
        try
        {
            //$html2pdf = new HTML2PDF('P', 'A4', 'en');
			$html2pdf = new HTML2PDF($p, 'A4', 'en', true,'UTF-8', array('7','7','7','15'));
            $html2pdf->setTestTdInOnePage(false);
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('report.pdf');
        }
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    ?>
