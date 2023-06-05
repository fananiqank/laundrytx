<?php
error_reporting(0);
$host="$_SERVER[HTTP_HOST]";
$hs="content.php";
$head1="<font size='+1'>Eratex Djaja Tbk</font>";
 /*
 * File Name: class.crud.php
 * Date: August 17, 2015
 * Author: Alfian Syahroni
 * email : lowshint@gmail.com
 * referensi:
 * Facebook : https://www.facebook.com/sourcecodeonline
 * http://php.net/manual/en/class.pdo.php
 * http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers#Why_use_PDO.3F
 * 
 */ 
class DatabaseM extends PDO{
    private $engine;  
    private $host; 
    private $database;
    private $user; 
    private $pass;
    private $result; 	
    public function __construct()
	{ 
        $this->engine	= 'mysql'; 
        $this->host	  	= '192.168.51.24'; 
		$this->database = 'mambo';  
		$this->user 	= 'root'; 
        $this->pass 	= 'youC1000';
        
		$dns = $this->engine.':dbname='.$this->database.";host=".$this->host; 
        parent::__construct( $dns, $this->user, $this->pass ); 
    }
	/*
    * Insert values into the table
    */
	public function beginTransaction(){
		parent::beginTransaction();
	}
	
	public function commit(){
		parent::commit();
	}
	
	public function rollback(){
		parent::rollBack();
	}	
    
