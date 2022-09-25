<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


namespace Application\Controller;

use Application\Master;
use Zend\View\Model\ViewModel;
use Khansia\Generic\Result;
use ZfrPusher\Client\Credentials;
use ZfrPusher\Client\PusherClient;
use ZfrPusher\Service\PusherService;
use ZfrPusher\Exception\ExceptionInterface as PusherExceptionInterface;

class ApiController extends \Application\Master\GlobalActionController {

    public function __construct($headScript)
    {
        $this->headScript = $headScript;
    }
    
    public function loginAction(){

        $this->loadUseri();
        
        // generate token if valid user
        $payload = ['email' => 'danirsdan@gmail.com', 'name' => 'dani'];
    
        //print_r($payload);die;
        $this->apiResponse['token'] = $this->generateJwtToken($payload);

        $this->apiResponse['message'] = 'Logged in successfully.';
        return $this->createResponse();
    }
	
	public function testAction(){
        $result     = new Result();
        $data       = $this->getData();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Test\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Test($storage);

        $getDataRes = $test->getDataTest();

        $payload    = $this->tokenPayload;

        $result->guid = $this->generateJwtToken($payload);
        $result->code = $result::CODE_SUCCESS;
        $result->info = $result::INFO_SUCCESS;
        $result->data = $payload;

        return $this->getOutput($result->toJson());
    }

	public function savedatapasienAction(){
      
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();
                
            $table_pasien= 'pasien';

			if ($request->isPost()) {

                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);                

                $counter = $param->getMaxCounter();
				try{
                    $idpasien = $counter->data;
                    $nama    = $post->inama;
                    $tempat_lahir = $post->itempat_lahir;
                    $tanggal_lahir = $post->itanggal_lahir;
                    $alamat = $post->ialamat;
                    $no_hp = $post->ino_hp;
                    $ktp = $post->iktp;
                    $no_antrian = $post->ino_antrian;
                    $norm = $post->inorm;
                    
                    $dataArrayPasien =  array (
                        'id_pasien'     => $idpasien,
                        'create_date'   => gmdate("Y-m-d H:i:s", time()+60*60*7),
                        'nama'          => $nama,
                        'tempat_lahir'  => $tempat_lahir,
                        'tanggal_lahir' => $tanggal_lahir,
                        'alamat'        => $alamat,
                        'no_hp'         => $no_hp,
                        'ktp'           => $ktp,
                        'no_rekam_medis' => $norm,
                    );

                    $param->saveGlobal( $dataArrayPasien, $table_pasien);
                    
                   
                        $result->code = 0;
                        $result->info = 'ok';
                        $result->data = $dataArrayPasien;
                           
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
	
		}
		return $this->getOutput($result->toJson());
    }

