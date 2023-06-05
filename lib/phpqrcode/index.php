<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 250px;
            text-align: center;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .container {
            padding: 2px 16px;
            text-align: left;
        }

        .BUTTON_PAF {
            background: #3D94F6;
            background-image: -webkit-linear-gradient(top, #3D94F6, #1E62D0);
            background-image: -moz-linear-gradient(top, #3D94F6, #1E62D0);
            background-image: -ms-linear-gradient(top, #3D94F6, #1E62D0);
            background-image: -o-linear-gradient(top, #3D94F6, #1E62D0);
            background-image: linear-gradient(to bottom, #3D94F6, #1E62D0);
            -webkit-border-radius: 20px;
            -moz-border-radius: 20px;
            border-radius: 20px;
            color: #FFFFFF;
            font-family: Open Sans;
            font-size: 12px;
            font-weight: 600;
            padding: 8px;
            text-shadow: -30px -35px 18px #000000;
            border: solid #337FED 1px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            text-align: center;
        }

        .BUTTON_PAF:hover {
            border: solid #337FED 1px;
            background: #2C1ED0;
            background-image: -webkit-linear-gradient(top, #2C1ED0, #3D94F6);
            background-image: -moz-linear-gradient(top, #2C1ED0, #3D94F6);
            background-image: -ms-linear-gradient(top, #2C1ED0, #3D94F6);
            background-image: -o-linear-gradient(top, #2C1ED0, #3D94F6);
            background-image: linear-gradient(to bottom, #2C1ED0, #3D94F6);
            -webkit-border-radius: 20px;
            -moz-border-radius: 20px;
            border-radius: 20px;
            text-decoration: none;
        }

        .css-input {
            border-color: #cccccc;
            font-size: 14px;
            padding: 8px;
            border-width: 4px;
            border-radius: 15px;
            border-style: solid;
            box-shadow: 0px 0px 2px 0px rgba(42, 42, 42, .75);
            font-style: none;
            font-weight: normal;
            font-family: fantasy;
        }

        .css-input:focus {
            outline: none;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            @page {
                size: 5mm 2mm;
                size: landscape;
            }
        }
    </style>
</head>
<?php
require('../../funlibs.php');
$con = new Database;
session_start();
//echo "<h1>PHP QR Code</h1><hr/>";

//set it to writable location, a place for temp generated PNG files
$PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;

//html PNG location prefix
$PNG_WEB_DIR = 'temp/';

include "qrlib.php";

//ofcourse we need rights to create temp dir
if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);


$filename = $PNG_TEMP_DIR . 'test.png';

//processing form input
//remember to sanitize user input in real-life solution !!!
$errorCorrectionLevel = 'L';
if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
    $errorCorrectionLevel = $_REQUEST['level'];

$matrixPointSize = 4;
if (isset($_REQUEST['size']))
    $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


if (isset($_REQUEST['data'])) {

    //it's very important!
    if (trim($_REQUEST['data']) == '')
        die('data cannot be empty! <a href="?">back</a>');

    // user data
    $filename = $PNG_TEMP_DIR . 'test' . md5($_REQUEST['data'] . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
    QRcode::png($_REQUEST['data'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);
} else {

    //default data
    //echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';    
    QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
}

if ($_GET['no'] != '') {
    $datano = $_GET['no'];
    $button = '<input type="submit" class="BUTTON_PAF" value="GENERATE">';
    $image = "";
    $hidden = "";
} else {
    $datano = (isset($_REQUEST['data']) ? htmlspecialchars($_REQUEST['data']) : 'PHP QR Code :)');
    //$button = '<input class="BUTTON_PAF" onclick="window.print()" value="Print">';
    $button = '<a href="../fpdf/membed.php" class="BUTTON_PAF">Print</a>';

    //select data
    foreach ($con->select("(select lot_no as lotno, lot_qty as qty, lot_status as status, lot_id as id,a.wo_master_dtl_proc_id,c.wo_no,c.garment_colors,shift_id,TO_CHAR( C.ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date,C.buyer_id from laundry_lot_number a JOIN laundry_wo_master_dtl_proc c ON a.wo_master_dtl_proc_id=c.wo_master_dtl_proc_id where lot_no = '$datano' 
            UNION select rec_no as lotno, rec_qty as qty, rec_status as status, rec_id as id,d.wo_master_dtl_proc_id,f.wo_no,f.garment_colors,shift_id,TO_CHAR( f.ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date,f.buyer_id from laundry_receive d JOIN laundry_wo_master_dtl_proc f ON d.wo_master_dtl_proc_id=f.wo_master_dtl_proc_id where rec_no = '$datano') as a", "lotno,qty,status,id,wo_master_dtl_proc_id,wo_no,garment_colors,shift_id,ex_fty_date,buyer_id") as $cmt) {
    }

    $expdatano = explode("-", $datano);
    $typelot = substr($expdatano[0], -4, 1);
    if ($typelot == 'Q') {
        $title = '<b>QA Sample</b>';
        $ref = '';
    } else if ($typelot == 'R') {
        $title = '<b>Reject Lot</b>';
        $ref = '';
    } else if ($typelot == 'N') {
        $title = '<b>Normal Production</b>';
        $ref = '';
    } else if ($typelot == 'C') {
        $title = '<b>Combine Lot</b>';
        $ref = '<tr>
                      <td width="30%">Ref. Lot No</td>
                      <td width="70%">: ' . $datano . '</td>
                  </tr>';
    } else if ($typelot == 'B') {
        $title = '<b>Split Production</b>';
        $ref = '<tr>
                      <td width="30%">Ref. Lot No</td>
                      <td width="70%">: ' . $datano . '</td>
                  </tr>';
    } else if ($typelot == 'P') {
        $title = '<b>Pre Bulk Lot</b>';
        $ref = '';
    } else if ($typelot == 'F') {
        $title = '<b>First Bulk Lot</b>';
        $ref = '';
    } else if ($typelot == 'W') {
        $title = '<b>Rework QA Lot</b>';
        $ref = '';
    } else if ($typelot == 'M') {
        $title = '<b>Rework Lot</b>';
        $ref = '';
    } else {
        $title = '<b>Receive Lot</b>';
        $ref = '';
    }

    $image = '  <body>
                        <div class="no-print">' . $button . '</div>
                        <div class="card">
                          <div class="container"> 
                            <p>
                              <table style="font-size:9px;" width="100%">
                               
                                <tr>
                                    <td width="30%" align="center"><img src="' . $PNG_WEB_DIR . basename($filename) . '" width="75px" height="75px"/>
                                    </td>
                                    <td>
                                        <table style="font-size:9px;" width="100%">
                                            <tr>
                                              <td colspan="2">' . $title . '</td>
                                            </tr> 
                                            <tr>
                                              <td colspan="2" style="font-size:12px;"><b>' . $datano . '</b></td>
                                            </tr>
                                            <tr>
                                              <td><b>' . $cmt[wo_no] . '</b></td>
                                              <td align="center"><b>' . $cmt[qty] . '</b></td>
                                            </tr>
                                            <tr>
                                              <td><b>' . $cmt[garment_colors] . '</b></td>
                                              <td align="center"><b>' . $cmt[shift_id] . '</b></td>
                                            </tr>
                                            <tr>
                                              <td><b>' . $cmt[ex_fty_date] . ' / ' . $cmt[buyer_id] . '</b></td>
                                              <td></td>
                                            </tr>
                                            
                                        </table>
                                    </td>
                                </tr>
                                
                               
                          </div>
                        </div>
                        
                    </body> ';
    $hidden = 'style="display:none"';
}


//echo "select * from laundry_receive where rec_no ='$datano'";

//config form
echo '<form action="index.php" method="post" ' . $hidden . '>
        <b>Lot No</b> :&nbsp;<input name="data" class="css-input" value="' . $datano . '" readonly/>
        <input type="hidden" name="level" id="level" value="H">
        <input type="hidden" name="size" id="size" value="4">
        ' . $button . '</form><hr/>';

// benchmark
//QRtools::timeBenchmark();    

//display generated file
echo $image;
