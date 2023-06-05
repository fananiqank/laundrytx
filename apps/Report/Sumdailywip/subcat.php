<?php 
include "../../../funlibs.php";
$con = new Database;
?>
	<option value="">All</option>
<?php 
	$selcatsub = $con->select("mtkategori_sub","*","status_kategori_sub = '1' and id_kategori = $_GET[id]");
	foreach ($selcatsub as $subcat) { 
	  if($_GET['s'] == $subcat['id_kategori_sub']){
	  	$s="selected";
	  } else{
	  	$s="";
	  }
?>
	<option value="<?=$subcat[id_kategori_sub]?>" <?=$s?>><?=$subcat['nama_kategori_sub']?></option>
<?php } ?>