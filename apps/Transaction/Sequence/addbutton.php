<?php 

  if($_GET['typesourceadd'] == '') {
	//jika editing
	  if ($_GET['idm']){
	    $model = "onclick='modeladdwo($_GET[idm],$_GET[d],1)'";
	    //tombol simpan jika dari approve berbeda dengan dari edit
	    if($_GET['l'] == "a"){
	    	$funcsimpan = "simpanapprove()";
	    } else {
	    	$funcsimpan = "simpan()";
	    }

	//jika create new
	  } else {
	    $model = "onclick='model(0,1)'";
	  }
	} else {
		 $model = "onclick='model(0,1,".$_GET['typesourceadd'].")'";
	}

?>
<a href='javascript:void(0)' data-toggle='modal' data-target='#funModal' id='mod' <?php echo $model; ?> class='label label-warning' style='font-size:12px;background-color:#0000FF;' >Add</a> &nbsp;