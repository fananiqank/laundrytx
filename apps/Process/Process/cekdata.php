<?php 
/*
	pengecekan apakah lot sudah ada pada process (jika sudah ada artinya sudah pernah dilakukan process)
 	jika belum ada artinya process tersebut belum ada process sama sekali, maka di ambilkan process terakhir
 	dari Lot Parent.
*/

$ceklotdiprocess = $con->selectcount("laundry_process","lot_no","lot_no = '".$_GET['lot']."'");
if ($ceklotdiprocess > 0) {
		$parlot = $_GET['lot'];
} else {
		//pengecekan Data sudah ada atau tidak di Keranjang
		foreach ($con->select("laundry_lot_number A LEFT JOIN laundry_lot_number_dtl b ON A.lot_id = b.lot_id",
								"A.lot_no,
								 A.lot_status,
								 A.lot_parent,
								 b.lot_id_parent,
								 A.lot_id",
								"A.lot_no = '".$_GET['lot']."'","a.lot_id DESC","1") as $lotdata){}
			// echo "select A.lot_no,
			// 		     A.lot_status,
			// 			 A.lot_parent,
			// 			 b.lot_id_parent 
			// 	  from laundry_lot_number A LEFT JOIN laundry_lot_number_dtl b ON A.lot_id = b.lot_id where A.lot_no = '$_GET[lot]'";

		//jika lot memiliki parent maka yang akan dipanggil adalah lot parentnya untuk trace process terakhir yang sudah dilakukan. (split Lot)
		if ($lotdata['lot_id_parent'] != '') {
			foreach ($con->select("laundry_lot_number a left join laundry_lot_number_dtl b on a.lot_id=b.lot_id_parent","lot_no,parent_first","a.lot_id = '".$lotdata['lot_id_parent']."'") as $lotparent) {}
			
			$parlot = $lotparent['lot_no'];
			
			//parameter jika memiliki parent Lot
			$cekpar = 1;
			$parentfirst = $lotparent['parent_first'];
		} 
		//(combine lot)
		else if ($lotdata['lot_parent'] == 1){
			foreach ($con->select("laundry_lot_number_dtl a join laundry_lot_number b on a.lot_id=b.lot_id","a.lot_id,a.lot_id_parent,b.lot_no,parent_first","lot_id_parent = '".$lotdata['lot_id']."'") as $lotparent) {}
			
			$parlot = $lotparent['lot_no'];
			
			//parameter jika memiliki parent Lot
			$cekpar = 1;
			$parentfirst = $lotparent['parent_first'];
		}
		//jika tidak menggunakan no. lot tersebut.
		else {
			$parlot = $lotdata['lot_no'];
			
			//parameter jika tidak memiliki parent Lot
			$cekpar = 0;

			$parent_first = 0;
		}
}
?>