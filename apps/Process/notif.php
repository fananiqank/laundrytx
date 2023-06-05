<?php
require_once "../../funlibs.php";
$con = new Database;
if(isset($_POST['view'])){


$selectnotif = $con->select("laundry_process_machine","COUNT(process_machine_id) as jumlahdata","machine_id =  and process_machine_notif = 1");

foreach ($selectnotif as $notif) {}
  if ($notif['jumlahdata'] != 0){
  	$('#button-process').load("apps/Process/Dry/button-process.php?lot="+lot+"&machine="+machine);
  } else {
  	$('#button-process').load("apps/Process/Dry/button-process.php?lot="+lot+"&machine="+machine);
  }


// $status_query = "SELECT * FROM comments WHERE comment_status=0";
// $result_query = mysqli_query($con, $status_query);
// $count = mysqli_num_rows($result_query);
// $data = array(
//     'notification' => $output,
//     'unseen_notification'  => $count
// );

//echo json_encode($data);

}

?>