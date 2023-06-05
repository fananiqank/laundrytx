<?php
echo "test";
require 'vendor/autoload.php';
 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
 
$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 
if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
 
    $arr_file = explode('.', $_FILES['file']['name']);
    $extension = end($arr_file);
 
    if('csv' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        //echo "disini<br>";
    }
 
    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
    
    //$sheetData = $spreadsheet->getActiveSheet()->toArray();
    //$dtsheet = $spreadsheet->getSheetCount();
    $dtrcount = $spreadsheet->getSheetCount();
    for($i=0;$i<$dtrcount;$i++){
    	//$spreadsheet->getSheet($i);

	    $dtsheet = $spreadsheet->getSheet($i)->toArray();//$spreadsheet->getActiveSheet()->toArray();
	    //var_dump($dtsheet);
	    $datanya[]=$dtsheet;
	    header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: GET");
		header("Access-Control-Max-Age: 3600");
		header("ccess-Control-Allow-Headers: origin, content-type, accept");
		
	    
	    //echo "<br><br>";
	    //echo json_encode($dtsheet);	
    }
    echo json_encode($datanya);
}
?>