<?php 
require( 'funlibs.php' );
$con=new Database;
session_start();
?>
<style>
table {
		  border-collapse: collapse;
	  }
.table, .td, .th {
				  border: 1px solid #DDD ;
				  padding:1px;

			  }
.th2{	
		border-top: 1px solid #ddd;	
	}
.td2 {
			   
               border: 0px solid #666; font-size:12px; 
               vertical-align:middle; padding:0px;line-height:15px;
           }
ol {
  display: block;
  list-style-type: decimal;
  margin-top: 1em;
  margin-bottom: 1em;
  margin-left: 0;
  margin-right: 0;
  padding-left: 15px;
}
ol.d {list-style-type: lower-alpha;
}
.titik {
padding-left : 15px;
}

.ol {
  counter-reset: item;
  margin-left: 0;
  padding-left: 0;
}
.kurung {
  display: block;
  margin-bottom: .5em;
  margin-left: 2em;
}
.kurung::before {
  display: inline-block;
  content: counter(item) ") ";
  counter-increment: item;
  width: 2em;
  margin-left: -2em;
}
.pasal{
  text-align: center;
}
 
p {
  margin-top: 5%;
  margin-bottom: 5%;
  font-size: 12px;
}
</style>
 <?php
					$selmodagent = $con->select("trdata a left join trrequest b on a.id_data=b.id_data
  left join mtdept g on a.departemen_data=g.id_dept
  left join trwo c on b.id_req =c.id_req
  left join mtkategori d on c.id_kategori = d.id_kategori
  left join mtagent e on c.id_agent = e.id_agent
  left join mtpegawai f on e.id_pegawai = f.id_pegawai",
  "a.*,b.id_req,b.note_user,c.dateupdate_wo,g.nama_dept,d.nama_kategori,f.nama_pegawai",
  "b.id_req = '$_GET[id]'");
          foreach ($selmodagent as $mg) {}
            if($mg['status_data'] == '1'){
              $isijam = "Open";
            } else if ($mg['status_data'] == '2') {
              $isijam = "On Progress";
            } else if ($mg['status_data'] == '3') {
              $isijam = "Hold";
            } else if ($mg['status_data'] == '4') {
              $isijam = "Closed";
            } else if ($mg['status_data'] == '0') {
              $isijam = "Reject";
            } else if ($mg['status_data'] == '5') {
              $isijam = "Hold Request";
            } else if ($mg['status_data'] == '6') {
              $isijam = "Assigned";
            } 
  $selapp = $con->select("trapprove a join mtpegawai b on a.id_pegawai=b.id_pegawai","b.nama_pegawai","a.id_req = $_GET[id]");
  foreach ($selapp as $app) {}
?>
<table cellpadding="0" cellspacing="0" style="width:98%;">
    <tr>
        <td width="40%" style="14%"><img src="assets/images/logo-eratex-djaja.png" style="height:height:50px; width:100px" >
        <p><b>&nbsp;Eratex Djaja Tbk</b></p>
        <span style="font-size:9px;margin-left: 1%;">
       
        Spazio Building 3rd Floor Unit.319-321 <br>
        Graha Festival Kav.3 – Graha Family <br>
        Jl. Mayjend Yono Soewoyo - 
        Surabaya 60226 – Indonesia <br>
        Phone: +62-31-99001101 <br>
        Fax: +62-31-99001115</span>
        </td>
      <td style="width:35%;font-size:20px;" align="center" class="td2">&nbsp;</td>
        <td align="right"><h2>Request Order</h2></td>
  </tr>
    <tr>
      <td colspan="3" ><hr></td>
  </tr> 
