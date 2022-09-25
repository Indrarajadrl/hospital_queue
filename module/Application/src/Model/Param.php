<?php
namespace Application\Model;

use Khansia\Generic\Objects\Map;
use Khansia\Generic\Objects\Mapper;
use Khansia\Generic\Set;
use Khansia\Generic\Result as Result;

class Param extends Mapper {
	
	private $_storage;

    public function __construct(\Application\Model\Param\Storage\Skeleton $storage) {
		$this->_storage = $storage;
        $this->_result = new Result();

		parent::__construct(
                array(),
                array(
					// this mapper table on db
                ),
				parent::CASE_INSENSITIVE
			);
	}	
    
    public function save($update = false) {
        $result = $this->_storage->saveUserCounter($this, $update);
        if ($result->code == 0) {
            $this->id = $result->data;
        }
        return $result;
    }

	
    public function getParam($paramtype = null, $param_val3 = null, $param_parent = null){
        $data = $this->_storage->loadParam($paramtype, $param_val3, $param_parent);
        return $data;
    }

    public function saveGlobal($param, $table){
        $data = $this->_storage->saveGlobal($param, $table);
        return $data;
    }
    public function loadregisterantrian($where){
        $data = $this->_storage->getRegister($where);
        return $data;
    }
    
   
    public function updateGlobal($tabel, $data, $where){
        $data = $this->_storage->updateGlobal($tabel, $data, $where);
        return $data;
    }

    public function getLastSeqPostgree($tabel, $column){
        $data = $this->_storage->getLastSeqPostgree($tabel, $column);
        return $data;
    }

    public function loadUser($id = null, $iddok = null){
        $data = $this->_storage->loadUserData($id,$iddok);
        return $data;
    }
    public function loadpasien($id = null, $norm = null){
        $data = $this->_storage->loadpasien($id, $norm);
        return $data;
    }
    public function loaddatapasien($id){
        $data = $this->_storage->loaddatapasien($id);
        return $data;
    }
    public function loadsisaantrian($id,$no){
        $data = $this->_storage->loadsisaantrian($id,$no);
        return $data;
    }
    public function cekpasien($no_rekam_medis = null,$ktp = null  ){
        $data = $this->_storage->cekpasien($no_rekam_medis, $ktp);
        return $data;
    }
    public function cekktp($no_rekam_medis, $ktp){
        $data = $this->_storage->cekktp($no_rekam_medis, $ktp);
        return $data;
    }

    public function loadRegisterPoli($id = null){
        $data = $this->_storage->loadRegisterPoli($id);
        return $data;
    }
    
    public function loadRegisterDokter($id = null){
        $data = $this->_storage->loadRegisterDokter($id);
        return $data;
    }

    public function loadRegisterRuang($id = null){
        $data = $this->_storage->loadRegisterRuang($id);
        return $data;
    }
   
    public function loadCondition($id = null){
        $data = $this->_storage->loadCondition($id);
        return $data;
    }

    public function loadantrianregister($id = null){
        $data = $this->_storage->loadantrianregister($id);
        return $data;
    }
    public function loadantrianregisterall($id = null){
        $data = $this->_storage->loadantrianregisterall($id);
        return $data;
    }
    
    public function loadkelolapoli($id = null){
        $data = $this->_storage->loadkelolapoli($id);
        return $data;
    }
    public function loadkelolapasien($id = null){
        $data = $this->_storage->loadkelolapasien($id);
        return $data;
    }
    public function loadkeloladokter($id = null){
        $data = $this->_storage->loadkeloladokter($id);
        return $data;
    }
    public function loadkelolaruang($id = null){
        $data = $this->_storage->loadkelolaruang($id);
        return $data;
    }
    public function updatekelolaantrian($idpasien = null, $iddok = null){
        $data = $this->_storage->updatekelolaantrian($idpasien,$iddok);
        return $data;
    }
    public function loadantrianmiss($id){
        $data = $this->_storage->loadantrianmiss($id);
        return $data;
    }
    
   

    public function loadPoli($id_poli = null){
        $data = $this->_storage->loadPoli($id_poli);
        return $data;
    }
    public function loadantrianunregis($id){
        $data = $this->_storage->loadantrianunregis($id);
        return $data;
    }
    public function loadPilihAntrian($iddok = null , $id_poli= null ){
        $data = $this->_storage->loadPilihAntrian($iddok, $id_poli);
        return $data;
    }
    public function valpilihAntrian($id_pasien = null ){
        $data = $this->_storage->valpilihAntrian($id_pasien);
        return $data;
    }
    public function loadDokter($id = null){
        $data = $this->_storage->loadDokter($id);
        return $data;
    }
    public function loadDokterWaktu($id = null){
        $data = $this->_storage->loadDokterWaktu($id);
        return $data;
    }
    public function loadAntrianAdmin($idokter = null){
        $data = $this->_storage->loadAntrianAdmin($idokter);
        return $data;
    }
    public function loadAntrianUmum($id = null){
        $data = $this->_storage->loadAntrianUmum($id);
        return $data;
    }
    public function loadNoAntrian($id = null, $status = null){
        $data = $this->_storage->loadNoAntrian($id, $status);
        return $data;
    }
    
    public function loadAntrian($id_poli){
        $data = $this->_storage->loadAntrian($id_poli);
        return $data;
    }
    public function loadjumlahpasien(){
        $data = $this->_storage->loadjumlahpasien();
        return $data;
    }
    public function loadpasiendalamantrian(){
        $data = $this->_storage->loadpasiendalamantrian();
        return $data;
    }
    public function loadpasienterlewat(){
        $data = $this->_storage->loadpasienterlewat();
        return $data;
    }
    public function loadterlayani(){
        $data = $this->_storage->loadterlayani();
        return $data;
    }
    public function loadChart($id = null){
        $data = $this->_storage->loadChart($id);
        return $data;
    }
    public function  tampilpoli($id = null){
        $data = $this->_storage-> tampilpoli($id);
        return $data;
    }
    public function  tampildatapoli($id = null){
        $data = $this->_storage-> tampildatapoli($id);
        return $data;
    }
    public function tampildokter(){
        $data = $this->_storage->tampildokter();
        return $data;
    }
    public function deleteGlobal($tabel, $where){
        $data = $this->_storage->deleteGlobal($tabel, $where);
        return $data;
    }

    public function getMaxCounter(){
        $data = $this->_storage->loadMaxCounter();
        return $data;
    }
    public function getUserCounter($status = null, $iddok = null){
        $data = $this->_storage->loadUserCounter($status, $iddok);
        return $data;
    }
}
