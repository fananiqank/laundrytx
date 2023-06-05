<?php
//error_reporting(0);

if (!empty($_GET['option'])) {
	if ($_GET['option'] == 'phpqrcode') {
		$folder= "lib/phpqrcode/";
		if (!empty($_GET['task'])){
			$EV = $_GET['task'];
			$page = 'printqrcode';
			if (!empty($_GET['act'])){
				$act = $_GET['act'];
			} else {
				$page = "Home";
			}	
		} else {
			$page="Home";
		}
	} else {
		$folder= "apps/".$_GET['option']."/";
		if (!empty($_GET['task'])){
			$exptask = explode("_",$_GET['task']);
			if ($exptask[0] == 'simpan'){
				$EV = $_GET['task'];
				$page = "simpan";
				if (!empty($_GET['act'])){
					$act = $_GET['act'];
				} else {
					$page = "Home";
				}
			} else {
				$EV = $_GET['task'];
				$page = $_GET['task'];
				if (!empty($_GET['act'])){
					$act = $_GET['act'];
				} else {
					$page = "Home";
				}
			}	
		} else {
			$page="Home";
		}
	}
	
} else {
	$page="Home";
}

$menu=$con2->select("m_pgev","PG,EV,HANDLER_PAGE,ERROR_PAGE","PG = '".$act."' and EV = UPPER('".$EV."')");
	foreach($menu as $menuval){
			switch($page)
			{		
				case "Home":
					$modul="apps/dashboard/dashboard.php";
					$title="";
					$data="";
					$cdpk="nav-expanded";
				break;
				case "simpan":
					$modul="$folder/index.php";
					$content="$menuval[HANDLER_PAGE]/simpan.php";
					$title="$menuval[ERROR_PAGE]";
					$data="$menuval[HANDLER_PAGE]/js.php";
					$cdpk="nav-expanded";
				break;
				case $page:
					$modul="$folder/index.php";
					$content="$menuval[HANDLER_PAGE]/content.php";
					$title="$menuval[ERROR_PAGE]";
					$data="$menuval[HANDLER_PAGE]/js.php";
					$cdpk="nav-expanded";
				break;
				case "modal":
					$modul="$folder/index.php";
					$content="$menuval[HANDLER_PAGE]/modagent.php";
					$title="$menuval[ERROR_PAGE]";
					$data="$menuval[HANDLER_PAGE]/js.php";
					$cdpk="nav-expanded";
				break;
				case "printqrcode":
					$modul="$folder/index.php";
					$content="$menuval[HANDLER_PAGE]/index.php";
					$title="$menuval[ERROR_PAGE]";
					$data="$menuval[HANDLER_PAGE]/js.php";
					$cdpk="nav-expanded";
				break;
			}

}

// die();
$haktemp="";
	//$hak_a=$con->select("role_menus","*","rolemenu_id='$_SESSION[ID_ROLE]'");
	$hak_a=$con->select("m_role a JOIN role_menus b on a.role_id=b.role_id","menu_id","a.role_id = '".$_SESSION['ID_ROLE']."'");
	
	foreach($hak_a as $hak_akses){
		$haktemp=$haktemp."".$hak_akses['menu_id'].",";
	}
	$akses_menu=rtrim($haktemp,',');
	$array_akses_menu=explode(',',$akses_menu);
?>