</table>
<table cellpadding="0" cellspacing="0" style="width:78%;">
	<!--<tr>
    	<td style="width:30%;font-size:12px; padding-left:5px; padding-top:5px; padding-bottom:5px; background-color:#039; color:white;" align="justify">VENDOR/SUPPLIER</td>
    	<td style="width:10%;font-size:10px; padding-left:10px; padding-top:10px;" align="justify" >&nbsp;</td>
        <td style="width:10%;font-size:12px; padding-left:5px; padding-top:5px; padding-bottom:5px; background-color:#039; color:white;" align="justify">SHIP TO</td>
        
    </tr>-->
    <tr>
   	  <td ><table width="70%" border="0">
   	    <tr>
   	      <td align="justify" style="width:24%;font-size:10px; "> No. Ticket</td>
   	      <td align="justify" style="width:5%;font-size:5px; ">:</td>
   	      <td align="justify" style="width:40%;font-size:10px; "><?=$mg['no_ticket']?></td>
        </tr>
   	    <tr>
   	      <td style="width:20%;font-size:10px; " align="justify" >Created Date</td>
   	      <td style="width:2%;font-size:5px;" align="justify" >:</td>
   	      <td style="width:40%;font-size:10px; " align="justify" ><?=$mg['created_date_data']?></td>
        </tr>
   	    <tr>
   	      <td style="width:20%;font-size:10px; " align="justify" >Approved by</td>
   	      <td style="width:2%;font-size:5px;" align="justify" >:</td>
   	      <td style="width:40%;font-size:10px; " align="justify" ><?=$app['nama_pegawai']?></td>
        </tr>
   	    <tr>
   	      <td style="width:20%;font-size:10px; " align="justify" > Assigned to</td>
   	      <td style="width:2%;font-size:5px;" align="justify" >:</td>
   	      <td style="width:40%;font-size:10px; " align="justify" ><?=$mg['nama_pegawai']?></td>
        </tr>
 	    </table>   	   
        </td>
      <td style="width:5%;font-size:10px; padding-left:10px;" align="justify" class="td2">&nbsp;</td>
        <td>
          <table width="70%" border="0">
          <tr>
            <td align="justify" style="width:24%;font-size:10px; "> Status</td>
            <td align="justify" style="width:5%;font-size:5px; ">:</td>
            <td align="justify" style="width:40%;font-size:10px; "><?=$isijam?></td>
          </tr>
          <tr>
            <td style="width:20%;font-size:10px;" align="justify" >Print Date</td>
            <td style="width:2%;font-size:5px;" align="justify" >:</td>
            <td style="width:40%;font-size:10px; " align="justify" ><?=date('d-m-Y')?></td>
          </tr>
          <tr>
            <td style="width:20%;font-size:10px; " align="justify" >&nbsp;</td>
            <td style="width:2%;font-size:5px;" align="justify" ></td>
            <td style="width:40%;font-size:10px; " align="justify" ></td>
          </tr>
          <tr>
            <td style="width:20%;font-size:10px; " align="justify" >&nbsp;</td>
            <td style="width:2%;font-size:5px;" align="justify" ></td>
            <td style="width:40%;font-size:10px; " align="justify" ></td>
          </tr>
        
          
        </table></td>
    </tr>
</table>
<br />

<table border="1" cellpadding="0" cellspacing="0" style="width: 98%">  
  <tr>
  	<td style="width: 88%;background-color: #55595E;font-size: 10px;color: #ffffff;height: 10px;" align="center"><b>Problem</b></td>
  </tr>
  <tr>
  	<td><p style="letter-spacing: 1px;text-indent: 15px;text-align: justify;"><?php echo $mg['remark_data']; ?></p></td>
  </tr>
</table>
<br />
<br><br>
<!-- <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td  class="">
                                <table width="0%" border="1">
                                    <tr>
                                        <td align="center" style=" font-size:11px;text-align:center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diketahui (OM)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td style="font-size:12px;" align="justify">tgl : </td>
                                    </tr>
                                    <tr>
                                        <td style="width:0%; height:6%; font-size:12px;" align="justify">&nbsp;</td>
                                    </tr>
                                </table> 
                                
                          </td>
                          <td  class="">
                                
                          </td>
                          <td  class="">
                                <table width="0%" border="1">
                                    <tr>
                                        <td align="center" style="font-size:11px;text-align:center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diterima&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td style="font-size:12px;" align="justify">tgl : </td>
                                    </tr>
                                    <tr>
                                        <td style="width:0%; height:6%; font-size:12px;" align="justify">&nbsp;</td>
                                    </tr>
                                </table>  
                                
                          </td>
                        </tr>
                        <tr>
                          <td width="6%" class="td2">&nbsp;</td>
                          <td width="15%" class="td2">&nbsp;</td>
                          <td width="79%" align="right" class="td2">&nbsp;</td>
                        </tr>
</table>
<br>                 
<p>
<font size="-1" class="td2">
<?php 
$datenow = date('d-m-Y H:i:s');
echo "<i><b>Dicetak tanggal</b> : $datenow </i>"; 
    $seluser = $con->select("r_user_login a JOIN m_pegawai b ON a.ID_PEGAWAI = b.id_pegawai","b.nama_pegawai","a.ID = '$valsupp1[id_user]'"); 
    foreach ($seluser as $sus){}
echo "<i><b>Dibuat oleh</b> : $sus[nama_pegawai]</i>";
    $seluser1 = $con->select("r_user_login a JOIN m_pegawai b ON a.ID_PEGAWAI = b.id_pegawai","b.nama_pegawai","a.ID = '$_SESSION[ID_LOGIN]'"); 
    foreach ($seluser1 as $sus1){}
echo "<i>  <b>Dicetak oleh</b> : $sus1[nama_pegawai]</i>";
?>
</font>
</p> -->
