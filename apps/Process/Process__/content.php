<section class="panel">
  <?php
  	session_start();
    //perpindahan menu
    $first = substr($_GET[p], 0,1);
    $last = substr($_GET[p], 1,3);
    
    $selectuser = $con->select("m_users a join laundry_user_section b on a.user_id=b.user_id","a.user_id,username","a.user_id = '".$_SESSION['ID_LOGIN']."'");

	   foreach ($selectuser as $user) {}    
  ?>

  <div class="panel-body" id="tampilcontent">
        <?php include "content_isi.php"; ?>
  </div>

</section>

    