    public function loaddatapasienAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        //  print_r($load);die;
        $load       = $test->loaddatapasien($post->id);

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }

    public function loadsisaantrianAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        //  print_r($load);die;
        $load       = $test->loadsisaantrian($post->id,$post->no);

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }

    public function cekpasienAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);
        
        $no_rekam_medis = $post->ino_rekam_medis;
        $ktp            = $post->iktp;

        $cekpasien      = $test->cekpasien($no_rekam_medis);
        
        $array = array(
            
            'datapasien'=> $cekpasien->data,
        );

        //  print_r( $cekpasien) ;die;
        
        if($cekpasien->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $array;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
	public function savedataAction(){
      
        $result 	= new Result();
        $request 	= $this->getRequest();
        $post 		= $request->getPost();
                
        if ($request->isPost()) {

            $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
            $param    	= new \Application\Model\Param($storage);
            
            try{
                $id = $post->iid;
                $nama    = $post->inama;
                $tempat_lahir = $post->itempat_lahir;
                $tanggal_lahir = $post->itanggal_lahir;
                $alamat = $post->ialamat;
                $no_hp = $post->ino_hp;
                $poli = $post->ipoli;
                $dokter = $post->idokter;
                $ktp = $post->iktp;
                $no_antrian = $post->ino_antrian;
                $no_rekam_medis = $post->ino_rekam_medis;
                $sisa_antrian = $post->isisa_antrian;
                    
                // $counter= $param->getMaxCounter();
                

                    $dataArrayAntrianPasien =  array (
                    'id_pasien'     => $id,
                    'nama'          => $nama,
                    'tempat_lahir'  => $tempat_lahir,
                    'tanggal_lahir' => $tanggal_lahir,
                    'alamat'        => $alamat,
                    'no_rekam_medis'=> $no_rekam_medis,
                    'no_hp'         => $no_hp,
                    'ktp'           => $ktp,
                    'status_code'   => 10,
                    'sisa_antrian'   => $sisa_antrian,
                );
                                    
                $table_antrianpasien       = 'antrian_pasien'; 
                $where                    = 'id_dokter='.$dokter.' and no_antrian='.$no_antrian;
        
                $register = $param->loadregisterantrian($where);
                
                $id_antrian = $register->data[0]['id_antrian']; 
                $whereidantrian = 'id_antrian='.$id_antrian;
                // print_r($register);die;

                $param->updateGlobal($table_antrianpasien, $dataArrayAntrianPasien , $whereidantrian);    
            
                $result->code = 0;
                $result->info = 'ok';
                $result->data = $register->data;
                        
            }catch (\Exception $exc) {
                $result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
            }
	
		}
		return $this->getOutput($result->toJson());
    }


    public function saveregisterAction(){
        $result 	= new Result();
        $request 	= $this->getRequest();
        $post 		= $request->getPost();
        
        $tb_register_antrian     ='register_antrian';
        $tb_register_dokter      ='register_dokter';
        $tb_dokter               ='dokter_to_poli';
        $tb_ruang                ='ruang';   
        $tb_antrian              ='antrian';
        $tb_antrian_pasien       ='antrian_pasien';

        if ($request->isPost()) {

            
            $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
            $param    	= new \Application\Model\Param($storage);
            $db     = $this->getDb();
            $conn     = $db->getDriver()->getConnection();
            $conn->beginTransaction();
    
            /* generate counter number */
            $counter = $param->getMaxCounter();

            try{
                $ruang    = $post->iruang;
                $poli = $post->ipoli;
                $dokter = $post->idokter;
                $antrian_all = $post->iantrian_all;
                $waktu_antrian = $post->iwaktu_antrian;
                $jam_mulai = $post->ijam_mulai;
                $estimasi_selesai = $post->iestimasi_selesai;
                // print_r($estimasi_selesai); die;

                $array = array();
             
                $seqBefore1     = $param->getLastSeqPostgree($tb_register_dokter ,'id_register');

             
                $dataArrayRegisterDokter=  array (
                    'create_date'   => gmdate("Y-m-d H:i:s", time()+60*60*7),
                    'id_ruang'    => $ruang,
                    'id_poli'     => $poli,
                    'id_dokter'  => $dokter,
                    'antrian_all'       => $antrian_all,
                    'waktu_antrian'    => $waktu_antrian,
                    'id_condition'     =>1,
                    'jam_mulai' => $jam_mulai,
                    'estimasi_selesai' => $estimasi_selesai,

                );

                $dataArrayRegisterAntrian=  array (
                    'create_date'   => gmdate("Y-m-d H:i:s", time()+60*60*7),
                    'antrian_all'       => $antrian_all,
                    'waktu_antrian'    => $waktu_antrian,
                    'jam_mulai' => $jam_mulai,
                );
                 
                $dataArrayDokter = array(
                     'id_condition' => 2,
                );

                $dataArrayRuang = array(
                    'id_condition' => 2,
                );


                if($dokter){
                   $array_merge = array_merge($dataArrayDokter, array('id_dokter=' => $dokter));
                 
                   $where = 'id_dokter='.$dokter;
                   $x           = $param->updateGlobal($tb_dokter, $dataArrayDokter, $where);
                    

                   $result->code = $result::CODE_SUCCESS; // code 0
                   $result->info = $result::INFO_SUCCESS;
                   $result->data = $dokter;
                }

                if($ruang){
                    $array_merge = array_merge($dataArrayDokter, array('id_ruang=' => $ruang));
                  
                    $where = 'id_ruang='.$ruang;
                    $x           = $param->updateGlobal($tb_ruang, $dataArrayRuang, $where);
                     

                    $result->code = $result::CODE_SUCCESS; // code 0
                    $result->info = $result::INFO_SUCCESS;
                    $result->data = $ruang;
                }
              

                // $dataArrayRegisterDokter=  array (
                //     'create_date'   => gmdate("Y-m-d H:i:s", time()+60*60*7),
                    
                //     'id_ruang'    => $ruang,
                //     'id_poli'     => $poli,
                //     'id_dokter'  => $dokter,
                //     'antrian_all'       => $antrian_all,
                //     'waktu_antrian'    => $waktu_antrian,
                //     'id_condition'     =>1,
                //     'jam_mulai' => $jam_mulai,
                //     'estimasi_selesai' => $estimasi_selesai,

                // );
                   
                // $dataArrayRegisterAntrian=  array (
                //     'create_date'   =>  gmdate("Y-m-d H:i:s", time()+60*60*7),
                //     'antrian_all'       => $antrian_all,
                //     'waktu_antrian'    => $waktu_antrian,
                //     'jam_mulai' => $jam_mulai,
                // );

                $param->saveGlobal($dataArrayRegisterDokter, $tb_register_dokter);
                $param->saveGlobal($dataArrayRegisterAntrian, $tb_register_antrian);
                        
           
                $seqAfter1     = $param->getLastSeqPostgree($tb_register_dokter, 'id_register');

    
                 if($seqAfter1->data['total']  > $seqBefore1->data['total']){

                    $seqBefore2 = $param->getLastSeqPostgree($tb_antrian_pasien,'id_antrian');
                    //untuk load data id register di table antrian register lalu menambahkan pada table pasien
                    
                        for ($noantrian=1; $noantrian <= $antrian_all;){
                            $dataArrayAntrianPasien =  array (
                                'id_register' => $seqAfter1->data['total'],
                                'create_date'   => gmdate("Y-m-d H:i:s", time()+60*60*7),
                                'no_antrian'    => $noantrian,
                                'id_poli'       => $poli,
                               
                                'id_dokter'    => $dokter,
                                'id_ruang'    => $ruang,
                                       
                                );
                                
                                $dataArrayAntrian =  array (
                                    'create_date'   => gmdate("Y-m-d H:i:s", time()+60*60*7),
                                    'no_antrian'    => $noantrian,
                                   
                                    
                                );
                                
                                $param->saveGlobal($dataArrayAntrianPasien, $tb_antrian_pasien);
                                $param->saveGlobal($dataArrayAntrian, $tb_antrian);
                                $noantrian++;
                            }
                        
                       
                              
                               
                               
                                
                        
                           

                    $seqAfter2 = $param->getLastSeqPostgree($tb_antrian_pasien, 'id_antrian');

                    if($seqAfter2->data['total'] > $seqBefore2->data['total']){
                            array_push($array ,  array('info'=>'sukses', 'id_antrian'=> $seqAfter1->data));
                    }else{
                        $conn->rollback();
                        array_push($array ,  array('info'=>'gagal antrian pasien'));
                    
                    }

                 }else{
                     $conn->rollback();
                     array_push($array ,  array('info'=>'gagal register dokter'));
                 }
                
               
                 //print_r($array);die;
                if('sukses' == $array[0]['info']) {
                    $conn->commit();
                    $result->code = 0;
                    $result->info = 'ok';
                    $result->data = array(
                        'id_register'=> $array[0]['id_antrian']['total'],
                        
                        
                    );
                }else{
                    $result->code = 1;
                    $result->info = 'gagal total';
                }

            }catch (\Exception $exc) {
                $result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
            }
        
        }
        return $this->getOutput($result->toJson());
    }

    public function loadantrianregisterAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        $load       = $test->loadantrianregister($post->id);
        // print_r($load);die;
        
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
   
    public function loadantrianregisterallAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        $load       = $test->loadantrianregisterall($post->id);
        // print_r($load);die;
        
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
   
    
    public function editdataregisterAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();
 
           
            $tb_register_antrian     ='register_antrian';
            $tb_register_dokter      ='register_dokter';
            $tb_antrian              ='antrian';
            $tb_antrian_pasien       ='antrian_pasien';             


			if ($request->isPost()) {


                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
               
             
				try{
                    $id_register = $post->id;
                    $antrian_all = $post->iantrial_all;
                    $waktu_antrian = $post->iwaktu_antrian;
                    $jam_mulai = $post->ijam_mulai;
                    $antrian_before = $post->iantrian_before;
                    $poli = $post->ipoli;
                    $dokter = $post->idokter;
                    $ruang = $post->iruang;
                    $tanggal_before = $post->itanggal_before;
                    $estimasi_selesai = $post->iestimasi_selesai;

                    // print_r($estimasi_selesai);die;

                    $array = array();
                   
                    $dataArrayRegister =  array (
                        
                       
                        'antrian_all'   =>$antrian_all,
                        'waktu_antrian' =>$waktu_antrian,
                        'jam_mulai'   =>$jam_mulai,
                        'estimasi_selesai'   =>$estimasi_selesai,
                        
                    );
                    $dataArrayAntrian =  array (
                        
                       
                        'antrian_all'   =>$antrian_all,
                        'waktu_antrian' =>$waktu_antrian,
                        'jam_mulai'   =>$jam_mulai,
                        
                      
                    );

                    $load       = $param->loadantrianregister($post->id);
                 
                  
                    $where            = 'id_register='.$id_register;
                    //  print_r ($where);die;
                    $param->updateGlobal($tb_register_dokter, $dataArrayRegister, $where);
                    
                    $where1            = 'id_registerantrian='.$id_register;
                    //  print_r ($where);die;
                    $param->updateGlobal($tb_register_antrian, $dataArrayAntrian, $where1);
            
                        $result->code = 0;
                        $result->info = 'ok';
                        $result->data = $load->data;
                    
                   //untuk load data id register di table antrian register lalu menambahkan pada table pasien
                    if($antrian_before < $antrian_all){
                       for ($antrian_before; $antrian_before <= $antrian_all;){
                           
                            $dataArrayAntrianPasien =  array (
                               'id_register' => $id_register,
                               'create_date'   => $tanggal_before,
                               'no_antrian'    => $antrian_before,
                               'id_poli'       => $poli,
                               'id_dokter'    => $dokter,
                               'id_ruang'    => $ruang,
                                      
                               );
                            //    print_r( $dataArrayAntrianPasien);die;
                               $dataArrayAntrian =  array (
                                   'create_date'   =>$tanggal_before,
                                   'no_antrian'    => $antrian_before,
                                  
                                   
                               );
                               
                               $param->saveGlobal($dataArrayAntrianPasien, $tb_antrian_pasien);
                               $param->saveGlobal($dataArrayAntrian, $tb_antrian);
                               $antrian_before++;
                               
                           }
                    } 
                    
                    if($antrian_all < $antrian_before){
                        for ($antrian_before; $antrian_before > $antrian_all ;){
                           //a
                            
                                $where     = "id_register=$id_register AND no_antrian=$antrian_before";

                        
                                
                                $param->deleteGlobal($tb_antrian_pasien, $where);
                                                
                             
                               $antrian_before--;
                           }
                           
                    }
                          
              
              
                //print_r($array);die;
              
                   $result->code = 0;
                   $result->info = 'ok';
                             
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }

    
    public function deletedataregisterAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        $tabel     = 'antrian_pasien';
        $where     = 'id_register='.$post->id;
        $res        = $test->deleteGlobal($tabel, $where);
        $tabel     = 'antrian';
        $where     = 'id_antrian='.$post->id;
        $res        = $test->deleteGlobal($tabel, $where);
        $tabel     = 'register_antrian';
        $where     = 'id_registerantrian='.$post->id;
        $res        = $test->deleteGlobal($tabel, $where);
        $tabel     = 'register_dokter';
        $where     = 'id_register='.$post->id;
        $res        = $test->deleteGlobal($tabel, $where);

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function loadkelolapoliAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        // print_r($post->id);die;
        $load       = $test->loadkelolapoli($post->id);
      
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function savekelolapoliAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();
            
            $table_poli= 'poli';
            $tabel  = 'user_data_header';
            
			if ($request->isPost()) {

                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
                
                try{
                    $fileupload      = $_FILES['fileupload']['tmp_name'];   
                    // print_r($fileupload);die;
                    $nama_poli    = $post->inama_poli;
                    $id_poli    = $post->id_poli;
                    $kode_poli = $post->ikode_poli;
                    $deskripsi_poli = $post->ideskripsi_poli;
                    $password = $post->ipassword;
                    
                    if (!empty($fileupload)){
                        $ImageName       = $_FILES['fileupload']['name'];
                        $tipes           = $_FILES['fileupload']['type'];
                        $size            = $_FILES['fileupload']['size'];

                        
                     
                        $uploaddir = './public/tamplate/img/poli/'; // directory file
                      
                        
                        $alamatfile    = $uploaddir.$ImageName;
                        if (move_uploaded_file($_FILES['fileupload']['tmp_name'],$alamatfile)){

                           
                          
                            // print_r('berhasil');die;
                            /* jika upload berhasil ke folder sever */
                            $dataAtt        = array(

                                'nama_poli' => $nama_poli,
                                'image_poli' => $ImageName,
                                'deskripsi_poli' => $deskripsi_poli,
                                'kode_poli'     => $kode_poli,
                                
                            );

                            $param->saveGlobal($dataAtt, $table_poli);

                            $seqAfter1     = $param->getLastSeqPostgree($table_poli, 'id_poli');
                            
                            $dataArray  = array (
                                'username'      => $nama_poli,
                                'password'      => md5($password),
                                'create_dtm'    => gmdate("Y-m-d H:i:s", time()+60*60*7),
                                'role'          => 10,
                                'status'        => 10,
                                'name'          => $nama_poli,
                                'id_poli'     => $seqAfter1->data['total'],
                               
                            );
                            // print_r( $dataArray);die;
                            
                            $param->saveGlobal($dataArray, $tabel);

                                $result->code = 0;
                                $result->info = 'ok';
                                $result->data = $dataArrayPoli->data;
                            
                            

                        }else{
                            $result->code = 17;
                            $result->info = 'FAILED UPLOAD FILE to SERVER';
                        }

                        
                    }else{
                        $result->info = 'File tidak boleh kosong';
                    }
                   
                           
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }
    

    public function editkelolapoliAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();
 
			if ($request->isPost()) {


                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
             
				try{
                    $fileupload      = $_FILES['fileupload']['tmp_name'];   
                    
                    $id_poli = $post->id_poli;
                    $password = $post->ipassword;
                    $iduser = $post->iduser;
                    // print_r($iduser);die;
                    $nama_poli    = $post->inama_poli;
                    $kode_poli = $post->ikode_poli;
                    $deskripsi_poli = $post->ideskripsi_poli;
                   
                    if (!empty($fileupload)){
                        $ImageName       = $_FILES['fileupload']['name'];
                        $tipes           = $_FILES['fileupload']['type'];
                        $size            = $_FILES['fileupload']['size'];


                     
                        $uploaddir = './public/tamplate/img/poli/'; // directory file
                      
                        
                        $alamatfile    = $uploaddir.$ImageName;
                       
                        if (move_uploaded_file($_FILES['fileupload']['tmp_name'],$alamatfile)){


                          

                            /* jika upload berhasil ke folder sever */
                            $dataArrayPoli       = array(

                                'nama_poli' => $nama_poli,
                                'image_poli' => $ImageName,
                                'deskripsi_poli' => $deskripsi_poli,
                                'kode_poli'     => $kode_poli,
                            );
                      
                            $load       = $param->loadkelolapoli($post->id_poli);
                            $table_poli = 'poli';
                            $where            = 'id_poli='.$id_poli;
                           
                           
                            $param->updateGlobal($table_poli, $dataArrayPoli, $where);

                            $dataArray  = array (
                                'username'      => $nama_poli,
                                'password'      => md5($password),
                                'create_dtm'    => gmdate("Y-m-d H:i:s", time()+60*60*7),
                                'role'          => 10,
                                'status'        => 10,
                                'name'          => $nama_poli,
                            );
                            // print_r( $dataArray);die;
                            $tabel  = 'user_data_header';
                            $where            = 'iduser='.$iduser;
                           
                           
                            $param->updateGlobal($table, $dataArray, $where);
                    
                                $result->code = 0;
                                $result->info = 'ok';
                              
                            

                        }else{
                            $result->code = 17;
                            $result->info = 'FAILED UPLOAD FILE to SERVER';
                        }

                        
                    }else{
                        
                        $dataArrayPoli       = array(

                            'nama_poli' => $nama_poli,
                            'deskripsi_poli' => $deskripsi_poli,
                            'kode_poli'     => $kode_poli,
                        );

                        $table_poli = 'poli';
                        $where            = 'id_poli='.$id_poli;
                        $param->updateGlobal($table_poli, $dataArrayPoli, $where);
                        $dataArray  = array (
                            'username'      => $nama_poli,
                            'password'      =>md5($password),
                            'create_dtm'    => gmdate("Y-m-d H:i:s", time()+60*60*7),
                            'role'          => 10,
                            'status'        => 10,
                            'name'          => $nama_poli,
                        );
                        // print_r( $dataArray);die;
                        $table  = 'user_data_header';
                        $where            = 'iduser='.$iduser;
                       
                       
                        $param->updateGlobal($table, $dataArray, $where);
                

                       }
                 
                                     
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }

    public function deletekelolapoliAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        $tabel1    = 'antrian_pasien';
        $where1     = 'id_poli='.$post->id;
        // print_r($where1);die;
        $res        = $test->deleteGlobal($tabel1, $where1);
        $tabel4    = 'user_data_header';
        $where4     = 'id_poli='.$post->id;
        // print_r($where1);die;
        $res        = $test->deleteGlobal($tabel4, $where4);
        
        $tabel     = 'dokter_to_poli';
        $where     = 'id_poli='.$post->id;
        $res        = $test->deleteGlobal($tabel, $where);
        $tabel2     = 'ruang';
        $where2     = 'id_poli='.$post->id;
        $res        = $test->deleteGlobal($tabel2, $where2);
        $tabel3     = 'poli';
        $where3    = 'id_poli='.$post->id;
        $res        = $test->deleteGlobal($tabel3, $where3);
       

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function loadkeloladokterAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        //  print_r($load);die;
        $load       = $test->loadkeloladokter($post->id);

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    
    public function savekeloladokterAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();
                
            $table_dokter_to_poli= 'dokter_to_poli';
            $table_dokter= 'dokter';
 
			if ($request->isPost()) {


                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
                
              
                
				try{

                    $fileupload      = $_FILES['fileupload']['tmp_name'];   
                  
                    $nama_dokter    = $post->inama_dokter;
                    $kode_dokter    = $post->ikode_dokter;
                    $poli           = $post->ipoli;
                  
  
                    if (!empty($fileupload)){
                        $ImageName       = $_FILES['fileupload']['name'];
                        $tipes           = $_FILES['fileupload']['type'];
                        $size            = $_FILES['fileupload']['size'];

                        
                     
                        $uploaddir = './public/tamplate/img/doctor/'; // directory file
                      
                        
                        $alamatfile    = $uploaddir.$ImageName;
                        if (move_uploaded_file($_FILES['fileupload']['tmp_name'],$alamatfile)){

                            // print_r('berhasil');die;
                            /* jika upload berhasil ke folder sever */
                          
                            $DokterPoli =  array (
                                
                                'nama_dokter'     => $nama_dokter,
                                'kode_dokter'     =>$kode_dokter,
                                'id_poli'         =>$poli,
                                'image_dokter'    => $ImageName,
                                'id_condition'    => 1,
                                'create_date'    => gmdate("Y-m-d H:i:s", time()+60*60*7),
                        
                            );

                            $Dokter =  array (
                                
                                'nama_dokter'     => $nama_dokter,
                                'kode_dokter'     =>$kode_dokter,
                                'image_dokter'    => $ImageName,
                            );


                            $param->saveGlobal($Dokter, $table_dokter);
                            $param->saveGlobal($DokterPoli, $table_dokter_to_poli);
                       
                                $result->code = 0;
                                $result->info = 'ok';
                                $result->data = $DokterPoli->data;

                        }else{
                            $result->code = 17;
                            $result->info = 'FAILED UPLOAD FILE to SERVER';
                        }

                        
                    }else{
                        $result->info = 'File tidak boleh kosong';
                    }
                   
                    //print_r($poli);die;                
                           
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }

    public function editkeloladokterAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();
 
			if ($request->isPost()) {


                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
             
				try{

                    
                    $fileupload      = $_FILES['fileupload']['tmp_name'];   
                    $id_dokter = $post->id_dokter;
                  
                    $nama_dokter     = $post->inama_dokter;
                    $kode_dokter     = $post->ikode_dokter;
                    $poli            =     $post->ipoli;
                  
  
                    if (!empty($fileupload)){
                        $ImageName       = $_FILES['fileupload']['name'];
                        $tipes           = $_FILES['fileupload']['type'];
                        $size            = $_FILES['fileupload']['size'];

                        
                     
                        $uploaddir = './public/tamplate/img/doctor/'; // directory file
                      
                        
                        $alamatfile    = $uploaddir.$ImageName;
                        if (move_uploaded_file($_FILES['fileupload']['tmp_name'],$alamatfile)){

                            // print_r('berhasil');die;
                            /* jika upload berhasil ke folder sever */
                          
                        $DokterPoli =  array (
                            
                            'nama_dokter'     => $nama_dokter,
                            'kode_dokter'     =>$kode_dokter,
                            'id_poli'         =>$poli,
                           
                            'image_dokter'    => $ImageName,
                        

                        
                        );
            

                        $Dokter =  array (
                            
                            'nama_dokter'     => $nama_dokter,
                            'kode_dokter'     =>$kode_dokter,
                            'image_dokter'    => $ImageName,
                        
                        );

                        $load       = $param->loadkeloladokter($post->id);
                    
                        $table_dokter_to_poli= 'dokter_to_poli';
                        $where            = 'id_dokter='.$id_dokter;
                        $param->updateGlobal($table_dokter_to_poli, $DokterPoli, $where);

                
                        $table_dokter= 'dokter';
                        $where            = 'id_dokter='.$id_dokter;
                        $param->updateGlobal($table_dokter, $Dokter, $where);
                
                            $result->code = 0;
                            $result->info = 'ok';
                            $result->data = $load->data;

                        }else{
                            $result->code = 17;
                            $result->info = 'FAILED UPLOAD FILE to SERVER';
                        }

                        
                    }else{
                        $DokterPoli =  array (
                            
                            'nama_dokter'     => $nama_dokter,
                            'kode_dokter'     =>$kode_dokter,
                            'id_poli'         =>$poli,
                        );

                        $Dokter =  array (
                            
                            'nama_dokter'     => $nama_dokter,
                            'kode_dokter'     =>$kode_dokter,
                    
                        );

                        $load       = $param->loadkeloladokter($post->id);
                    
                        $table_dokter_to_poli= 'dokter_to_poli';
                        $where            = 'id_dokter='.$id_dokter;
                        $param->updateGlobal($table_dokter_to_poli, $DokterPoli, $where);

                
                        $table_dokter= 'dokter';
                        $where            = 'id_dokter='.$id_dokter;
                        $param->updateGlobal($table_dokter, $Dokter, $where);
                
                            $result->code = 0;
                            $result->info = 'ok';
                    }
                  
  
                                     
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }


    public function deletekeloladokterAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);


        $tabel     = 'antrian_pasien';
        $where1     = 'id_dokter='.$post->id;
        // print_r($where);die;
        $res        = $test->deleteGlobal($tabel, $where1);
        
        $tabel_dokter_to_poli     = 'dokter_to_poli';
        $where     = 'id_dokter='.$post->id;
        
        
        $tabel_dokter     = 'dokter';
       
        $res_tabel_dokter_to_poli        = $test->deleteGlobal($tabel_dokter_to_poli, $where);
        $res_tabel_dokter                = $test->deleteGlobal($tabel_dokter, $where);

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }


    public function loadkelolaruangAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        //   print_r($load);die;
        $load       = $test->loadkelolaruang($post->id);

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }

    public function savekelolaruangAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();
                
            $table_ruang= 'ruang';
 
			if ($request->isPost()) {

                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
                
				try{
                    $nama_ruang    = $post->inama_ruang;
                    $lantai           = $post->ilantai;
                    $poli           = $post->ipoli;
                    //print_r($poli);die;
                    $dataArrayruang =  array (
                        
                        'nama_ruang'     =>$nama_ruang,
                        'lantai'        =>$lantai,
                        'id_poli'       =>$poli,
                        'id_condition'  => 1
                   );

                    //  print_r($dataArrayruang);die;
            
                    $param->saveGlobal($dataArrayruang, $table_ruang);
                    
               
                        $result->code = 0;
                        $result->info = 'ok';
                        $result->data = $dataArrayruang;
                   
                           
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }

    public function editkelolaruangAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();

			if ($request->isPost()) {


                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
            
				try{
                    $id_ruang = $post->id;
                    $nama_ruang    = $post->inama_ruang;
                    $poli           = $post->ipoli;
                    $lantai           = $post->ilantai;
                  
  
                    
                    
                    $dataArrayruang =  array (
                        
                        'nama_ruang'      =>$nama_ruang,
                        'id_poli'          =>$poli,
                        'lantai'          =>$lantai,
                    );
                    
                    $load       = $param->loadkelolaruang($post->id);
                    
                    //  print_r($post->id);die;
                    $table_ruang= 'ruang';
                    $where            = 'id_ruang='.$id_ruang;
                    // print_r ($where);die;
                     $param->updateGlobal($table_ruang, $dataArrayruang, $where);
            
                        $result->code = 0;
                        $result->info = 'ok';
                        $result->data = $load->data;
                   
                           
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }

    public function deletekelolaruangAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);


        $tabel1    = 'antrian_pasien';
        $where1     = 'id_ruang='.$post->id;
        // print_r($where);die;
        $res        = $test->deleteGlobal($tabel1, $where1);
       

      
        $tabel     = 'ruang';
        $where     = 'id_ruang='.$post->id;
  
        $res        = $test->deleteGlobal($tabel, $where);

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }

    public function loadantrianmissAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$n

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

       

     
        $load       = $test->loadantrianmiss($post->id);
        //  print_r($load);die;
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function loadantrianunregisAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$n

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

     
        $load       = $test->loadantrianunregis($post->id);
        //  print_r($load);die;
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }

    public function loadkelolapasienAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

       
        $load       = $test->loadkelolapasien($post->id_pasien);
        // print_r($load);die;
      
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }

    public function editkelolapasienAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();
 
			if ($request->isPost()) {


                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
             
				try{
                    $id_pasien = $post->id_pasien;
                    $nama_pasien    = $post->inama;
                    $ktp  = $post->iktp;
                    $tanggal_lahir = $post->itanggal_lahir;
                    $tempat_lahir = $post->itempat_lahir;
                    $alamat = $post->ialamat;
                    $no_hp = $post->ino_hp;

                    
  
                    $dataarraypasien =  array (
                        
                        'ktp'               => $ktp,
                        'nama'              =>$nama_pasien,
                        'tanggal_lahir'     =>$tanggal_lahir,
                        'tempat_lahir'      =>$tempat_lahir,
                        'alamat'            =>$alamat,
                        'no_hp'             =>$no_hp,          
                    );
                   

    

                    $load       = $param->loadkelolapasien($post->id_pasien);
                    
                    $table= 'pasien';
                    $where            = 'id_pasien='.$id_pasien;
                    // print_r($where);die;
                    $param->updateGlobal($table, $dataarraypasien, $where);
            
                        $result->code = 0;
                        $result->info = 'ok';
                        $result->data = $load->data;
                                     
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }

    public function deletekelolapasienAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        $tabel     = 'antrian_pasien';
        $where     = 'id_pasien='.$post->id_pasien;
        // print_r($where);die;
        $res        = $test->deleteGlobal($tabel, $where);

        $tabel     = 'pasien';
        $where     = 'id_pasien='.$post->id_pasien;
        $res        = $test->deleteGlobal($tabel, $where);
     


        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function updateconditiondokAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();

			if ($request->isPost()) {


                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
            
				try{
                    $id_dokter = $post->id_dokter;
                    $id_condition = $post->id_condition;
                    
                    // print_r( $id_dokter);die;
                    // print_r( $id_condition);die;
                    
                    if($id_condition == 1){
                        $datacondition =  array (
                            'id_condition' => 2,
                        );

                    }else{
                        $datacondition =  array (
                            'id_condition' => 1,
                        );
                    }
                    
                  
                    
                    //  print_r($post->id);die;
                    $tabledok= 'dokter_to_poli';
                    $where            = 'id_dokter='.$id_dokter;
                   
                     $param->updateGlobal($tabledok, $datacondition, $where);
            
                        $result->code = 0;
                        $result->info = 'ok';
                                           
                           
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }

    public function updateconditionruangAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();

			if ($request->isPost()) {


                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
            
				try{
                    $id_ruang = $post->id_ruang;
                    $id_condition = $post->id_condition;
                    
                    // print_r( $id_dokter);die;
                    // print_r( $id_condition);die;
                    
                    if($id_condition == 1){
                        $datacondition =  array (
                            'id_condition' => 2,
                        );

                    }else{
                        $datacondition =  array (
                            'id_condition' => 1,
                        );
                    }
   
                    //  print_r($post->id);die;
                    $tableruang= 'ruang';
                    $where            = 'id_ruang='.$id_ruang;
                   
                     $param->updateGlobal($tableruang, $datacondition, $where);
            
                        $result->code = 0;
                        $result->info = 'ok';
                                           
                           
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }

    public function updateconditionseluruhruangAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();

			if ($request->isPost()) {


                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
            
				try{
                    $id_ruang = $post->id_ruang;
                    $id_condition = $post->id_condition;

                    $datacondition =  array (
                        'id_condition' => 1,
                    );

                    $tableruang= 'ruang';

                    $where            = 'id_condition = 2';
                   
                    $param->updateGlobal($tableruang, $datacondition, $where);
            
                    $result->code = 0;
                    $result->info = 'ok';
                                           
                           
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }
    public function updateconditionseluruhdokterAction(){
        if($this->isLoggedIn()){
			$result 	= new Result();
			$request 	= $this->getRequest();
            $post 		= $request->getPost();

			if ($request->isPost()) {


                $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
                $param    	= new \Application\Model\Param($storage);
            
				try{
                    $id_ruang = $post->id_ruang;
                    $id_condition = $post->id_condition;

                    $datacondition =  array (
                        'id_condition' => 1,
                    );

                    $tableruang= 'dokter_to_poli';

                    $where            = 'id_condition = 2';
                   
                    $param->updateGlobal($tableruang, $datacondition, $where);
            
                    $result->code = 0;
                    $result->info = 'ok';
                                           
                           
				}catch (\Exception $exc) {
					$result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
				}
			}else{
				$result = new Result(0,401,'Silahkan masuk untuk melanjutkan');
			}
		}
		return $this->getOutput($result->toJson());
    }

    
    public function loadpasienAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        // print_r($post->no_rekam_medis);die;

        $load       = $test->loadpasien($post->id, $post->no_rekam_medis);
      
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }

    public function loadRegisterEditAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $poli   	= new \Application\Model\Param($storage);
        
        
        $load       = $poli->loadRegisterPoli();
        $loadc      = $poli->loadCondition();        

        $listdata= array();

        $listdata = array (
            'poli'      =>$load->data,
            'condition' =>$loadc->data
        );
        // print_r($listdata);die;

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $listdata;
        }else{
            $result->code = 1;
            $result->info = 'Not Found poli';
        }

        return $this->getOutput($result->toJson());
    }


    public function loadRegisterPoliAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $poli   	= new \Application\Model\Param($storage);
        
        
        $load       = $poli->loadRegisterPoli();
        

     
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found poli';
        }

        return $this->getOutput($result->toJson());
    }
    
    
    public function loadRegisterDokterAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $dokter   	= new \Application\Model\Param($storage);
        
        
        $load       = $dokter->loadRegisterDokter($post->id_poli);

     
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found dokter';
              

        }

        return $this->getOutput($result->toJson());
    }


    public function loadRegisterRuangAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $ruang   	= new \Application\Model\Param($storage);
        
        
        $load       = $ruang->loadRegisterRuang($post->id_poli);

     
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }

    public function loadAntrianAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);


     
        // $id         = $this->_getParam($test->loadAntrian($post->id_poli));
      
        $load       = $test->loadAntrian($post->id_poli);
        // print_r($post->id_poli);die;

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
           
        }
        else{
            $result->code = 1;
            $result->info = 'Not Found';
              
        }

        return $this->getOutput($result->toJson());
    }

    public function loadConditionAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $poli   	= new \Application\Model\Param($storage);
        
        
        $load       = $poli->loadCondition();

     
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found poli';
        }

        return $this->getOutput($result->toJson());
    }

    public function loadPoliAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $poli   	= new \Application\Model\Param($storage);
        
        
        $load       = $poli->loadPoli($post->$id_poli);
