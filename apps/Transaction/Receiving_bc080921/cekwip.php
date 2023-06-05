<?php 

session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();
 $colors = $con->searchseq($_GET['co']);
 $cmt = $con->searchseqcmt($_GET['cm']);  
//wipdengan dengan status 9 dan wono dan color 
 $selcekwip = $con->select("wip_dtl","COUNT(wip_dtl_id) as jumlahid","wip_dtl_status = 9");
 foreach ($selcekwip as $cekwip) {}
 echo "<input type='hidden' id='jwip' name='jwip' value='$cekwip[jumlahid]'>";
//wip dengan status 9
 $selwip9 = $con->select("wip_dtl","COUNT(wip_dtl_id) as jumid","wip_dtl_status = 9");
 foreach ($selwip9 as $wip9) {}
 echo "<input type='hidden' id='wip9' name='wip9' value='$wip9[jumid]'>";

?>