<?php

namespace Application\Model\Param\Storage;
use Application\Model\Param\Storage;
use Khansia\Generic\Result as Result;
use Zend\Db\ResultSet\ResultSet;

class Mysql extends \Khansia\Db\Storage implements Skeleton {
    
    private $_result;
	
	const LOP 				    = 'lop_cluster';
    const JOIN_LEFT			    = 'left';
    const ORDERHEADER 			= 'order_header';
	const MIDUSER		        = 'user_data_header';
	const DATAUserexTYPE		= 'user_data_role';
    const USERACCESS			= 'user_data_map';
    const USERCOUNTER		    = 'pasien';
	 
    public function array_change_key_case_recursive($input, $case = CASE_LOWER){ 
        if(!is_array($input)){ 
            trigger_error("Invalid input array '{$array}'",E_USER_NOTICE); exit; 
        } 
        // CASE_UPPER|CASE_LOWER 
        if(null === $case){ 
            $case = CASE_LOWER; 
        } 
        if(!in_array($case, array(CASE_UPPER, CASE_LOWER))){ 
            trigger_error("Case parameter '{$case}' is invalid.", E_USER_NOTICE); exit; 
        } 
        $input = array_change_key_case($input, $case); 
        foreach($input as $key=>$array){ 
            if(is_array($array)){ 
                $input[$key] = $this->array_change_key_case_recursive($array, $case); 
            } 
        } 
        return $input; 
    } 
    
    public function __construct(\Zend\Db\Adapter\Adapter $adapter, $config = array()) {

		parent::__construct($adapter, $config);
		 /* get conn instance */
      $this->_conn = $adapter->getDriver()->getConnection()->getResource();
   
	  
        //print_r($config);
        if (isset($config['tables'])) {
            $tables = $config['tables'];
            foreach ($tables as $key => $value) {
                if (array_key_exists($key, $this->_tables) && $value) {
                    $this->_tables[$key] = $this->_($value);
                }
            }
        }
    }
    
    public function fetchAll(\Zend\Db\Sql\Select $select, $raw = true){

        $statement = $this->_sql->prepareStatementForSqlObject($select);
        if ($result = $statement->execute()) {
            $resultset = new \Zend\Db\ResultSet\ResultSet();
            $data = $resultset->initialize($result)->toArray();
            return $data;
        }

        return false;
    }


    public function deleteGlobal($tabel, $where){
       
        $stmt = $this->_db->query("delete from $tabel where $where ");

        return $stmt->execute();
    }
    
    public function saveGlobal($atribut, $table){
        
        $insert = $this->_sql->insert()
                ->into($table)
                ->values($atribut);
                // echo str_replace('"','',$insert->getSqlString());die;
        $result = $this->execute($insert);
        return $result;

    }

    public function updateGlobal($tabel, $data, $where){
        $update = $this->_sql->update()
            ->table($tabel)
            ->set($data)
            ->where($where);
            // echo str_replace('"','',$update->getSqlString());die;
        $result = $this->execute($update);
        return $result;
    }

    public function getLastSeqPostgree($tabel, $column){
        $result = new Result();

        $sql        = " select max($column) as total from $tabel ";
        
        $stmt       = $this->_db->query($sql);

        $proced     = $stmt->execute();

        $seq        = $proced->current();

        if ($seq) {
            $result->code = 0;
            $result->info = 'OK';
            $result->data = $seq;            
        } else {
            $result->code = 1;
            $result->info = 'Seq error';
        }

        return $result;

    }