// print_r($post->$id_poli);die;

        
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    
    public function loadDokterAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $dokter   	= new \Application\Model\Param($storage);
        
        // print_r($post->id_poli);die;
        $load       = $dokter->loadDokter($post->id_poli);

     
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function loadDokterWaktuAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $dokter   	= new \Application\Model\Param($storage);
        
        // print_r($post->id_poli);die;
        $load       = $dokter->loadDokterWaktu($post->id_poli);

     
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
   
    public function loadPilihAntrianAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $dokter   	= new \Application\Model\Param($storage);
        
        

        $iddokter = $post->iddok;
        $id_poli = $post->id_poli;
      
        // print_r($id_pasien);die;
        $load       = $dokter->loadPilihAntrian($iddokter, $id_poli);

        $listdata = array();

        foreach($load->data as $val){

            array_push($listdata, $val);

        }
        // print_r($listdata);die;
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $listdata;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function valpilihAntrianAction(){
        $result = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $dokter   	= new \Application\Model\Param($storage);
        
        


        $id_pasien = $post->id_pasien;
        // print_r($id_pasien);die;
        $load       = $dokter->valpilihAntrian($id_pasien);

       
        // print_r($listdata);die;
        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }

    public function LoadAntrianAdminAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);
    
        $idokter = $post->iddok;

        $loadAntrianAdmin           = $test->loadAntrianAdmin($idokter);
        // print_r( $idokter);die;

       
        
        if($loaddokter->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $loadAntrianAdmin->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function LoadNoAntrianAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);
    
        // $idokter = $post->idokter;

        $loadNoAntrian         = $test->loadNoAntrian($post->id_dokter, $post->istatus);
             
        if($loaddokter->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $loadNoAntrian->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }

    public function nextantrianAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
        $post 		= $request->getPost();

