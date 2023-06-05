<?php	
	$date = date("Y-m-d H:i:s");
	$date2 = date("Y-m-d_G-i-s");
	$tabel = "m_role";
if($_POST['jenis']=='edit'){
				
				$data = array( 
								 'name' => $_POST['rolename']
							); 
				$exec= $con->update($tabel, $data,"role_id = '$_POST[kode]'");

				//$where = array("role_id" => $_POST['kode']);
				//echo $_POST[kode];
				//$con->delete("role_menus",$where);
				foreach ($_POST['hak'] as $key => $value){
					
					$idrole = idurut("role_menus","rolemenu_id");
					$data2 = array( 
								 'rolemenu_id' => $idrole,
								 'role_id' => $_POST['kode'],
								 'menu_id' => $value
							); 
					var_dump($data2);
					//$exec2= $con->insert("role_menus", $data2);
				}
				//die;	
		if($exec2){
			echo "<script>alert('Successfully')</script>";  
			echo "<script>window.location='content.php?p=$_POST[getp]'</script>";  
		} else {
			echo "<script>alert('Not Saved')</script>"; 
			echo "<script>window.location='content.php?p=$_POST[getp]'</script>";  
		}
} else {
			
				// $idrole = $con->idurut($tabel,"id_role");
				// $data = array( 
				// 				 /*'nama_berita' => $_POST[nama],*/
				// 				 'id_role' => $idrole,
				// 				 'nama_role' => $_POST['rolename'],
				// 				 'dateupdate_role' => $date
				// 			); 
				// $exec= $con->insert($tabel, $data);
				// foreach ($_POST['hak'] as $key => $value){
				// 	$data2 = array( 
				// 				 /*'nama_berita' => $_POST[nama],*/
				// 				 'id_role' => $idrole,
				// 				 'id_menu' => $value
				// 			); 
				
				
				// 	$exec2= $con->insert("mtrole_dtl", $data2);
				// }
			
		if($exec){
			echo "<script>alert('Successfully')</script>";  
			echo "<script>window.location='content.php?p=$_POST[getp]'</script>";  
		} else {
			echo "<script>alert('Not Saved')</script>"; 
			echo "<script>window.location='content.php?p=$_POST[getp]'</script>";  
		}
}