	public function callp($proc,$t=null){
		if($t==''){
		$a="";	
		}else{
		$a="($t)";
		}

		echo "call $proc$a";

		$stmt = parent::prepare("call $proc$a");
		$value = '1';
		$stmt->bindParam(1, $value, parent::PARAM_STR|parent::PARAM_INPUT_OUTPUT, 4000); 
		try{
		$stmt->execute();
		// var_dump($stmt);
		// return "".$value;

		var_dump($stmt->fetch(PDO::FETCH_ASSOC));
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			 $posts[] = $row;
			 // var_dump($row);
			 // echo "asd";
		}
		return $posts;
		} catch(Exception $e){
			return $e;
			}
		
		
	}

	public function callme($proc,$t=null){
		if($t==''){
		$a="";	
		}else{
		$a="($t)";
		}

		//echo "call $proc$a";

		$stmt = parent::prepare("call $proc($t)");
		//$value = '1';
		//$stmt->bindParam(1, $value, parent::PARAM_STR|parent::PARAM_INPUT_OUTPUT, 4000); 
		try{
		$stmt->execute();
		$stmt->closeCursor();

		$r = parent::query('select @tot')->fetch();

		
		return $r;
		} catch(Exception $e){
			return $e;
			}
		
		
	}
	
	public function callp1($proc,$t=null,$p=null,$i=null){
		if($t==''){
		$a="";	
		}else{
		$a="('$t','$p','$i')";
		}
		$stmt = parent::prepare("call $proc $a");
		$value = '';
		$stmt->bindParam(1, $value, parent::PARAM_STR|parent::PARAM_INPUT_OUTPUT, 4000); 
		//echo "call $proc $a";
		try{
		$stmt->execute();
		return "".$value;
		} catch(Exception $e){
			return $e;
			}
	}

	public function callp1t($proc,$t=null,$p=null){
		if($t==''){
		$a="";	
		}else{
		$a="('$t','$p')";
		}
		$stmt = parent::prepare("call $proc $a");
		$value = '';
		$stmt->bindParam(1, $value, parent::PARAM_STR|parent::PARAM_INPUT_OUTPUT, 4000); 
		echo "call $proc $a";
		try{
		$stmt->execute();
		return "".$value;
		} catch(Exception $e){
			return $e;
			}
	}

	public function callp2($proc,$t=null){
		if($t==''){
		$a="";	
		}else{
		$a="('$t')";
		}
		$stmt = parent::prepare("call $proc $a");
		$value = ''; 
		$stmt->bindParam(1, $value, parent::PARAM_STR|parent::PARAM_INPUT_OUTPUT, 4000); 
		try{
		$stmt->execute();
		return "".$value;
		} catch(Exception $e){
			return $e;
			}
	}

	
	public function insert($table,$rows=null,$err=null) 
	{
		$command = 'INSERT INTO '.$table;
		$row = null; $value=null;
		foreach ($rows as $key => $nilainya)
		{
		  $row	.=",".$key;
		  $value 	.=", :".$key;
		}
		
		$command .="(".substr($row,1).")";
		$command .="VALUES(".substr($value,1).")";
		//echo"$command<br><br>";
	   
		$stmt =  parent::prepare($command);
		$stmt->execute($rows);
		if($err==1){
			$rowcount=$stmt->errorInfo();
		}else{
		$rowcount = $stmt->rowCount(); 
		}//or die(print_r($stmt->errorInfo(), true));
		//$rowcount = parent::lastInsertId();
		return $rowcount;
	}
	
	public function insertNotExist($table,$rows=null,$where=null)
	{
		
		$command = 'INSERT INTO '.$table;
		$row = null; $value=null;
		foreach ($rows as $key => $nilainya)
		{
		  $row	.=",".$key;
		  $value 	.=", :".$key;
		}
		
		$command .="(".substr($row,1).")";
		$command .="VALUES(".substr($value,1).")";
		//echo"$command<br><br>";
	   
		$stmt =  parent::prepare($command);
		$stmt->execute($rows);
		$rowcount = $stmt->rowCount();
		//$rowcount = parent::lastInsertId();
		return $rowcount;
	}
	
	//Insert Data and Return Last Insert ID
	public function insertID($table,$rows=null)
	{
		$command = 'INSERT INTO '.$table;
		$row = null; $value=null;
		foreach ($rows as $key => $nilainya)
		{
		  $row	.=",".$key;
		  $value 	.=", :".$key;
		}
		
		$command .="(".substr($row,1).")";
		$command .="VALUES(".substr($value,1).")";
		 // echo"$command";
	   
		$stmt =  parent::prepare($command);
		$stmt->execute($rows);
		//$rowcount = $stmt->rowCount();
		$cuk=$stmt->errorInfo();
		
		if($cuk[0]=="00000"){
			$rowcount = parent::lastInsertId();
		}else{
			$rowcount ="0";
		}
		
		return $rowcount;
	} 
	public function idurut($table,$field){
		$max=$this->select($table,"max($field)as id");
		foreach($max as $val){}
		$id=$val['id']+1;
		return $id;
	}
	public function nourut($field, $table, $param, $kdunit, $tgl){
		$lenght = strlen($param);
		$mul=8;
		if($lenght==2){
			$mul=$mul-1;	
			$cab=4;	
		//PU/01/201605/0001
		}elseif($lenght==3){
			$mul=$mul;
			$cab=5;	
		//PUO/01/201605/0001
		}
		
		$thn = date("Y",strtotime($tgl));
		$bln = date("m",strtotime($tgl));
		//$query = $this->select($table,"$field AS maxID","SUBSTR($field,1,$lenght)='$param' and SUBSTR($field,$cab,2)='$kdunit' ORDER BY SUBSTR($field,$mul,12) desc limit 1");
		$query = $this->select($table,"$field AS maxID","SUBSTRING_INDEX($field,'/',1)='$param' and LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX($field,'/',2),'/',-1),3,0)='$kdunit' ORDER BY 
SUBSTRING_INDEX($field,'/',-2) desc limit 1");

		foreach($query as $data){}
		$idMaxj = $data['maxID'];
		
		$temp=explode("/",$idMaxj);
		
		$noUrutj = intval($temp[3]);
		$noBlnj =  substr($temp[2], 4, 2); 
		
		if($noBlnj<> $bln)
		{
			$nourutj=1;
		} else {
			$noUrutj++;
		}
		$id=$param."/".$kdunit."/".$thn."".$bln."/".sprintf("%05s", $noUrutj);
		return $id;
	}
	public function nouruttahun($field, $table, $param, $kdunit, $tgl){
		$lenght = strlen($param);
		$mul=8;
		if($lenght==2){
			$mul=$mul-1;	
			$cab=4;	
		//PU/01/201605/0001
		}elseif($lenght==3){
			$mul=$mul;
			$cab=5;	
		//PUO/01/201605/0001
		}
		
		$thn = date("Y",strtotime($tgl));
		$bln = date("m",strtotime($tgl));
		//$query = $this->select($table,"$field AS maxID","SUBSTR($field,1,$lenght)='$param' and SUBSTR($field,$cab,2)='$kdunit' ORDER BY SUBSTR($field,$mul,12) desc limit 1");
		$query = $this->select($table,"$field AS maxID","SUBSTRING_INDEX($field,'/',1)='$param' and LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX($field,'/',2),'/',-1),3,0)='$kdunit' ORDER BY 
SUBSTRING_INDEX($field,'/',-2) desc limit 1");
		foreach($query as $data){}
		$idMaxj = $data['maxID'];
		
		$temp=explode("/",$idMaxj);
		
		$noUrutj = intval($temp[3]);
		$noThnj =  substr($temp[2], 0, 4); 
		
		if($noThnj<> $thn)
		{
			$nourutj=1;
		} else {
			$noUrutj++;
		}
		$id=$param."/".$kdunit."/".$thn."/".sprintf("%05s", $noUrutj);
		return $id;
	} 
	public function norek($field, $table, $param, $cab){
		/*
		select SUBSTRING_INDEX(SUBSTRING_INDEX(RNACCOUNT,'-',-2),'-',1) AS maxID
from mrekeningnasabah where SUBSTRING_INDEX(RNACCOUNT,'-',1)='1' order by SUBSTRING_INDEX(SUBSTRING_INDEX(RNACCOUNT,'-',-2),'-',1) desc limit 0,1;
		*/
		// $query = $this->select($table,"SUBSTRING_INDEX(SUBSTRING_INDEX($field,'-',-2),'-',1) AS maxID","SUBSTRING_INDEX($field,'-',1)='$param' order by SUBSTRING_INDEX(SUBSTRING_INDEX($field,'-',-2),'-',1) desc limit 0,1");
		$query = $this->select($table,"MID($field,5,5) AS maxID","MID($field,4,1)='$param' order by MID($field,5,5) desc limit 0,1");
		foreach($query as $data){}
		//$idMaxj = $data['maxID'];
		//$temp=explode("/",$idMaxj);
		$randlast = rand(0,9);
		$noUrutj = intval($data['maxID']);
		$urut=$noUrutj+1;
		$id=$cab."".$param."".sprintf("%05s", $urut)."$randlast";
		return $id;
	}

	public function getRomawi($tgl){
		$bln = date("m",strtotime($tgl));
                switch ($bln){
                    case 1: 
                        return "I";
                        break;
                    case 2:
                        return "II";
                        break;
                    case 3:
                        return "III";
                        break;
                    case 4:
                        return "IV";
                        break;
                    case 5:
                        return "V";
                        break;
                    case 6:
                        return "VI";
                        break;
                    case 7:
                        return "VII";
                        break;
                    case 8:
                        return "VIII";
                        break;
                    case 9:
                        return "IX";
                        break;
                    case 10:
                        return "X";
                        break;
                    case 11:
                        return "XI";
                        break;
                    case 12:
                        return "XII";
                        break;
                }
		}

	public function noakad($field, $table, $param, $kdunit, $tgl){
		$querylen = $this->select("mfasilitas","FACODE","FAID = '$param' limit 1");
		foreach($querylen as $lent){}
		$param = strlen($lent);
		// $mul=8;
		// if($lenght==2){
		// 	$mul=$mul-1;	
		// 	$cab=4;	
		// //PU/01/201605/0001
		// }elseif($lenght==3){
		// 	$mul=$mul;
		// 	$cab=5;	
		// //PUO/01/201605/0001
		// }elseif($lenght==4){
		// 	$mul=$mul;
		// 	$cab=6;	
		// //PUO/01/201605/0001
		// }
		
		$thn = date("Y",strtotime($tgl));
		//$bln = date("m",strtotime($tgl));
		//$query = $this->select($table,"$field AS maxID","SUBSTR($field,1,$lenght)='$param' and SUBSTR($field,$cab,2)='$kdunit' ORDER BY SUBSTR($field,$mul,12) desc limit 1");
		$query = $this->select($table,"$field AS maxID","SUBSTRING_INDEX($field,'/',1)='$param' and LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX($field,'/',2),'/',-1),3,0)='$kdunit' ORDER BY 
SUBSTRING_INDEX($field,'/',-2) desc limit 1");
		foreach($query as $data){}
		$idMaxj = $data['maxID'];
		
		$temp=explode("/",$idMaxj);
		
		$noUrutj = intval($temp[3]);
		$noBlnj =  substr($temp[2], 4, 2);
		
		if($noBlnj<> $bln)
		{
			$nourutj=1;
		} else {
			$noUrutj++;
		}
		$id=sprintf("%05s", $noUrutj)."/"."BS"."/".$param."/".$kdunit."/".$bln."/".$thn."/";
		return $id;
	}
	
	public function nofasilitas($jenis){ 
		if($jenis==1){}
		if($jenis==2){}
		if($jenis==3){
			$query = $this->select("mpinjaman","MAX(pjid) AS maxID");
			foreach($query as $data){}
			$$noUrutj=ltrim($data[maxID],"4");	
			$id="4".sprintf("%06s", $noUrutj+1);
		}	
		
		return $id;
	}
	/*
    * Delete records from the database.
    */
	public function delete($tabel,$where=null)
	{
		$command = 'DELETE FROM '.$tabel;
		
		$list = Array(); $parameter = null;
		foreach ($where as $key => $value) 
		{
		  $list[] = "$key = :$key";
		  $parameter .= ', ":'.$key.'":"'.$value.'"';
		} 
		$command .= ' WHERE '.implode(' AND ',$list);
	   	// echo"$command";
		$json = "{".substr($parameter,1)."}";
		$param = json_decode($json,true);
				
		$query = parent::prepare($command); 
		$query->execute($param);
		$rowcount = $query->rowCount();
        return $rowcount;
	}
   /*
    * Uddate Record
    */
	public function update($tabel, $fild = null ,$where = null)
	{
		 $update = 'UPDATE '.$tabel.' SET ';
		 $set=null; $value=null;
		 foreach($fild as $key => $values)
		 {
			 $set .= ', '.$key. ' = :'.$key;
			 $value .= ', ":'.$key.'":"'.$values.'"';
		 }
		 $update .= substr(trim($set),1);
		 $json = '{'.substr($value,1).'}';
		 $param = json_decode($json,true);
		 
		 if($where != null)
		 {
		    $update .= ' WHERE '.$where;
		 }
		 // echo"$update<br>";
		 try
			{
			 $query = parent::prepare($update);
			 $query->execute($param);
			 //echo"test<br>";
			}
				catch(Exception $e)
			{
				echo($e->getMessage()); echo"test";
			}
		 $rowcount = $query->rowCount();
		 //echo $rowcount;
         return $rowcount;
    }
   /*
    * Selects information from the database.
    */
	public function select($table, $rows, $where = null, $order = null, $limit= null,$error=null,$show=null)
	{
	    $command = 'SELECT '.$rows.' FROM '.$table;
        if($where != null)
            $command .= ' WHERE '.$where;
        if($order != null)
            $command .= ' ORDER BY '.$order;            
        if($limit != null)
            $command .= ' LIMIT '.$limit;
// echo"$command<br><br>";
        if($show==1){
			echo"$command<br><br>";
		}
		$query = parent::prepare($command);
		$query->execute();
		$query->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$posts = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			 $posts[] = $row;
		}
		//return $this->result = json_encode(array('post'=>$posts));
		//return $query->fetch(PDO::FETCH_ASSOC);
 		
        return $posts;	
 	}
	
	
	public function selectcount($tabel,$rows,$where)
	{	
		$q=$this->select($tabel,$rows,$where);
        return count($q);
		//return $q;
 	}
		
	
	public function newIDKM($jenis)
	{
		 $thn = date("Y");
		 $bln = date("m");
		 $tgl = date("d");
		 		
		 if($jenis=='1'){
			 	//Rawat Jalan
				$tabel = "rj";
				$idfield="no_rj";
				$pref="IRJ.";
				$id="id_rj";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(antrian)='$bln' and year(antrian)='$thn' and $id=(select $id as maxID from $tabel WHERE month(antrian)='$bln' and year(antrian)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 if($jenis=='2'){
			 	//Rawat resep
				$tabel = "resep";
				$idfield="no_resep";
				$pref="IRN.";
				$id="id";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(tgl)='$bln' and year(tgl)='$thn' and $id=(select $id as maxID from $tabel WHERE month(tgl)='$bln' and year(tgl)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 if($jenis=='3'){
			 	//Rawat Inap
				$tabel = "konsultasi";
				$idfield="no_konsul";
				$pref="KSL.";
				$id="id_konsul";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(tgl)='$bln' and year(tgl)='$thn' and $id=(select $id as maxID from $tabel WHERE month(tgl)='$bln' and year(tgl)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 if($jenis=='4'){
			 	//Rawat Inap
				$tabel = "td_bayar";
				$idfield="no_bayar";
				$pref="BYR.";
				$id="id";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(tgl)='$bln' and year(tgl)='$thn' and $id=(select $id as maxID from $tabel WHERE month(tgl)='$bln' and year(tgl)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 	 	
			 
		
		$dt=$this->select($tabel,$rows,$where);
		//echo"<br> check select $rows from $tabel Where $where<br>";
		foreach($dt as $value){
			$idMax=$value['maxID'];
		}
		
		$noUrut = (int) substr($idMax, 12, 13);
		$noBln = (int) substr($idMax, 5, 2);
		if($idMax=='0' or empty($idMax))
		{
			$noUrut=1;
		} else {
		
			$noUrut++;
		}
				
		$newID = "$pref". $thn ."". sprintf("%02s",$bln).sprintf("%02s",$tgl)."". sprintf("%06s", $noUrut);	 
		
		return $newID;	 
    }
	
	public function paramjur($kdparam,$layanan,$cabang){
		
		$table="parameter_jurnal";
		$rows="*";
		$where="kd_param='$kdparam' AND LAYANAN='$layanan' AND cabang='$cabang'";
		
		return $this->select($table,$rows,$where);
		
	}
	
	/*
    * Returns the result set
    */
	public function getResult()
	{
        return $this->result;
    }
	
	
	static function data_output ( $columns, $data, $isJoin = false )
    {
        $out = array();
        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();
            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];
                // Is there a formatter?
                if ( isset( $column['formatter'] ) ) {
                    $row[ $column['dt'] ] = ($isJoin) ? $column['formatter']( $data[$i][ $column['field'] ], $data[$i] ) : $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
                }
                else {
                    $row[ $column['dt'] ] = ($isJoin) ? $data[$i][ $columns[$j]['field'] ] : $data[$i][ $columns[$j]['db'] ];
                }
            }
            $out[] = $row;
        }
        return $out;
    }
    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    static function limit ( $request, $columns )
    {
        $limit = '';
        if ( isset($request['start']) && $request['length'] != -1 ) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }
        return $limit;
    }
    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param bool  $isJoin  Determine the the JOIN/complex query or simple one
     *
     *  @return string SQL order by clause
     */
    static function order ( $request, $columns, $isJoin = false )
    {
        $order = '';
        if ( isset($request['order']) && count($request['order']) ) {
            $orderBy = array();
            $dtColumns = self::pluck( $columns, 'dt' );
            for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];
                if ( $requestColumn['orderable'] == 'true' ) {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';
                    $dtl=explode(" as ",$column['db']);
                    //$orderBy[] = ($isJoin) ? $column['db'].' '.$dir : '`'.$column['db'].'` '.$dir;
                    $orderBy[] = ($isJoin) ? $dtl[0].' '.$dir : '`'.$dtl[0].'` '.$dir;
                }
            }
            $order = 'ORDER BY '.implode(', ', $orderBy);
            //echo "$order";
        }
        return $order;
    }
    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the sql_exec() function
     *  @param  bool  $isJoin  Determine the the JOIN/complex query or simple one
     *
     *  @return string SQL where clause
     */
    static function filter ( $request, $columns, &$bindings, $isJoin = false )
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck( $columns, 'dt' );
        if ( isset($request['search']) && $request['search']['value'] != '' ) {
            $str = $request['search']['value'];
            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];
                if ( $requestColumn['searchable'] == 'true' ) {
                    $binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                    $dtl=explode(" as ",$column['db']);
                    //$globalSearch[] = ($isJoin) ? $column['db']." LIKE ".$binding : "`".$column['db']."` LIKE ".$binding;
                    $globalSearch[] = ($isJoin) ? $dtl[0]." LIKE ".$binding : "`".$dtl[0]."` LIKE ".$binding;
                }
            }
        }
        // Individual column filtering
        for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
            $requestColumn = $request['columns'][$i];
            $columnIdx = array_search( $requestColumn['data'], $dtColumns );
            $column = $columns[ $columnIdx ];
            $str = $requestColumn['search']['value'];
            if ( $requestColumn['searchable'] == 'true' &&
                $str != '' ) {
                $binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
            	$dtl=explode(" as ",$column['db']);
                //$columnSearch[] = ($isJoin) ? $column['db']." LIKE ".$binding : "`".$column['db']."` LIKE ".$binding;
            	$columnSearch[] = ($isJoin) ? $dtl[0]." LIKE ".$binding : "`".$dtl[0]."` LIKE ".$binding;
            }
        }
        // Combine the filters into a single string
        $where = '';
        if ( count( $globalSearch ) ) {
            $where = '('.implode(' OR ', $globalSearch).')';
        }
        if ( count( $columnSearch ) ) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where .' AND '. implode(' AND ', $columnSearch);
        }
        if ( $where !== '' ) {
            $where = 'WHERE '.$where;
        }
        return $where;
    }
    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $sql_details SQL connection details - see sql_connect()
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @param  array $joinQuery Join query String
     *  @param  string $extraWhere Where query String
     *
     *  @return array  Server-side processing response array
     *
     */
    static function simple ( $request, $sql_details, $table, $primaryKey, $columns, $joinQuery = NULL, $extraWhere = '', $groupBy = '')
    {
        $bindings = array();
        $db = self::sql_connect( $sql_details );
        // Build the SQL query string from the request
        $limit = self::limit( $request, $columns );
        $order = self::order( $request, $columns, $joinQuery );
        $where = self::filter( $request, $columns, $bindings, $joinQuery );
		// IF Extra where set then set and prepare query
        if($extraWhere)
            $extraWhere = ($where) ? ' AND '.$extraWhere : ' WHERE '.$extraWhere;
        
        $groupBy = ($groupBy) ? ' GROUP BY '.$groupBy .' ' : '';
        
        // Main query to actually get the data
        if($joinQuery){
            $col = self::pluck($columns, 'db', $joinQuery);
            $query =  "SELECT SQL_CALC_FOUND_ROWS ".implode(", ", $col)."
			 $joinQuery
			 $where
			 $extraWhere
			 $groupBy
			 $order
			 $limit";
        }else{
            $query =  "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
			 FROM `$table`
			 $where
			 $extraWhere
			 $groupBy
			 $order
			 $limit";
        }
        //echo $query;
        $data = self::sql_exec( $db, $bindings,$query);
        // Data set length after filtering
        $resFilterLength = self::sql_exec( $db,
            "SELECT FOUND_ROWS()"
        );
        $recordsFiltered = $resFilterLength[0][0];
        // Total data set length
        $resTotalLength = self::sql_exec( $db,
            "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table`"
        );
        $recordsTotal = $resTotalLength[0][0];
        /*
         * Output
         */
        return array(
            "draw"            => intval( $request['draw'] ),
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => self::data_output( $columns, $data, $joinQuery )
        );
    }
    /**
     * Connect to the database
     *
     * @param  array $sql_details SQL server connection details array, with the
     *   properties:
     *     * host - host name
     *     * db   - database name
     *     * user - user name
     *     * pass - user password
     * @return resource Database connection handle
     */
    static function sql_connect ( $sql_details )
    {
        try {
            $db = @new PDO(
                "mysql:host=192.168.51.24;dbname=mambo",
                'root',
                'youC1000',
                array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION )
            );
            $db->query("SET NAMES 'utf8'");
        }
        catch (PDOException $e) {
            self::fatal(
                "An error occurred while connecting to the database. ".
                "The error reported by the server was: ".$e->getMessage()
            );
        }
        return $db;
    }
    /**
     * Execute an SQL query on the database
     *
     * @param  resource $db  Database handler
     * @param  array    $bindings Array of PDO binding values from bind() to be
     *   used for safely escaping strings. Note that this can be given as the
     *   SQL query string if no bindings are required.
     * @param  string   $sql SQL query to execute.
     * @return array         Result from the query (all rows)
     */
    static function sql_exec ( $db, $bindings, $sql=null )
    {
        // Argument shifting
        if ( $sql === null ) {
            $sql = $bindings;
        }
        $stmt = $db->prepare( $sql );
        //echo $sql;
        // Bind parameters
        if ( is_array( $bindings ) ) {
            for ( $i=0, $ien=count($bindings) ; $i<$ien ; $i++ ) {
                $binding = $bindings[$i];
                $stmt->bindValue( $binding['key'], $binding['val'], $binding['type'] );
            }
        }
        // Execute
        try {
            $stmt->execute();
        }
        catch (PDOException $e) {
            self::fatal( "An SQL error occurred: ".$e->getMessage() );
        }
        // Return all
        return $stmt->fetchAll();
    }
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Internal methods
     */
    /**
     * Throw a fatal error.
     *
     * This writes out an error message in a JSON string which DataTables will
     * see and show to the user in the browser.
     *
     * @param  string $msg Message to send to the client
     */
    static function fatal ( $msg )
    {
        echo json_encode( array(
            "error" => $msg
        ) );
        exit(0);
    }
    /**
     * Create a PDO binding key which can be used for escaping variables safely
     * when executing a query with sql_exec()
     *
     * @param  array &$a    Array of bindings
     * @param  *      $val  Value to bind
     * @param  int    $type PDO field type
     * @return string       Bound key to be used in the SQL where this parameter
     *   would be used.
     */
    static function bind ( &$a, $val, $type )
    {
        $key = ':binding_'.count( $a );
        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );
        return $key;
    }
    /**
     * Pull a particular property from each assoc. array in a numeric array,
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @param  bool  $isJoin  Determine the the JOIN/complex query or simple one
     *  @return array        Array of property values
     */
    static function pluck ( $a, $prop, $isJoin = false )
    {
        $out = array();
        for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
            //$out[] = ($isJoin && isset($a[$i]['as'])) ? $a[$i][$prop]. ' AS '.$a[$i]['as'] : $a[$i][$prop];
              $out[] = ($isJoin && isset($a[$i]['as'])) ? $a[$i][$prop]. ' AS '.$a[$i]['as'] : $a[$i][$prop];
        }
        return $out;
    }
	static function _flatten ( $a, $join = ' AND ' )
	{
		if ( ! $a ) {
			return '';
		}
		else if ( $a && is_array($a) ) {
			return implode( $join, $a );
		}
		return $a;
	}
	public function cek_mutasi($kode_brg,$unit){
			$sql=$this->select("tx_mutasi","IFNULL(akhir,0)as akhir,ifnull(hpp,0)as hpp","id_barang='$kode_brg' AND id_gudang='$unit' ORDER BY id_mutasi DESC LIMIT 0,1");
			foreach($sql as $mutasi){}
			return $mutasi;
			
	}
	public function cek_mutasi_riject($kode_brg,$unit){
			$sql=$this->select("tx_mutasi_reject","IFNULL(akhir,0)as akhir,ifnull(hpp,0)as hpp","id_barang='$kode_brg' AND id_gudang='$unit' ORDER BY id_mutasi DESC LIMIT 0,1");
			foreach($sql as $mutasi){}
			return $mutasi;
			
	}
	public function aktif_price($tgl,$id){
		$acti=$this->select("m_pricelist","id_price","id_supp='$id' and due_date<='$tgl' and status='1' group by due_date order by due_date desc limit 0,1");
		foreach($acti as $actival){}
		return $actival;
	}
	function jumlah_hari($bulan = 0, $tahun = '') { 
			if ($bulan < 1 OR $bulan > 12) { 
			return 0; 
			} 
			if ( ! is_numeric($tahun) OR strlen($tahun) != 4) { 
			$tahun = date('Y'); 
			} 
			if ($bulan == 2) { 
			if ($tahun % 400 == 0 OR ($tahun % 4 == 0 AND $tahun % 100 != 0)) { 
			return 29; 
			} 
			} 
			$jumlah_hari = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
			return $jumlah_hari[$bulan - 1]; 
	} 
	function ses_cab($cab){
		 if($cab==0 || $cab==99){
			$caba=" ";
		 }else{
			$caba=" and id_cabang='$cab'";
		 }
		 return $caba;
			
	}
	function cetak($jab,$cab){
		$cek=$this->select("m_pegawai","nama_pegawai","id_jabatan='$jab' and id_cabang='$cab' and id_aktif='1' order by tgl_mulai");
		foreach($cek as $dtbm){}
		$jum=count($cek);
		
		if($jum>0){
			$nama=$dtbm['nama_pegawai'];
		}else{
			$nama="";
			}
		
				 return $nama;
			
	}
			
	public function masuk_mutasi($id,$kd_brg,$idgud,$qty,$hpp,$user,$jen){
					
					$mutasi2=$this->select("tx_mutasi","*","id_barang='$kd_brg' and id_gudang='$idgud' order by id_mutasi desc limit 0,1");
					foreach($mutasi2 as $mutasi){}
					if($mutasi[id_mutasi]==''){
						$awal=0; 
						$masuk=$qty;
						$keluar=0;
						$akhir=$qty;
					}else{
						$awal=$mutasi[akhir];
						$masuk=$qty;
						$keluar=0;
						$akhir=$awal+$masuk-$keluar;
					}
					$tgl=	date("Y-m-d H:i:s");		
					$data = array( 		
							'no_ref' => $id,
							'id_barang' => $kd_brg,
							'awal' => $awal,
							'masuk' => $masuk,
							'keluar' => $keluar,
							'akhir' => $akhir,
							'hpp' => $hpp,
							'tgl_mutasi' => $tgl,
							'jenis_mutasi' => $jen,
							'id_user' => $user,
							'id_gudang' => $idgud
					);
					$exec= $this->insert("tx_mutasi", $data);
					//$sql="insert into mutasi$periode(no_ref,kd_brg,awal,masuk,keluar,akhir,tgl_mutasi,jenis_mutasi,id_user,id_gudang,hpp)
					//values('$id','$kd_brg','$awal','$masuk','$keluar','$akhir',NOW(),'0','$user','$idgud','$hpp')";
					//mysql_query($sql);
					//return $sql;
		}
		public function masuk_mutasi_r($id,$kd_brg,$idgud,$qty,$hpp,$user,$jen){
					$mutasi2=$this->select("tx_mutasi_reject","*","id_barang='$kd_brg' and id_gudang='$idgud' order by id_mutasi desc limit 0,1");
					foreach($mutasi2 as $mutasi){}
					if($mutasi[id_mutasi]==''){
						$awal=0; 
						$masuk=$qty;
						$keluar=0;
						$akhir=$qty;
					}else{
						$awal=$mutasi[akhir];
						$masuk=$qty;
						$keluar=0;
						$akhir=$awal+$masuk-$keluar;
					}
					$tgl=	date("Y-m-d H:i:s");		
					$data = array( 		
							'no_ref' => $id,
							'id_barang' => $kd_brg,
							'awal' => $awal,
							'masuk' => $masuk,
							'keluar' => $keluar,
							'akhir' => $akhir,
							'hpp' => $hpp,
							'tgl_mutasi' => $tgl,
							'jenis_mutasi' => $jen,
							'id_user' => $user,
							'id_gudang' => $idgud
					);
					$exec= $this->insert("tx_mutasi_reject", $data);
					//$sql="insert into mutasi$periode(no_ref,kd_brg,awal,masuk,keluar,akhir,tgl_mutasi,jenis_mutasi,id_user,id_gudang,hpp)
					//values('$id','$kd_brg','$awal','$masuk','$keluar','$akhir',NOW(),'0','$user','$idgud','$hpp')";
					//mysql_query($sql);
					//return $sql;
		}
		
		function keyED($txt,$encrypt_key) { 
			$encrypt_key = md5($encrypt_key); 
			$ctr=0; 
			$tmp = ""; 
			for ($i=0;$i<strlen($txt);$i++) { 
			if ($ctr==strlen($encrypt_key)) $ctr=0; 
			$tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1); 
			$ctr++; 
			} 
			return $tmp; 
		} 
		//die("A";
		
		public function encrypt($txt,$key) { 
			srand((double)microtime()*1000000); 
			$encrypt_key = md5(rand(0,32000)); 
			$ctr=0; 
			$tmp = ""; 
			for ($i=0;$i<strlen($txt);$i++) { 
			if ($ctr==strlen($encrypt_key)) $ctr=0; 
			$tmp.= substr($encrypt_key,$ctr,1) . 
			(substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1)); 
			$ctr++; 
			} 
			return $this->keyED($tmp,$key); 
		} 
		//die("A";
		
		public function decrypt($txt,$key) { 
			$txt = $this->keyED($txt,$key); 
			$tmp = ""; 
			for ($i=0;$i<strlen($txt);$i++) { 
			$md5 = substr($txt,$i,1); 
			$i++; 
			$tmp.= (substr($txt,$i,1) ^ $md5); 
			} 
			return $tmp; 
		} 
		//die("B";
		
		public function enkrip($text) {
			$key1="";
			$key2="";
			$key3="";
			$e = base64_encode($this->keyED($this->encrypt($this->keyED($text,$key1),$key2),$key3));
			//$e = base64_encode($text);
			return $e;
			}
			//die("C";
			
		public function dekrip($text) {
			$key1="";
			$key2="";
			$key3="";
			$d = $this->keyED($this->decrypt($this->keyED(base64_decode($text),$key3),$key2),$key1);
			//$d = base64_decode($text);
			return $d;
		}
		
		public function minus($nominal){
		
			if($nominal<0){
				$k="(".number_format(abs($nominal)).")";
				} else {$k=number_format($nominal);}
			return $k;
		}
		
		/**public function checksaldo($bulan,$tahun,$cabang,$akun){
			
			if($bulan==1){
			$awalk="kredit";
			$awald="debet";
			$mutd="D1";
			$mutk="K1";
			}else{
				for($i=1;$i<$bulan;$i++){
					$ad.="D".$i."+";
					$ak.="K".$i."+";
					}
				$awald="(ifnull(debet,0)+".substr($ad,0,-1).")";
				$awalk="(ifnull(kredit,0)+".substr($ak,0,-1).")";
				$mutd="D".$i;
				$mutk="K".$i;
				}
		$accch=explode("-",$akun);		
		$tbl="ak_acc a JOIN ak_acc_group d ON a.type=d.id_group LEFT JOIN ak_closing c ON a.account = c.ACC_CODE
									 AND c.TAHUN='". ($tahun-1) ."' 
									 LEFT JOIN ak_acc_saldo b ON a.account = b.ACC_CODE 
									 AND b.TAHUN='$tahun'
								where a.post_flag<>'0' AND a.account='".trim($accch[0]," ")."' AND 	
								b.CABANG='$cabang'";
								
		$f="a.description as desk, 
			   sum($awald) as DAWAL, 
			   sum($awalk) as KAWAL, 
			   sum($mutd) as mutd, 
			   sum($mutk) as mutk,
			   `status`
			   ";						
		//echo"select $f from $tbl <br><br>";
		$sa=$this->select($tbl,$f);
		foreach($sa as $sa1){
			
			if($sa1[status]=="D"){
				$awal=$sa1[DAWAL]-$sa1[KAWAL];
				$mut=$sa1[mutd]-$sa1[mutk];
				$akhir=$awal+$mut;
				$t="D";
			} else {
				$awal=$sa1[KAWAL]-$sa1[DAWAL];
				$mut=$sa1[mutk]-$sa1[mutd];
				$akhir=$awal+$mut;
				$t="K";
			}
		}
		
		return $akhir;	
		}**/
		public function checksaldo($bulan,$tahun,$cabang,$akun){
			
			if($bulan==1){
			$awalk="kredit";
			$awald="debet";
			$mutd="D1";
			$mutk="K1";
			}else{
				for($i=1;$i<$bulan;$i++){
					$ad.="ifnull(D".$i.",0)+";
					$ak.="ifnull(K".$i.",0)+";
					}
				$awald="(ifnull(debet,0)+".substr($ad,0,-1).")";
				$awalk="(ifnull(kredit,0)+".substr($ak,0,-1).")";
				$mutd="D".$i;
				$mutk="K".$i;
				}
		$accch=explode("-",$akun);	
		/* 
		$tbl="ak_acc a JOIN ak_acc_group d ON a.type=d.id_group LEFT JOIN ak_closing c ON a.account = c.ACC_CODE
									 AND c.TAHUN='". ($tahun-1) ."' 
									 LEFT JOIN ak_acc_saldo b ON a.account = b.ACC_CODE 
									 AND b.TAHUN='$tahun'
								where a.post_flag<>'0' AND a.account='".trim($accch[0]," ")."' AND 	
								b.CABANG='$cabang'"; */
		$tbl="  ak_acc a  
										JOIN mcabang b
										LEFT JOIN ak_acc_saldo c ON a.account = c.ACC_CODE
										AND c.CABANG = b.CBCODE
										AND c.tahun = '$tahun'
										LEFT JOIN ak_closing_dtl d ON a.account = d.ACC_CODE
										AND b.CBCODE = d.id_cabang
										AND d.tahun = '". ($tahun-1) ."'
										JOIN ak_acc_group e ON e.id_group=a.type 
										where a.post_flag<>'0' AND a.account='".trim($accch[0]," ")."' AND 	
								b.CBCODE='$cabang'";						
								
		$f="a.description as desk, 
			   sum($awald) as DAWAL, 
			   sum($awalk) as KAWAL, 
			   sum($mutd) as mutd, 
			   sum($mutk) as mutk,
			   e.status
			   ";						
		//echo"select $f from $tbl <br><br>";
		$sa=$this->select($tbl,$f);
		foreach($sa as $sa1){
			
			if($sa1[status]=="D"){
				$awal=$sa1[DAWAL]-$sa1[KAWAL];
				$mut=$sa1[mutd]-$sa1[mutk];
				$akhir=$awal+$mut;
				$t="D";
			} else {
				$awal=$sa1[KAWAL]-$sa1[DAWAL];
				$mut=$sa1[mutk]-$sa1[mutd];
				$akhir=$awal+$mut;
				$t="K";
			}
		}
		
		return $akhir;	
		}
		
		public function indonesian_date ($timestamp = '', $date_format = 'l', $suffix = '') {	
			if (trim ($timestamp) == '')
			{
					$timestamp = time ();
			}
			elseif (!ctype_digit ($timestamp))
			{
				$timestamp = strtotime ($timestamp);
			}
			# remove S (st,nd,rd,th) there are no such things in indonesia :p
			$date_format = preg_replace ("/S/", "", $date_format);
			$pattern = array (
				'/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
				'/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
				'/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
				'/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
				'/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
				'/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
				'/April/','/June/','/July/','/August/','/September/','/October/',
				'/November/','/December/',
			);
			$replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
				'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
				'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
				'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
				'Oktober','November','Desember',
			);
			$date = date ($date_format, $timestamp);
			$date = preg_replace ($pattern, $replace, $date);
			$date = "{$date} {$suffix}";
			return $date;
		}
		public function tutupbuku($cabang,$tgl){
				$tgl=date("Y-m-d",strtotime($tgl));
				$exp=explode("-",$tgl);
				$bul=$exp[1];
				$tah=$exp[0];
				$dt=count($this->select("m_tutup_buku","*","id_cabang='$cabang' and bulan='$bul' and tahun='$tah'"));
				//echo "$tgl select * from m_tutup_buku where id_cabang='$cabang' and bulan='$bul' and tahun='$tah'";
				return $dt;
		}
		public function SALDO($cab,$d1,$d2,$acc){
				$tglawal=date("Y-m-d",strtotime($d1));
				$expawal=explode("-",$tglawal);
				$bul=$expawal[1];
				$tah=$expawal[0];
				//ambil saldo awal ak_closing_dtl
				$sa=$this->select("ak_closing_dtl","ifnull(DEBET,0) as DEBET,ifnull(KREDIT,0) as KREDIT","id_cabang='$cab' and tahun='".($tah-1)."' and ACC_CODE='$acc'");
				foreach ($sa as $sa1){};
				//echo "select * from ak_closing_dtl where id_cabang='$cab' and bulan='$bul' and tahun='".($tah-1)."' and ACC_CODE='$acc'";
				//ambil saldo awal table ak_acc_saldo
				$sa7 = $this->select("ak_jurnal_dtl","sum(DEBET) as DEBM,sum(KREDIT) as KREM","TGL_JURNAL < '$d1' AND ACC_CODE = '$acc' AND ID_CAB = '$cab' and month(TGL_JURNAL)='$bul' and year(TGL_JURNAL)='$tah' ");
				//echo "select sum(DEBET) as DEBM,sum(KREDIT) as KREM from ak_jurnal_dtl where TGL_JURNAL < '$d1' AND ACC_CODE = '$acc' AND ID_CAB = '$cab' and month(TGL_JURNAL)='$bul' and year(TGL_JURNAL)='$tah' ";
				foreach ($sa7 as $sa8){};				
				
				if ($bul == 1) {
					$DAWAL = $sa1['DEBET']+$sa8['DEBM'];
					$KAWAL = $sa1['KREDIT']+$sa8['KREM'];
				}else{
					$debj = "";
					$krej = "";
					for ($i=1;$i<$bul;$i++){
					
					if ($i!=$bul-1){
					$debj = $debj . "ifnull(D".$i.",0)+";
					$krej = $krej . "ifnull(K".$i.",0)+";
					}else{
					$debj = $debj . "ifnull(D".$i.",0)";
					$krej = $krej . "ifnull(K".$i.",0)";}
					
					}
					
					$sa2=$this->select("ak_acc_saldo","$debj as DEBJ,$krej as KREJ","cabang='$cab' and tahun = '$tah' and acc_code = '$acc'");
					foreach ($sa2 as $sa3){};
					$DAWAL = $sa1['DEBET']+$sa3['DEBJ']+$sa8['DEBM'];
					$KAWAL = $sa1['KREDIT']+$sa3['KREJ']+$sa8['KREM'];					
				}
				//------------ambil nilai mutasi dari table ak_jurnal_dtl
				$sa3 = $this->select("ak_jurnal_dtl","ifnull(sum(DEBET),0) as DEBM,ifnull(sum(KREDIT),0) as KREM","TGL_JURNAL BETWEEN '$d1' AND '$d2' AND ACC_CODE = '$acc' AND ID_CAB = '$cab'");
				//echo "select sum(DEBET) as DEBM,sum(KREDIT) as KREM from ak_jurnal_dtl where TGL_JURNAL BETWEEN '$d1' AND '$d2' AND ACC_CODE = '$acc' AND ID_CAB = '$cab'";
				foreach ($sa3 as $sa4){};
				$DEBM = $sa4['DEBM'];
				$KREM = $sa4['KREM'];
				//isi saldo akhir
				$sa5 = $this->select("ak_acc a left join ak_acc_group b on a.type = b.id_group","b.status as status", "a.account='$acc'"); 
				//echo "select b.status as status from ak_acc a left join ak_acc_group b on a.type = b.id_group where a.account='$acc'";
				foreach($sa5 as $sa6){};
				
				if($sa6['status']=='D') {
					$SALDOAWAL = $DAWAL - $KAWAL;
					$SALDOAKHIR = $SALDOAWAL+$DEBM-$KREM;	
				}else{
					$SALDOAWAL = $KAWAL - $DAWAL;
					$SALDOAKHIR = $SALDOAWAL+$KREM-$DEBM;						
				}
				$SALDO = $SALDOAWAL ."/". $DEBM ."/" . $KREM ."/" .$SALDOAKHIR;
				return $SALDO;
		}
		public function jenistrans($j){
			switch($j){
				case 1;
				$dt="CC";
				break;
				case 2;
				$dt="DC";
				break;
				case 3;
				$dt="CH";
				break;
				case 5;
				$dt="MT";
				break;
				case 6;
				$dt="RF";
				break;
				case 7;
				$dt="VP";
				break;
			}
			return $dt;
			
		}	
		public function encrypt_url($string) {
		  $key = "nUJUfc#7^K)mkCi96[w5^uPKWf(wOe"; //key to encrypt and decrypts.
		  $result = '';
		  $test = "";
		   for($i=0; $i<strlen($string); $i++) {
		     $char = substr($string, $i, 1);
		     $keychar = substr($key, ($i % strlen($key))-1, 1);
		     $char = chr(ord($char)+ord($keychar));
		     $test[$char]= ord($char)+ord($keychar);
		     $result.=$char;
		   }
		   return urlencode(base64_encode($result));
		}
		public function decrypt_url($string) {
		    $key = "nUJUfc#7^K)mkCi96[w5^uPKWf(wOe"; //key to encrypt and decrypts.
		    $result = '';
		    $string = base64_decode(urldecode($string));
		   for($i=0; $i<strlen($string); $i++) {
		     $char = substr($string, $i, 1);
		     $keychar = substr($key, ($i % strlen($key))-1, 1);
		     $char = chr(ord($char)-ord($keychar));
		     $result.=$char;
		   }
		   return $result;
		}
		public function parseuri(){
			$uri=(isset($_SERVER['HTTPS']) ? "https" : "http") ."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$url1=parse_url($uri);
			$uride=$this->decrypt_url("$url1[query]");
			
			$ex=explode(";", $uride);
			for($i=0;$i<count($ex);$i++){
				//echo $i."<br>";
				$ex2=explode("=",$ex[$i]);
				$id=$ex2[0];
				$geturi[$id]=$ex2[1];
			}
			
			return $geturi;	
		}
		public function showprim($tab){	
				$tbl=$this->select("INFORMATION_SCHEMA.COLUMNS","COLUMN_NAME","TABLE_SCHEMA = '".$this->database."' AND TABLE_NAME = '$tab' AND COLUMN_KEY = 'PRI'");
				foreach($tbl as $tbval){
					$col=$tbval['COLUMN_NAME'];
					}
				return $col;
				
		}
		function calltable($tab,$prim,$where){
			foreach($this->select($tab,"*","$prim='$where'")as $dta);
			return $dta;
		}
		public function buttonField($name,$label,$collg,$value,$js,$id){
			if($label==''){$lbl=0;}else{$lbl=4;}
			$ins="
						<label class='control-label col-lg-$lbl'>$label</label>
						<div class='col-lg-$collg'>
							<input type='button' name='$name' id='$name' class='btn btn-success' value='$value' $js>
				   		</div>
				  "; 
			   
			return $ins;
		}
		public function textField($name,$label,$collg,$val,$read,$valu,$js){
			$exp=explode(":",$valu);
			$names=explode(":",$name);
			$name2=$names[0];
			$lb=explode(":",$val);
			
			if($exp[1]==''){
				$texv=$valu;
			}else{
				$dta=$this->calltable($exp[0],$exp[1],$exp[2]);
				$texv=$dta[$name2];
			}
			
			if($label==''){$lbl=0;}else{$lbl=4;}
			$ins="
						<label class='control-label col-lg-$lbl'>$label</label>
						<div class='col-lg-$collg'>
							<input type='text' name='$name2' id='$name2' class='form-control $lb[1]' autocomplete='off' $read $lb[0] value='$texv' $js>
				   		</div>
				  "; 
			$this->paramfield($name);	   
			$this->paramfieldview($name);
			$this->paramfielddata($name);	   
			return $ins;
		}
		public function hiddenField($name,$label,$collg,$val,$read,$valu,$js){
			$exp=explode(":",$valu);
			$names=explode(":",$name);
			$name2=$names[0];
			$lb=explode(":",$val);
			
			if($exp[1]==''){
				$texv=$valu;
			}else{
				$dta=$this->calltable($exp[0],$exp[1],$exp[2]);
				$texv=$dta[$name2];
			}
			
			if($label==''){$lbl=0;}else{$lbl=4;}
			$ins="
						<label class='control-label col-lg-$lbl'>$label</label>
						<div class='col-lg-$collg'>
							<input type='hidden' name='$name2' id='$name2' class='form-control $lb[1]' autocomplete='off' $read $lb[0] value='$texv' $js>
				   		</div>
				  "; 
			$this->paramfield($name);	   
			$this->paramfieldview($name);
			$this->paramfielddata($name);	   
			return $ins;
		}
		public function selectField($name,$label,$collg,$class,$type,$value,$edit,$js){
				$exp=explode(":",$edit);
				$names=explode(":",$name);
				$name2=$names[0];
			if($label==''){$lbl=0;}else{$lbl=4;}
			if($type==1){
				$dta=$this->calltable($exp[0],$exp[1],$exp[2]);
				$lo=explode("/",$value);
							for($k=0;$k<count($lo);$k++){
								$los=explode("^",$lo[$k]);
								if($los[0]==$dta[$name2]){
									$sel="selected";	
								}else{
									$sel="";	
								}
								$aka.="<option value='$los[0]'  $sel>$los[1]</option>";
							}
				$ins="
							<label class='control-label col-lg-$lbl'>$label</label>
							<div class='col-lg-$collg'>
								<select name='$name2' class='$class' $js>
									<option value='0'>--Pilih--</value>
									$aka
								</select>
							</div>
					  "; 
					   
			}
			if($type==2){
				$dta=$this->calltable($exp[0],$exp[1],$exp[2]);
				$lo=explode("/",$value);
				$col=explode(",",$lo[1]);
				$valu1=$col[0];$valu2=$col[1];
						$m=$this->select($lo[0],$lo[1]);
 						foreach($m as $mc){	
								if($mc[$valu1]==$dta[$name2]){
									$sel="selected";	
								}else{
									$sel="";	
								}
							$ak.="<option value='$mc[$valu1]' $sel>$mc[$valu2]</option>";
						}					
						
						$ins.="		<label class='control-label col-lg-$lbl'>$label</label>
										<div class='col-lg-$collg'>
											<select name='$name2' class='$class' $js>
												<option value='0'>--Pilih--</value>
												$ak
											</select>
										</div>
								";
			}
			$this->paramfield($name);
			$this->paramfieldview($name);
			$this->paramfielddata($name);	   
			return $ins;
		}
		
		// setter & getter
		private $nama;
		private $nama2;
		private $nama3;
		function paramfield($name){
			$split=explode(":",$name);
			if($split[1]!=''){
				$this->nama.=$split[0]."*";
			}
			
		}
		function getParam(){
			return $this->nama;
		}
		function paramfieldview($name){
			$split=explode("*",$name);
			for($i=0;$i<count($split);$i++){
				$subsplit=explode(":",$split[$i]);
				if($subsplit[1]=='join'){
					$name2.=$subsplit[2]."*";	
				}elseif($subsplit[1]=='ok'){
					$name2.=$subsplit[0]."*";	
				}
			}
			
			$this->nama2.=$name2;
		}
		function getParamView(){
			return $this->nama2;
		}
		function paramfielddata($name){
			$split=explode("*",$name);
			for($i=0;$i<count($split);$i++){
				$subsplit=explode(":",$split[$i]);
				if($subsplit[1]=='ok' || $subsplit[1]=='join'){
					$name3.=$subsplit[2]."*";	
				}
			}
			
			$this->nama3.=$name3;
		}
		function getParamData(){
			return $this->nama3;
		}
		
		public function	cekout(){
			foreach($this->select("r_ip","*")as $ips);
			echo $ips['ip'];
		}
		
		public function isian($tab,$isi,$aks,$id,$prim){	
			$is=explode("*",$isi);
			$jum=count($is);
			
			if($aks=='update'){
				foreach($this->select($tab,"*","$prim='$id'")as $val);
			}
			
			for($i=0;$i<$jum;$i++){	
					$si=explode(":",$is[$i]);$nm=explode("_",$si[0]);
					$label=ucfirst($nm[0]).' '.$nm[1]; $vl=$si[0];
					
					if((substr($si[1],0,7)!='select{') && (substr($si[1],0,7)!='select[')){	
						 $ins.="<div class='form-group'>
										<label class='control-label col-lg-4'>$label</label>
										<div class='col-lg-$si[2]'>
										<input type='$si[1]' name='$si[0]' id='$si[0]' class='form-control' autocomplete='off' value='$val[$vl]' $si[3] $si[4] $si[5]>
										</div>
						 </div>"; 
						 $this->textField($si[0],$label);
					}
					
					if(substr($si[1],0,7)=='select{'){
						$lo=explode("{",$si[1]);$los=explode("/",$lo[1]);$jumi=count($los);
						for($k=0;$k<$jumi;$k++){
							$spl=explode("^",$los[$k]);
							if($spl[0]==$val[$vl]){
								$sel="selected";	
							}else{
								$sel="";	
							}
							$aka.="<option value='$spl[0]' $sel>$spl[1]</option>";
						}
						$ins.="<div class='form-group'>
										<label class='control-label col-lg-4'>$label</label>
										<div class='col-lg-4'>
											<select name='$si[0]' $si[2]>
												$aka
											</select>
										</div>
										
						 </div>";
					}
					if(substr($si[1],0,7)=='select['){
						$lo=explode("[",$si[1]);$col=explode(",",$lo[2]);$valu=$col[0];$name=$col[1];
						$m=$this->select($lo[1],"$lo[2]");
 						foreach($m as $mc){	
							if($mc[$valu]==$val[$vl]){
								$sel="selected";	
							}else{
								$sel="";	
							}
							$ak.="<option value='$mc[$valu]' $sel>$mc[$name]</option>";
						}					
						
						$ins.="<div class='form-group'>
										<label class='control-label col-lg-4'>$label</label>
										<div class='col-lg-4'>
											<select name='$si[0]' $si[2]>
												$ak
											</select>
										</div>
						 </div>";
					}
			}	
		return $ins;
		//echo $is[0].$is[1];
		}
		
		public function field($field){
				$fiel=str_replace(
						array('a.','b.','c.','d.','e.','f.','g.','h.','i.','j.','k.','l.','m.','n.','o.','p.','q.','r.','s.','t.','u.','v.','w.','x.','y.','z.'),
						array("", ""),
						$field
					);
				return $fiel;	
		}
		public function bulanh($bulan)
	{
		
		switch($bulan){
			case 1;
			$rom = "Januari";
			break;
			case 2;
			$rom = "Februari";
			break;
			case 3;
			$rom = "Maret";
			break;
			case 4;
			$rom = "April";
			break;
			case 5;
			$rom = "Mei";
			break;
			case 6;
			$rom = "Juni";
			break;
			case 7;
			$rom = "Juli";
			break;
			case 8;
			$rom = "Agustus";
			break;
			case 9;
			$rom = "September";
			break;
			case 10;
			$rom = "Oktober";
			break;
			case 11;
			$rom = "November";
			break;
			case 12;
			$rom = "Desember";
			break;
		}
			
		return $rom;
	}
	function hari_ini($haris){
	$hari = date("D",strtotime($haris));
 
	switch($hari){
		case 'Sun':
			$hari_ini = "Minggu";
		break;
 
		case 'Mon':			
			$hari_ini = "Senin";
		break;
 
		case 'Tue':
			$hari_ini = "Selasa";
		break;
 
		case 'Wed':
			$hari_ini = "Rabu";
		break;
 
		case 'Thu':
			$hari_ini = "Kamis";
		break;
 
		case 'Fri':
			$hari_ini = "Jumat";
		break; 
 
		case 'Sat':
			$hari_ini = "Sabtu";
		break;
		
		default:
			$hari_ini = "Tidak di ketahui";		
		break;
	}
 
	return $hari_ini;
 
	}


	function ts($a){
		switch($a){
			case "T":
				$hari_ini = "1";
			break;
			case "A":			
				$hari_ini = "3";
			break;
			case "LB":			
				$hari_ini = "4";
			break;
			default:
				$hari_ini = "2";		
			break;
		}
		return $hari_ini;
	}
	function getbul($bul){
		if($bul=='01' || $bul=='02' || $bul=='03'){
            if($bul=='01'){
                $nil=3;
            }if($bul=='02'){
                $nil=2;
            }if($bul=='03'){
                $nil=1;
            }
        }
        if($bul=='04' || $bul=='05' || $bul=='06'){
            if($bul=='04'){
                $nil=3;
            }if($bul=='05'){
                $nil=2;
            }if($bul=='06'){
                $nil=1;
            }
        }
        if($bul=='07' || $bul=='08' || $bul=='09'){
            if($bul=='07'){
                $nil=3;
            }if($bul=='08'){
                $nil=2;
            }if($bul=='09'){
                $nil=1;
            }
        }
        if($bul=='10' || $bul=='11' || $bul=='12'){
            if($bul=='10'){
                $nil=3;
            }if($bul=='11'){
                $nil=2;
            }if($bul=='12'){
                $nil=1;
            }
        }
        return $nil;
	}


	public function tglindo($tgl){
		$x=explode("-",$tgl);
		$t="$x[2] ". $this->bulanh($x[1]) ." $x[0]";
		return $t;
	}
	public function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("Nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
      $temp = " ". $huruf[$nilai];
    } else if ($nilai <20) {
      $temp = $this->penyebut($nilai - 10). " belas";
    } else if ($nilai < 100) {
      $temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
    } else if ($nilai < 200) {
      $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
      $temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
    } else if ($nilai < 2000) {
      $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
      $temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
      $temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
      $temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
      $temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
    }     
    return $temp;
  }
  public function terbilang($nilai) {
    if($nilai<0) {
      $hasil = "minus ". trim($this->penyebut($nilai));
    } else {
      $hasil = trim($this->penyebut($nilai));
    }         
    return $hasil;
  }
		
	
}
?>