     /* cek duplikasi data */
     public function checkDuplicateData($table, $column, $value, $msg){
        $result = new Result();
        try {
            
            $select = $this->select()
                    ->from($table)
                    ->where(array($column => $value));
            //echo str_replace('"','',$select->getSqlString());die;
            $return = $this->fetchAll($select);
            if ($return) {
                
                $result->code = 100;
                $result->info = 'DUPLICATE "'.$msg.'" ';
                $result->data = $return;
                
                
            } else {
                $result->code = 0;
                $result->info = 'nok';
            }
            
        }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
            $result->code = 3;
            $result->info = 'ERROR : ' . $ex->getMessage();
        } catch (\Exception $ex) {
            $result->code = 4;
            $result->info = 'ERROR : ' . $ex->getMessage();
        }
        return $result;
    }


    public function loadParam($paramtype = NULL, $param_val3 = null, $param_parent = null){
		 $result = new Result();
        try {
				$select = $this->select()
						->from(array('' => 'master_parameter'));
                if($paramtype){
                    $select->where(array($this->_('param_type', '') . " = '" .$paramtype."'"));
                }
                if($param_val3){
                    $select->where(array($this->_('param_val3', '') . " = '" .$param_val3."'"));
                }
                if($param_parent){
                    $select->where(array($this->_('param_parent', '') . " = '" .$param_parent."'"));
                }

                $select->order(array('idm_parameter ASC'));
			// echo str_replace('"','',$select->getSqlString());die;

			$return = $this->fetchAll($select);

            if ($return) {

                $result->code = 0;
                $result->info = 'OK';
                $result->data = $return;


            } else {
                $result->code = 1;
                $result->info = 'nok';
            }

        }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
            $result->code = 3;
            $result->info = 'ERROR : ' . $ex->getMessage();
        } catch (\Exception $ex) {
            $result->code = 4;
            $result->info = 'ERROR : ' . $ex->getMessage();
        }
        return $result;
    }

    /* load data mitra */
	public function loadProfileMap($role) {
		$result = new Result();
	   	try {

			$sql = " 	select am.map_id, ua.access_name, ua.access_code, am.access_status 
						from user_data_access ua, user_data_map am
						where ua.access_code=am.access_code and am.role_code='$role' ";
            
            //print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
            $listdata   = array();
            
            foreach($resdata as $val){
          
                    // print_r($val);die;
                array_push($listdata, $val);

            }

			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }

    /* get data tag  */
	public function getTypepost(){
		$result = new Result();
		
		try{
			$select =  $this->select()
						->from(self::DATAUserexTYPE);
			
			if($data = $this->fetchAll($select)){
				$result->code = 0;
				$result->info = 'OK';
				$result->data = array_change_key_case($data);
			}else{
				$result->code = 1;
				$result->info = 'not found';
				$result->data = array_change_key_case($data);
			}
			
		}catch (\Exception $ex) {
			$result->code = 2;
			$result->info = 'Error:' . $ex->getMessage();
			$result->data = $ex->getMessage();
		}
		
		return $result;
		
    }
    
    /* load data user */
	public function getUserData($tipe = null, $id = null){
		$result = new Result();
	   	try {

			$sql = "    SELECT
                        CASE
                                concat ( 'Regional ', A.regional_id ) 
                                WHEN 'Regional ' THEN
                                '-' ELSE concat ( 'Regional ', A.regional_id ) 
                            END Regional,
                            A.*,
                            b.NAME role_name,
                            dw.divisi_witel_name witel 
                        FROM
                            user_data_header
                            A LEFT JOIN user_data_role b ON A.ROLE = b.access_role_code
                            LEFT JOIN divisi_witel dw ON A.witel_id = dw.divisi_witel_id 
                        WHERE
                            iduser IS NOT NULL"; 

		   
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }

    /* load data user */
	 public function loadUserData($id){
		$result = new Result();
	   	try {
            $sql = "   SELECT antrian_pasien.id_pasien, antrian_pasien.nama, antrian_pasien.no_antrian,antrian_pasien.no_rekam_medis, dokter_to_poli.nama_dokter, poli.nama_poli , dokter_to_poli.kode_dokter, dokter_to_poli.id_dokter,antrian_pasien.sisa_antrian, antrian_pasien.create_date
			FROM antrian_pasien
			INNER JOIN dokter_to_poli
			ON antrian_pasien.id_dokter = dokter_to_poli.id_dokter
			INNER JOIN poli
			ON antrian_pasien.id_poli = poli.id_poli
			WHERE antrian_pasien.id_antrian = $id 	
			";
				
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			// print_r($listdata);die;
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	
	}
	 public function loadpasien($id = null, $norm = null){
		$result = new Result();
	   	try {

            $sql = "  SELECT  * FROM pasien"; 
            
            if($id){
                $sql .= " WHERE id_pasien = $id ";
			}
			if($norm){
				$sql .= " WHERE no_rekam_medis = '$norm'";
			}
			$sql .= " ORDER BY no_rekam_medis ASC";
			
			// print_r($sql);die;

		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			// print_r($listdata);die;
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }

    public function loadMaxCounter(){

        $result = new Result();
        try {
            $sql = " SELECT coalesce(MAX(id_pasien), 0)  AS maxid FROM pasien";
            
			$stmt = $this->_db->query($sql);
            $resdata = $stmt->execute();
            
            $listdata = array();
            
                $res = $resdata->current();
                array_push($listdata,$res);

            if ($listdata) {
                $result->code = 0;
                $result->info = 'OK';
                $result->data = $listdata[0]['maxid'] + 1;
            } else {
                $result->code = 1;
                $result->info = 'failed get max number counter';
            }
            
        }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
            $result->code = 3;
            $result->info = 'ERROR : ' . $ex->getMessage();
        } catch (\Exception $ex) {
            $result->code = 4;
            $result->info = 'ERROR : ' . $ex->getMessage();
        }
        
        return $result;
	}

	
	public function loadsisaantrian($id,$no){
		$result = new Result();
	   	try {

			$sql = "  SELECT COUNT(id_pasien) 
			FROM antrian_pasien
			 WHERE id_pasien is not null AND create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR' AND
			id_dokter =$id AND (status_code <> 50 and status_code <> 60) and no_antrian < $no";
			

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;

		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	public function loaddatapasien($id){
		$result = new Result();
	   	try {


			$sql = "  SELECT  * FROM pasien WHERE id_pasien = $id ";
			

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	public function loadjumlahpasien(){
		$result = new Result();
	   	try {

			$sql = "  SELECT count(id_pasien) FROM pasien ";
			

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	public function loadChart($id){
		$result = new Result();
	   	try {

			$sql = " SELECT dp.id_dokter, dp.nama_dokter,ap.id_dokter, count (ap.id_dokter) as total  from antrian_pasien ap, dokter_to_poli dp
			WHERE ap.id_poli = $id and dp.id_dokter = ap.id_dokter AND ap.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR'
			
			GROUP BY dp.id_dokter, ap.id_dokter ";
			

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	public function loadpasiendalamantrian(){
		$result = new Result();
	   	try {

			$sql = "  SELECT count(id_pasien) FROM antrian_pasien WHERE create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR'  ";
			

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	public function loadpasienterlewat(){
		$result = new Result();
	   	try {

			$sql = "  SELECT count(status_code)  FROM antrian_pasien WHERE status_code= 60  AND create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR' ";
			

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	public function loadterlayani(){
		$result = new Result();
	   	try {

			$sql = "  SELECT count(status_code)  FROM antrian_pasien WHERE status_code = 50 and create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR' ";
			

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	public function getRegister($where){
		$result = new Result();
	   	try {

			$sql = "SELECT id_antrian, id_dokter, no_antrian, create_date FROM antrian_pasien WHERE create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR' AND $where  ";
			
		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	
	

	public function cekpasien($no_rekam_medis){
		$result = new Result();
	   	try { 

			$sql = " SELECT id_pasien, no_rekam_medis FROM pasien 

				WHERE no_rekam_medis = '$no_rekam_medis' "; 
            
		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
                array_push($listdata, $val);
            }
			
		    if ($listdata!=null) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}

	public function cekktp($no_rekam_medis, $ktp){
		$result = new Result();
	   	try {

			$sql = "SELECT no_rekam_medis  FROM pasien WHERE no_rekam_medis = '$no_rekam_medis' ";
            
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
			$listdata = array();
			
		    foreach($resdata as $val){
                array_push($listdata, $val);
            }
			
		    if ($listdata) {
				$sqlktp =  "SELECT ktp, no_rekam_medis  FROM pasien WHERE no_rekam_medis = '$no_rekam_medis' AND ktp = '$ktp'";

				$stmtktp       = $this->_db->query($sqlktp);
				$resdataktp    = $stmtktp->execute();

				$listdataktp = array();
			
				foreach($resdataktp as $val){
					array_push($listdataktp, $val);
				}
				if($listdataktp){
					$result->code = 0;
					$result->info = 'OK';
					$result->data = $listdataktp;
				}else{
					$result->code = 1;
					$result->info = 'your ktp wrong';
					$result->data = $listdata;
				}
			   	
		    }else{
			   $result->code = 2;
			   $result->info = 'Your no_rekam_medis wrong';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	
    public function loadRegisterPoli($id = null){
		$result = new Result();
	   	try {

			$sql = "  SELECT * FROM poli
			ORDER BY nama_poli ASC"; 
			
			
            
         

		     //print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }

    public function loadRegisterDokter($id = null){
		$result = new Result();
	   	try {

            $sql = "  SELECT * FROM dokter_to_poli";
            
            if ($id){
                $sql .= " WHERE id_poli=$id AND id_condition='1'";
            }
            
            $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }

    public function loadRegisterRuang($id = null){
		$result = new Result();
	   	try {

            $sql = "  SELECT * FROM ruang";
            
            if ($id){
                $sql .= " WHERE id_poli=$id AND id_condition='1'";
            }
            
            $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }
    
	public function loadCondition($id = null){
		$result = new Result();
	   	try {

            $sql = "  SELECT * FROM condition"; 
            
         

		     //print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }
  
    public function loadantrianregister($id	= null){
	 	$result = new Result();
	   	try {

			$sql = "   SELECT register_dokter.id_register, dokter_to_poli.nama_dokter, poli.nama_poli, ruang.nama_ruang,register_dokter.antrian_all,register_dokter.waktu_antrian, register_dokter.jam_mulai, register_dokter.create_date,register_dokter.estimasi_selesai,poli.id_poli,ruang.id_ruang,dokter_to_poli.id_dokter
			FROM register_dokter
			INNER JOIN poli
			ON register_dokter.id_poli = poli.id_poli
			INNER JOIN dokter_to_poli
			ON register_dokter.id_dokter = dokter_to_poli.id_dokter
			INNER JOIN ruang
			ON register_dokter.id_ruang = ruang.id_ruang
			Where  register_dokter.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR'
		";
            
            if($id){
                $sql .= " AND register_dokter.id_register= $id AND register_dokter.id_register IS NOT NULL" ;
            }

            $sql .= " 	ORDER BY  poli.nama_poli asc , register_dokter.create_date";

            

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }
    public function loadantrianregisterall($id	= null){
	 	$result = new Result();
	   	try {

			$sql = "   SELECT register_dokter.id_register, dokter_to_poli.nama_dokter, poli.nama_poli, ruang.nama_ruang,register_dokter.antrian_all,register_dokter.waktu_antrian, register_dokter.jam_mulai, register_dokter.create_date
			FROM register_dokter
			INNER JOIN poli
			ON register_dokter.id_poli = poli.id_poli
			INNER JOIN dokter_to_poli
			ON register_dokter.id_dokter = dokter_to_poli.id_dokter
			INNER JOIN ruang
			ON register_dokter.id_ruang = ruang.id_ruang
			  
		";
            
            if($id){
                $sql .= " Where register_dokter.id_register= $id AND register_dokter.id_register IS NOT NULL" ;
            }

            $sql .= " 	ORDER BY  poli.nama_poli asc , register_dokter.create_date";

            

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }
  
    public function loadkelolapasien($id = null){
		$result = new Result();
	   	try {

            $sql = " SELECT * FROM pasien"; 
            
            if($id){
                $sql .= " WHERE id_pasien = $id ";
			}
			$sql .= " ORDER BY no_rekam_medis ASC";

        

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }
    public function loadkelolapoli($id = null){
		$result = new Result();
	   	try {

            $sql = " SELECT  pl.id_poli,pl.nama_poli, pl.kode_poli,pl.deskripsi_poli,pl.image_poli,uss.password,uss.iduser FROM poli pl
			LEFT JOIN  user_data_header uss
			On pl.nama_poli = uss.username"; 
            
            if($id){
                $sql .= " WHERE pl.id_poli = $id ";
			}
			$sql .= " ORDER BY pl.nama_poli ASC";

        

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }
    public function loadkeloladokter($id = null){
		$result = new Result();
	   	try {

            $sql = "  SELECT dokter_to_poli.id_dokter, dokter_to_poli.nama_dokter, dokter_to_poli.kode_dokter , poli.id_poli, poli.nama_poli,dokter_to_poli.image_dokter, condition.condition,condition.id_condition
					FROM dokter_to_poli
					INNER JOIN poli
					ON dokter_to_poli.id_poli = poli.id_poli
					INNER JOIN condition
					ON condition.id_condition = dokter_to_poli.id_condition
					"; 
            
            if($id){
                $sql .= " Where dokter_to_poli.id_dokter = $id";
			}
			
			$sql .= " ORDER BY nama_poli ASC";

        

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }
    
	public function loadkelolaruang($id = null){
		$result = new Result();
	   	try {

            $sql = "SELECT ruang.id_ruang, ruang.nama_ruang, poli.nama_poli,poli.id_poli, condition.condition,condition.id_condition, ruang.lantai
					FROM RUANG
					INNER JOIN poli
					ON ruang.id_poli = poli.id_poli
					INNER JOIN condition
					ON condition.id_condition = ruang.id_condition"; 

            if($id){
                $sql .= " 	WHERE ruang.id_ruang = $id ";
			}
			$sql .= " ORDER BY nama_poli ASC";

        

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}

	public function loadantrianmiss($id){
		$result = new Result();
	   	try {

            $sql = "SELECT antrian_pasien.no_antrian,antrian_pasien.id_pasien,antrian_pasien.create_date, antrian_pasien.nama, dokter_to_poli.kode_dokter , dokter_to_poli.nama_dokter, dokter_to_poli.id_dokter,poli.kode_poli, poli.nama_poli ,counter_status.status_code, counter_status.status_name
			FROM antrian_pasien    
			INNER JOIN poli
			ON poli.id_poli = antrian_pasien.id_poli 
			INNER JOIN dokter_to_poli
			ON dokter_to_poli.id_dokter = antrian_pasien.id_dokter 
			INNER JOIN counter_status
			ON counter_status.status_code = antrian_pasien.status_code 
			WHERE antrian_pasien.id_pasien IS NOT NULL AND  antrian_pasien.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR'AND antrian_pasien.status_code=60 and antrian_pasien.id_poli = $id
			
			"; 

			$sql .= " 	ORDER BY poli.nama_poli asc , antrian_pasien.no_antrian asc" ;
		
        

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	public function loadantrianunregis($id){
		$result = new Result();
	   	try {

            $sql = "SELECT antrian_pasien.id_pasien, antrian_pasien.nama, antrian_pasien.no_antrian,antrian_pasien.ktp,antrian_pasien.no_rekam_medis,poli.nama_poli,dokter_to_poli.nama_dokter,dokter_to_poli.id_dokter, antrian_pasien.create_date
			FROM antrian_pasien
			INNER JOIN poli
			on poli.id_poli = antrian_pasien.id_poli
			INNER JOIN dokter_to_poli
			on dokter_to_poli.id_dokter = antrian_pasien.id_dokter
			
			WHERE id_pasien IS NOT NULL AND antrian_pasien.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR' and antrian_pasien.id_poli = $id
			ORDER BY poli.nama_poli asc
			
			"; 
		
        

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	
	public function updatekelolaantrian($idpasien = null,$iddok = null){
		$result = new Result();
	   	try {

            $sql = "SELECT antrian_pasien.no_antrian,antrian_pasien.id_pasien, antrian_pasien.nama, dokter_to_poli.kode_dokter , dokter_to_poli.nama_dokter, dokter_to_poli.id_dokter,poli.kode_poli, poli.nama_poli , counter_status.status_name,  antrian_pasien.create_date 
			FROM antrian_pasien    
			INNER JOIN poli
			ON poli.id_poli = antrian_pasien.id_poli 
			INNER JOIN dokter_to_poli
			ON dokter_to_poli.id_dokter = antrian_pasien.id_dokter 
			INNER JOIN counter_status
			ON counter_status.status_code = antrian_pasien.status_code
			WHERE antrian_pasien.id_dokter=$iddok and antrian_pasien.id_pasien=$idpasien AND antrian_pasien.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR'";
			; 

		
        

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }


    public function loadPoli($id = null){
		$result = new Result();
	   	try {

            $sql = " SELECT poli.id_poli, poli.nama_poli, antrian_pasien.create_date
			FROM antrian_pasien
			INNER JOIN poli
			ON antrian_pasien.id_poli = poli.id_poli
			WHERE antrian_pasien.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR'
			GROUP BY poli.id_poli, antrian_pasien.create_date";
					
		

	
			 
					
			
            
         

		    //  print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }


    public function loadDokter($id = null){
		$result = new Result();
	   	try {

            $sql = "  SELECT * FROM dokter_to_poli ";
            
            if ($id){
                $sql .= " where id_poli=$id and id_condition='2' ";
            }
            
            $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	public function loadDokterWaktu($id = null){
		$result = new Result();
	   	try {

            $sql = "  SELECT DISTINCT on (idd.create_date) idd.create_date,dp.id_dokter, dp.nama_dokter,pl.id_poli
			FROM antrian_pasien idd
						LEFT JOIN dokter_to_poli dp
						on idd.id_dokter = dp.id_dokter
							LEFT JOIN poli pl
						on idd.id_poli = pl.id_poli
						WHERE idd.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR'  	AND pl.id_poli=$id";
            
          
            
            $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }

		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }
    
    public function tampildokter(){
		$result = new Result();
	   	try {
            
            $sql = "SELECT dokter_to_poli.id_dokter,dokter_to_poli.image_dokter, dokter_to_poli.nama_dokter , poli.nama_poli ,  dokter_to_poli.kode_dokter
            FROM dokter_to_poli
            INNER JOIN poli
            ON dokter_to_poli.id_poli = poli.id_poli 
             ORDER BY nama_poli ASC ";

         
            // $sql .= " ORDER BY jumlah DESC";

		    //print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
            // print_r($listdata);die;
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
    public function tampildatapoli($id){
		$result = new Result();
	   	try {
            
            $sql = "SELECT * FROM poli
            	where id_poli=$id";

         
            // $sql .= " ORDER BY jumlah DESC";

		    //print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
            // print_r($listdata);die;
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}

    public function  tampilpoli($id= null){
		$result = new Result();
	   	try {
            
           
					
			if ($id){
				$sql = "SELECT poli.nama_poli, poli.kode_poli, poli.deskripsi_poli ,poli.id_poli, poli.image_poli, dokter_to_poli.nama_dokter, dokter_to_poli.id_dokter, dokter_to_poli.kode_dokter, dokter_to_poli.image_dokter
				FROM poli
				INNER JOIN dokter_to_poli
				ON dokter_to_poli.id_poli = poli.id_poli" ;
				
				$sql .= " where poli.id_poli = $id";
				$sql .=	" ORDER BY poli.nama_poli ASC";
			} else {
				$sql = "SELECT * FROM poli"; 	
			}
		
			

         
            

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
            // print_r($listdata);die;
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	
    public function loadPilihAntrian($iddok, $id_poli){
		$result = new Result();
	   	try {

            $sql = "SELECT DISTINCT on (idd.no_antrian) idd.no_antrian,idd.no_antrian, idd.id_pasien , idd.create_date,rd.waktu_antrian, rd.jam_mulai, idd.status_code,rd.id_register,idd.id_poli,idd.id_dokter,idd.id_antrian,idd.nama
			FROM antrian_pasien idd 
			LEFT JOIN register_dokter rd
			on idd.id_register = rd.id_register
			WHERE idd.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR' and idd.id_dokter =$iddok AND idd.id_poli= $id_poli ORDER BY no_antrian ASC" ;           
		   
		
			// print_r($sql);die;
           
            $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                
                array_push($listdata, $val);

            }
            // print_r($listdata);
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	
	public function valpilihAntrian($id_pasien = null){
		$result = new Result();
	   	try {

            $sql = "SELECT DISTINCT on (idd.no_antrian) idd.no_antrian,idd.no_antrian, idd.id_pasien , idd.create_date,rd.waktu_antrian, rd.jam_mulai, idd.status_code,rd.id_register,idd.id_poli,idd.id_dokter,idd.id_antrian,idd.nama
			FROM antrian_pasien idd 
			LEFT JOIN register_dokter rd
			on idd.id_register = rd.id_register
			WHERE idd.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR' AND idd.id_pasien = $id_pasien"; 


        

		    // print_r($sql);die;
		    $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }

    public function loadAntrianAdmin($idokter = null){
		$result = new Result();
	   	try {

            $sql = "SELECT antrian_pasien.no_antrian, antrian_pasien.nama, dokter_to_poli.kode_dokter , dokter_to_poli.nama_dokter, dokter_to_poli.id_dokter,poli.kode_poli, poli.nama_poli , counter_status.status_name, ruang.nama_ruang, antrian_pasien.create_date
			FROM antrian_pasien    
			INNER JOIN poli
			ON poli.id_poli = antrian_pasien.id_poli 
			INNER JOIN dokter_to_poli
			ON dokter_to_poli.id_dokter = antrian_pasien.id_dokter 
			INNER JOIN counter_status
			ON counter_status.status_code = antrian_pasien.status_code 
			INNER JOIN ruang
			on ruang.id_ruang = antrian_pasien.id_ruang
			WHERE antrian_pasien.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR'
			
          
						" ;           
             if ($idokter){
                $sql .= "  AND antrian_pasien.id_pasien IS NOT NULL AND dokter_to_poli.id_dokter = $idokter  AND antrian_pasien.status_code=10   ORDER BY no_antrian ASC";
            }
                

        


            $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
            // print_r($listdata);
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
	}
	
    public function loadAntrian($id_poli){
		$result = new Result();
	   	try {
			   $sql="SELECT DISTINCT on (idd.id_dokter) idd.id_dokter,idd.no_antrian,dok.nama_dokter,pol.nama_poli,dok.kode_dokter,rur.nama_ruang ,idd.status_code,idd.id_poli , idd.create_date
			   FROM antrian_pasien idd 
			   
		   LEFT JOIN dokter_to_poli dok 
			   on idd.id_dokter = dok.id_dokter 
		   LEFT JOIN poli pol 
			   on idd.id_poli = pol.id_poli
		   LEFT JOIN ruang rur 
			   on idd.id_ruang = rur.id_ruang
		   LEFT JOIN counter_status cs 
			   on cs.status_code = idd.status_code 	
					   
		   WHERE idd.id_pasien is not null  AND  idd.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR'
		and  
		(cs.status_code=30 or cs.status_code =40) and 
		idd.id_poli=$id_poli
		
						
			    ";
        //  print_r($sql);die;
            
            $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
               
                array_push($listdata, $val);

            }
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }

    
    public function loadNoAntrian($id = null ){
		$result = new Result();
	   	try {   

                $sql = "SELECT antrian_pasien.no_antrian,  antrian_pasien.id_dokter, dokter_to_poli.kode_dokter, counter_status.status_name,counter_status.status_code, ruang.nama_ruang,antrian_pasien.create_date 
                FROM antrian_pasien 
                INNER JOIN counter_status
                ON counter_status.status_code = antrian_pasien.status_code
                INNER JOIN dokter_to_poli
                ON dokter_to_poli.id_dokter = antrian_pasien.id_dokter 
				INNER JOIN ruang
				ON ruang.id_ruang = antrian_pasien.id_ruang
				WHERE antrian_pasien.create_date > CURRENT_TIMESTAMP - INTERVAL '24 HOUR'" ;           
             if ($id){
                $sql .= " AND antrian_pasien.id_pasien IS NOT NULL  AND dokter_to_poli.id_dokter = $id AND antrian_pasien.status_code=10 or dokter_to_poli.id_dokter = $id   and antrian_pasien.status_code=30 or  dokter_to_poli.id_dokter = $id and  antrian_pasien.status_code=40  ORDER BY no_antrian ASC";
            }
            
            $stmt       = $this->_db->query($sql);
		    $resdata    = $stmt->execute();
		   
		    $listdata = array();
		    foreach($resdata as $val){
          
                // print_r($val);die;
                array_push($listdata, $val);

            }
            // print_r($listdata);
			
		    if ($listdata) {
			   $result->code = 0;
			   $result->info = 'OK';
			   $result->data = $listdata;
		    }else{
			   $result->code = 1;
			   $result->info = 'nok';
		    }
		   
	    }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
		   $result->code = 3;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }catch (\Exception $ex) {
		   $result->code = 4;
		   $result->info = 'ERROR : ' . $ex->getMessage();
	    }
	   return $result;
    }




    public function loadUserCounter($status = null, $iddok = null, $no_antrian = null, $id_poli = null){
		$result = new Result();
        try {
            $sql = " SELECT antrian_pasien.no_antrian, dokter_to_poli.nama_dokter , poli.nama_poli , counter_status.status_name
            FROM antrian_pasien    
            INNER JOIN poli
            ON poli.id_poli = antrian_pasien.id_poli 
            INNER JOIN dokter_to_poli
            ON dokter_to_poli.id_dokter = antrian_pasien.id_dokter 
            INNER JOIN counter_status
            ON counter_status.status_code = antrian_pasien.status_code  ";

             $sql .= " WHERE antrian_pasien.id_pasien IS NOT NULL AND dokter_to_poli.id_dokter = $iddok ORDER BY no_antrian ASC LIMIT 1 "  ;
            
           
            //print_r($mode);die;
			$stmt = $this->_db->query($sql);
			$resdata = $stmt->execute();
			
			$listdata = array();
			while($resdata->next()){
				$res = $resdata->current();
				 array_push($listdata,$res);
			}
			//print_r($listdata);die;
            if ($listdata) {
                $result->code = 0;
                $result->info = 'OK';
                $result->data = $listdata;
            } else {
                $result->code = 1;
                $result->info = 'failed load user counter';
            }
            
        }catch (\Zend\Db\Adapter\Exception\RuntimeException $ex) {
            $result->code = 3;
            $result->info = 'ERROR : ' . $ex->getMessage();
        } catch (\Exception $ex) {
            $result->code = 4;
            $result->info = 'ERROR : ' . $ex->getMessage();
        }
        
        return $result;
    }

}
