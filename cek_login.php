<?php
//error_reporting(0);
session_start ();
date_default_timezone_set ( 'Asia/Jakarta' );

require_once 'funlibs.php';
$con = new Database();
require_once "funlibsmysql.php";
$con2=new DatabaseM();
//$check = $_POST['check'];
// echo "as";
$username = addslashes(trim($_POST ['username']));
$pass = md5($_POST ['pwd']);
//$pref =$con->select("user_tbl", " ID = '1'");
if($username=='admin'){
	$tabel = "m_users a join user_role b on a.user_id = b.user_id LEFT JOIN m_department C on A.dept_id =C.dept_id";
	$fild  = "a.*,b.role_id,c.name as dep"; //menampilkan semua fild
	$where = "username='$username' AND password='$pass' AND b.role_id >= '2000'";
	$dtk=$con->select($tabel,$fild,$where);
	//echo "select $fild from $tabel where $where"."a";
}else{
	$tabel = "mos_users a 
	          JOIN mos_graccess_usergroup b on a.id = b.userid 
			  JOIN mos_graccess c on b.groupid=c.id";
	$fild  = "a.*,b.groupid,c.name as group_name"; //menampilkan semua fild
	$where = "username='$username' AND password='$pass'";
	$dtk=$con2->select($tabel,$fild,$where);
	//echo "select $fild from $tabel where $where";
}

	if(count($dtk)>=1 and $check==null)
	{
		// foreach($pref as $val){
		// $_SESSION['ID_PREF'] = $val['id_pref'];
		// $_SESSION['NOPREF'] = $val['nopref'];
		// $_SESSION['NAMA_PERUSAHAAN'] = $val['nama_perusahaan'];
		// $_SESSION['LOGO'] = $val['logo'];
		// $_SESSION['ALAMAT'] = $val['alamat'];
		// $_SESSION['NO_TELP'] = $val['no_telp'];
		// }
		
		foreach($dtk as $value){
			$_SESSION ['ID_LOGIN'] = $value['id'];
			$_SESSION ['ID_DEPT'] = $value['group_name'];
			$_SESSION ['ID_ROLE'] = $value['groupid'];
			$_SESSION ['NAMA_PEG'] = $value['name'];
			$_SESSION ['IPAD'] =  getenv('REMOTE_ADDR');
			$_SESSION ['USER_NAME'] =  $value['username'];
			// $_SESSION ['ID_PEG'] = $value['ID_PEGAWAI'];	
			// $_SESSION ['ID_CABANG'] = $value['id_cabang'];
			// $_SESSION ['ID_JABATAN'] = $value['id_jabatan'];
			//$_SESSION ['ID_DIV'] = $value['id_divisi'];
        	$user_ip = getenv('REMOTE_ADDR');
			$name = gethostbyaddr($user_ip);
			$logurut = $con->idurut("user_logs","log_id");
			
			//input log di laundry
			$datas = array(  
			'log_id' => $logurut,
		   	'user_id' => $value['id'],
		   	'ip_address' => $user_ip,
		   	'actions' =>	"LOGIN",
		   	'remarks' => "LAUNDRY",
		   	'created_date' => date("Y-m-d H:i:s"),
		  	);
		  	// var_dump($datas);
		  	// die();
			$exec=$con->insert("user_logs",$datas);

			//input log di mambo
			$datamambo = array(  
		   	'user_id' => $value['id'],
		   	'ip_address' => $user_ip,
		   	'actions' =>	"LOGIN",
		   	'created_date' => date("Y-m-d H:i:s"),
		  	);
		  	// var_dump($datas);
		  	// die();
			$execmambo=$con2->insert("m_user_logs",$datamambo);
				//die;
			if ($_POST['emm'] == 1) {
				echo "<script>location.href='content.php?p=$_POST[halaman]&d=$_POST[dataid]';</script>";
			} else {
        		echo "<script>location.href='$hs';</script>";	
        	}
	   }	
	} 
	else if(count($dtk)>=1 and $check==1){
		foreach($dtk as $value){
			$_SESSION ['ID_LOGIN'] = $value['id'];
			$_SESSION ['ID_DEPT'] = $value['group_name'];
			$_SESSION ['ID_ROLE'] = $value['groupid'];
		//	$_SESSION ['EMAIL'] = $value['auth_email'];
			$_SESSION ['NAMA_PEG'] = $value['name'];
			$_SESSION ['IPAD'] =  getenv('REMOTE_ADDR');
			$_SESSION ['USER_NAME'] =  $value['username'];
			
        	$user_ip = getenv('REMOTE_ADDR');
			$name = gethostbyaddr($user_ip);
			$logurut = $con->idurut("user_logs","log_id");

			//input log di laundry
			$datas = array(  
			'log_id' => $logurut,
		   	'user_id' => $value['id'],
		   	'ip_address' => $user_ip,
		   	'actions' =>	"LOGIN",
		   	'remarks' => "LAUNDRY",
		   	'created_date' => date("Y-m-d H:i:s"),
		  	);
	
			$exec=$con->insert("user_logs",$datas);

			//input log di mambo
			$datamambo = array(  
		   	'user_id' => $value['id'],
		   	'ip_address' => $user_ip,
		   	'actions' =>	"LOGIN",
		   	'created_date' => date("Y-m-d H:i:s"),
		  	);
		  	
			$execmambo=$con2->insert("m_user_logs",$datamambo);

			$exec=$con->insert("user_logs",$datas);
			if ($_POST['emm'] == 1) {
				echo "<script>location.href='content.php?p=$_POST[halaman]&d=$_POST[dataid]';</script>";
			} else {
				echo "<script>location.href='$hsi';</script>";
			}
        	

	   }	
	} 
	else {
		//die;
			//echo $_SESSION[ID_ROLE];		
			echo "<script>location.href='index.php';</script>"; 
			
	}
	
?>