// First, run 'composer require pusher/pusher-php-server'
        

        if ($request->isPost()) {

            $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
            $test    	= new \Application\Model\Param($storage);     
                       
        
            try{
             
                $noantrian = $post->inoantrian;
                $iddok = $post->idok;
                $status = $post->istatus;
              

                if($status == 30){
                    require  'C:/laragon/www/queue/load.php';
                }else if($status == 60){
                    require  'C:/laragon/www/queue/load.php';
                }else if($status == 50){
                    require  'C:/laragon/www/queue/load.php';
                }
                
                $dataarray = array(
                    'status_code'   => $status,        
                );

                            
                $loadAntrianAdmin     = $test->loadAntrianAdmin($iddok);

                $table = 'antrian_pasien'; 
                $where = 'id_dokter='.$iddok.' and no_antrian='.$noantrian;
                $test->updateGlobal($table, $dataarray, $where);

                

                if($load->code == 0){
                    $result->code = $result::CODE_SUCCESS;
                    $result->info = $result::INFO_SUCCESS;
                    $result->data = $loadAntrianAdmin->data;
                
                }else{
                    $result->code = 1;  
                    $result->info = 'Not Found';
                }
            
            
                       
            }catch (\Exception $exc) {
                $result = new Result(0,1,$exc->getMessage() .'-'.$exc->getTraceAsString());
            }
        }

        return $this->getOutput($result->toJson());
    }

    public function updateantrianmissAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        
        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

    

        $iddok = $post->iddok;
        $idpasien= $post->iidpasien;

        $dataarray = array(
            'status_code'   => 10,        
        );
            
        $loadAntrianAdmin     = $test->updatekelolaantrian($idpasien,$iddok);

            $table = 'antrian_pasien'; 
            $where = 'id_dokter='.$iddok.' and id_pasien='.$idpasien;
            $test->updateGlobal($table, $dataarray, $where);

            // print_r($where);
            
        if($loaddokter->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $loadAntrianAdmin->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function updateunregisterAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        
        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

    

       
        $id_pasien= $post->id_pasien;

        $dataarray = array(
            'id_pasien'     =>  null,
            'nama'          =>  null,
            'tempat_lahir'  =>  null,
            'tanggal_lahir' =>  null,
            'alamat'        =>  null,
            'no_rekam_medis'=>  null,
            'no_hp'         =>  null,
            'ktp'           =>  null,
            'status_code'   =>  null,
        );
            
        // $loadunreg     = $test->loadantrianunregis($id_pasien);

            $table = 'antrian_pasien'; 
            $where = 'id_pasien='.$id_pasien;
            $test->updateGlobal($table, $dataarray, $where);

            // print_r($where);
            
        if($loaddokter->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $dataarray->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    
    public function tampildokterAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);


        $loaddokter           = $test->tampildokter();

        // print_r($loaddokter);die;


        if($loaddokter->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $loaddokter->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }    
    public function tampilpoliAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);


        $loadpoli           = $test->tampilpoli($post->id);

  


        if($loaddokter->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $loadpoli->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }    
    public function tampildatapoliAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);


        $loaddatapoli           = $test->tampildatapoli($post->id);
        // print_r($loaddatapoli);die;
  


        if($loaddokter->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $loaddatapoli->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }    
    public function loadjumlahpasienAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
        //$name       = $this->isFieldMandatory(@$data['name'], 'name');

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        
        $load       = $test->loadjumlahpasien();

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function loadpasiendalamantrianAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
     

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        
        $load       = $test->loadpasiendalamantrian();

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function loadpasienterlewatAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
     

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        
        $load       = $test->loadpasienterlewat();
        // print_r($load);die;

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function loadterlayaniAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
     

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        
        $load       = $test->loadterlayani();
        // print_r($load);die;

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
    public function loadChartAction(){
        $result     = new Result();
        $request 	= $this->getRequest();
		$post 		= $request->getPost();
        
     

        $storage 	= \Application\Model\Param\Storage::factory($this->getDb(), $this->getConfig());
        $test    	= new \Application\Model\Param($storage);

        

        $load       = $test->loadChart($post->id);
        // print_r($load);die;

        if($load->code == 0){
            $result->code = $result::CODE_SUCCESS;
            $result->info = $result::INFO_SUCCESS;
            $result->data = $load->data;
        }else{
            $result->code = 1;
            $result->info = 'Not Found';
        }

        return $this->getOutput($result->toJson());
    }
}