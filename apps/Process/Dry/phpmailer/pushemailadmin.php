<?php	
date_default_timezone_set('Asia/Jakarta');
//include "../../funlibs.php";
require "PHPMailerAutoload.php";

session_start();
 
 //echo "jem";
 //die;
 		//$jmldetail=$con->select("pj_penjualan_dtl a join m_barang b on a.id_barang=b.id_barang join m_satuan c on b.id_satuan=c.id_satuan","a.id_dtl_jual,a.id_barang,b.kode_barang,b.nama_barang,c.nama_satuan,a.harga_jual,a.dtl_total,a.qty_jual","a.id_pj='$_GET[id]'");				
					
$con=new Database();

	// $tabel = "trdata";
	// $fild  = "*"; 
	// $where = "email_data ='$smail[email_data]'";
	// $data=$con->select($tabel,$fild,$where);
	// //var_dump($data);
	// //die;
	// foreach($data as $val){}
	// //echo $val['EMAIL'];
	// //die;

 
       $jmldetail=$con->select("trdata a left join mtdept b on a.departemen_data=b.id_dept
        	left join trwo c on a.id_data = c.id_data 
        	left join mtagent d on c.id_agent = d.id_agent 
        	left join mtpegawai e on d.id_pegawai = e.id_pegawai",
        	"a.*,b.nama_dept,e.nama_pegawai,c.note_agent,c.priority_wo",
        	"a.id_data='$_POST[iddata]'");   
        $no=1;
        foreach($jmldetail as $d){}                     
                        if ($d['status_data'] == '1'){
                          $statuse = "OPEN";
                        } else if ($d['status_data'] == '2'){
                          $statuse = "ON PROGRESS";
                        } else if ($d['status_data'] == '3'){
                          $statuse = "HOLD";
                        } else if ($d['status_data'] == '4'){
                          $statuse = "CLOSED";
                        } else if ($d['status_data'] == '5'){
                          $statuse = "HOLD REQUEST";
                        } else if ($d['status_data'] == '6'){
                          $statuse = "ASSIGNED";
                        } else if ($d['status_data'] == '0'){
                          $statuse = "REJECT";
                        }

                        if ($d['priority_wo'] == '1'){
                          $prio = "Low";
                        } else if ($d['priority_wo'] == '2'){
                          $prio = "Middle";
                        } else if ($d['priority_wo'] == '3'){
                          $prio = "High";
                        } 
     // echo "select a.*,b.nama_dept from trdata a left join mtdept b on a.departemen_data=b.id_dept where a.no_ticket='$idgen'";            
   
        $asign=$con->select("mtagent d left join mtpegawai e on d.id_pegawai = e.id_pegawai",
        	"d.id_agent,e.email,e.nama_pegawai",
        	"d.id_agent='$_POST[assign]'");
        	// echo "select d.id_agent,e.email,e.nama_pegawai from mtagent d left join mtpegawai e on d.id_pegawai = e.id_pegawai where d.id_agent='$_POST[assign]'";
        	// die;   
        foreach($asign as $is){} 
		        
		if ($is['email'] != ''){
			$nopj = $idgen;
				//Create a new PHPMailer instance
				$mail = new PHPMailer;
				//Tell PHPMailer to use SMTP
				$mail->isSMTP();
				//Enable SMTP debugging
				// 0 = off (for production use)
				// 1 = client messages
				// 2 = client and server messages
				$mail->SMTPDebug = 0;
				//Ask for HTML-friendly debug output
				$mail->Debugoutput = 'html';
				//Set the hostname of the mail server
				//$mail->Host = "ssl://smtp.eratex.co.id";
				$mail->Host = "192.168.31.7";
				//Set the SMTP port number - likely to be 25, 465 or 587
				//$mail->Port = 465;
				//Whether to use SMTP authentication
				$mail->SMTPAuth = true;
				//$mail->SMTPSecure = 'tls'; 
				//Username to use for SMTP authentication
				$mail->Username = "sby-helpdesk@eratex.co.id";
				//Password to use for SMTP authentication
				$mail->Password = "Veritasertx";
				//Set who the message is to be sent from
				$mail->setFrom('sby-helpdesk@eratex.co.id', 'IT Ticketing Eratex Djaja ,Tbk');
				//Set an alternative reply-to address
				$mail->addReplyTo('sby-helpdesk@eratex.co.id', 'IT Ticketing Eratex Djaja ,Tbk');
				//Set who the message is to be sent to
				$mail->addAddress($is['email'] ,$is['nama_pegawai']);
				//Set the subject line
				$mail->Subject = 'Ticket No.'.$d['no_ticket'].' : '.$prio;

				//$isian = include 'detail_email.php';
					$text="Ticket $d[no_ticket] has been Assigned to you and Priority $prio, here's the details : <br>
							<br>
								<table width='50%' cellpadding='0' cellspacing='0' border='0'>                   
								  <tr style='padding:5%;'>
								    <td align='left' width='20%'><strong>Ticket No</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'><strong>$nopj</strong></td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Created Date</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$d[created_date_data]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Created By</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$d[nama_data]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Dept</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$d[nama_dept]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Problem</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$d[remark_data]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Status</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'><strong>$statuse</strong></td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Assigned to</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$d[nama_pegawai]</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Priority</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$prio</td>
								  </tr>
								  <tr>
								    <td align='left'><strong>Note Approval</strong></td>
								    <td align='left' width='2%'>:</td>
								    <td align='left'>$d[note_agent]</td>
								  </tr>
								</table>

							 <br>
							<br>
							View Ticket Click here
							<br>
							<br>
							<br>
							regards, <br>
							IT Ticketing Admin <br>
					   
						   ";	

				$mail->msgHTML("$text");
				//Replace the plain text body with one created manually
				$mail->AltBody = 'This is a plain-text message body';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');
			//send the message, check for errors
			
			if (!$mail->send()) {
				$row_set[] = array("status"=>$mail->ErrorInfo);
				//echo json_encode($row_set);
				//echo $_GET['callback']."(".json_encode($row_set).")";
			
			} else {
				//echo "b";
				$row_set[] = array("status"=>"Email Terkirim");
				//echo json_encode($row_set);
				//echo $_GET['callback']."(".json_encode($row_set).")";
			
			}

		}
		else if ($is['email'] == ''){ 
			$row_set[] = array("status"=>"Email Tidak Ditemukan");
			//echo json_encode($row_set);
			//echo $_GET['callback']."(".json_encode($row_set).")";

		}
