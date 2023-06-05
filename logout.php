<?php
session_start();
include 'funlibs.php';
$con = new Database();
$host="../../laundry/index.php";

// $dta=array(
// 	"LOGGED" => 0
// );
// $exec=$con->update("mauser_login",$dta,"id='".$_SESSION['ID_LOGIN']."'");
session_destroy();

echo "<script>location.href='$host';</script>";
