<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.card {
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
  width: 35%;
  text-align: center;
}

.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
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

.css-input { border-color:#cccccc; font-size:14px; padding:8px; border-width:4px; border-radius:15px; border-style:solid; box-shadow: 0px 0px 2px 0px rgba(42,42,42,.75); font-style:none; font-weight:normal; font-family:fantasy;  } 
         .css-input:focus { outline:none; } 
</style>
</head>
<?php    
require( '../../funlibs.php' );
$con=new Database;
session_start();
    //echo "<h1>PHP QR Code</h1><hr/>";
    
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 4;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


    if (isset($_REQUEST['data'])) { 
    
        //it's very important!
        if (trim($_REQUEST['data']) == '')
            die('data cannot be empty! <a href="?">back</a>');
            
        // user data
        $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($_REQUEST['data'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    } else {    
    
        //default data
        //echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';    
        QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    }    
    
    if($_GET['no'] != '' ){
        $datano = $_GET['no'];
        $button = '<input type="submit" class="BUTTON_PAF" value="GENERATE">';
        $image = "";
        $hidden = "";
    } else {
        $datano = (isset($_REQUEST['data'])?htmlspecialchars($_REQUEST['data']):'PHP QR Code :)');
        $button = '<input class="BUTTON_PAF" onclick="window.print()" value="Print">';

        //select data
        foreach($con->select("(select lot_no as lotno, lot_qty as qty, lot_status as status, lot_id as id,wo_master_dtl_proc_id,wo_no,garment_colors,shift_name from laundry_lot_number a join laundry_master_shift b on a.shift_id=b.shift_id where lot_no = '$datano' UNION select rec_no as lotno, rec_qty as qty, rec_status as status, rec_id as id,wo_master_dtl_proc_id,wo_no,garment_colors,shift_name from laundry_receive c join laundry_master_shift d on c.shift_id=d.shift_id where rec_no = '$datano') as a","lotno,qty,status,id,wo_master_dtl_proc_id,wo_no,garment_colors,shift_name") as $cmt){}

        $expdatano = explode("-",$datano);
        $typelot = substr($expdatano[0], -4,1);
        if($typelot == 'Q'){
            $title = '<div style="background-color:#00FFFF;color:#000000"><b>QA Sample</b></div>';
            $ref = '';
        } else if($typelot == 'R'){
            $title = '<div style="background-color:#FF0000;color:#000000"><b>Reject Lot</b></div>';
            $ref = '';
        } else if($typelot == 'N'){
            $title = '<div style="background-color:#228B22;color:#ffffff"><b>Normal Production</b></div>';
            $ref = '';
        } else if($typelot == 'C'){
            $title = '<div style="background-color:#4B0082;color:#ffffff"><b>Combine Lot</b></div>';
            $ref ='<tr>
                      <td width="30%">Ref. Lot No</td>
                      <td width="70%">: '.$datano.'</td>
                  </tr>';
        } else if($typelot == 'B'){
            $title = '<div style="background-color:#0000CD;color:#ffffff"><b>Split Production</b></div>';
             $ref ='<tr>
                      <td width="30%">Ref. Lot No</td>
                      <td width="70%">: '.$datano.'</td>
                  </tr>';
        } else if($typelot == 'P'){
            $title = '<div style="background-color:#708090;color:#ffffff"><b>Pre Bulk Lot</b></div>';
            $ref = '';
        } else if($typelot == 'F'){
            $title = '<div style="background-color:#FF8C00;color:#ffffff"><b>First Bulk Lot</b></div>';
            $ref = '';
        } else {
            $title = '<div style="background-color:#FFFF00;color:#000000"><b>Receive Lot</b></div>';
            $ref = '';
        } 

        $image = '  <body>
                        <div class="card">'.
                          $title
                           .'<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>
                          <div class="container"> 
                            <p><table style="font-size:11px;" width="100%">
                                <tr>
                                    <td width="30%">Lot No</td>
                                    <td width="70%">: '.$datano.'</td>
                                </tr>'.
                                $ref
                                .'<tr>
                                    <td>CMT No</td>
                                    <td>: '.$cmt['wo_no'].'</td>
                                </tr>
                                <tr>
                                    <td>Colors</td>
                                    <td>: '.$cmt['garment_colors'].'</td>
                                </tr>
                                <tr>
                                    <td>Qty</td>
                                    <td>: '.$cmt['qty'].' Pcs </td>
                                </tr>
                                <tr>
                                    <td>Shift</td>
                                    <td>: '.$cmt['shift_name'].' </td>
                                </tr>
                                <tr>
                                    <td colspan="2">Sequence Process</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table width="100%" style="font-size:11px;">';
                                   		$selwomaster=$con->select("laundry_wo_master_dtl_proc a join laundry_role_wo b on a.role_wo_master_id = b.role_wo_master_id","b.role_wo_name,b.master_type_process_id,b.role_wo_id","a.wo_master_dtl_proc_id = '$cmt[wo_master_dtl_proc_id]'","b.role_wo_seq");
                                   		foreach ($selwomaster as $womas) {
                                        $cekprocess = $con->selectcount("laundry_process","process_id","master_type_process_id='$womas[master_type_process_id]' and role_wo_id='$womas[role_wo_id]'");
                                        // echo "select process_id from laundry_process where master_type_process_id='$womas[master_type_process_id]' and role_wo_id='$womas[role_wo_id]'";
                                          //if ($cekprocess == 0){
                                              $image.='<tr>
                                                <td width="50%">'.$womas['role_wo_name'].'</td>
                                                <td width="30%"><input type="checkbox"></td>
                                                <td width="20%"><input type="text" style="width:100%"></td>
                                              </tr>';
                                          //}
                                        }
                                        $image.='
                                        </table>
                                    </td>
                                </tr>
                            </table></p> 
                          </div>
                        </div>
                        <div>'.
                        $button
                        .'</div>
                    </body> ';
        $hidden = 'style="display:none"';
    } 

    
     //echo "select * from laundry_receive where rec_no ='$datano'";

    //config form
    echo '<form action="index.php" method="post" '.$hidden.'>
        <b>Lot No</b> :&nbsp;<input name="data" class="css-input" value="'.$datano.'" readonly/>
        <input type="hidden" name="level" id="level" value="H">
        <input type="hidden" name="size" id="size" value="4">
        '.$button.'</form><hr/>';
        
    // benchmark
    //QRtools::timeBenchmark();    

    //display generated file
    echo $image;

    