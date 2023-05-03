<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\Modeljadwalumum;
use App\Models\Modelinstruktur;
use App\Models\Modelkelas;
use App\Controllers\BaseController;

class jadwalumum extends BaseController
{
    use ResponseTrait;
    private $encrypt;
    private $bcrypt;
    public function __construct()
    {
        // Initialize the $encrypt property in the constructor
        $this->encrypt = \Config\Services::encrypter();
        $this->bcrypt = \Config\Services::bcrypt();
    }
    // public function index()
    // {
    //     $Modeljadwalumum = new Modeljadwalumum();
    //     $data = $Modeljadwalumum->findAll();
    //     $response = [
    //         'status' => 200,
    //         'error' => "false",
    //         'message' => '',
    //         'totaldata' => count($data),
    //         'data' => $data,
    //     ];
    //     return $this->respond($response, 200);
    // }
    

    public function index()
    {
        $encryption = \Config\Services::encrypter();
        $Modeljadwalumum = new Modeljadwalumum();
        // $data = $Modeljadwalumum->select('jadwalumum as u, instruktur as i')->
        // join('u.id_instruktur =  i.id_instruktur')->findAll();
        $data = $Modeljadwalumum->select('jadwal_umum.*, instruktur.nama , kelas.nama_kelas')
                                ->join('instruktur', 'jadwal_umum.id_instruktur = instruktur.id_instruktur')
                                ->join('kelas', 'jadwal_umum.id_kelas = kelas.id_kelas')
                                ->orderBy("CASE jadwal_umum.hari 
                                            WHEN 'senin' THEN 1 
                                            WHEN 'selasa' THEN 2 
                                            WHEN 'rabu' THEN 3 
                                            WHEN 'kamis' THEN 4 
                                            WHEN 'jumat' THEN 5 
                                            WHEN 'sabtu' THEN 6 
                                            WHEN 'minggu' THEN 7 
                                            ELSE 8 END")
                                ->findAll();

        foreach ($data as &$row) {
            $row['id_instruktur'] = $row['nama'];
            $row['id_kelas'] = $row['nama_kelas'];
            unset($row['id_instruktur'], $row['id_kelas']);
        }
                        
        $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
        ];

        return $this->respond($response, 200);
    }

    public function show($id_jadwalumum = null,$password = null,$category = null)
    {
        $Modeljadwalumum = new Modeljadwalumum();

        // $data = $Modeljadwalumum->where('id', $id_jadwalumum)->get()->getResult();
        $data = $Modeljadwalumum->select('jadwal_umum.*, instruktur.nama , kelas.nama_kelas')
                                ->join('instruktur', 'jadwal_umum.id_instruktur = instruktur.id_instruktur')
                                ->join('kelas', 'jadwal_umum.id_kelas = kelas.id_kelas')
                                // ->orderBy('jadwal_umum.hari', 'ASC')
                                ->where('id', $id_jadwalumum)->get()->getResult();

                                $data = (array) $data;

                                foreach ($data as &$row) {
                                    $row->id_instruktur = $row->nama;
                                    $row->id_kelas = $row->nama_kelas;
                                    unset($row->id_instruktur, $row->id_kelas);
                                }

                                
        if (count($data) > 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
            return $this->respond($response, 200);
        } else if (count($data) == 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
                return $this->respond($response, 200);
        } else {
            return $this->failNotFound('maaf data ' . $id_jadwalumum . ' tidak ditemukan');
        }
    }
    

    // public function create()
    // {
    //     $Modeljadwalumum = new Modeljadwalumum();
    //     $tanggal_kelas = $this->request->getPost("Tanggal");
    //     $jam = $this->request->getPost("Jam");
    //     $nama = $this->request->getPost("nama");
    //     $nama_kelas = $this->request->getPost("nama_kelas");

    //     $Modelinstruktur = new Modelinstruktur();
    //     $instruktur = $Modelinstruktur->where('nama', $nama)->first();
    //     // $id_instruktur = $instruktur['id_instruktur'];
    //     if ($instruktur === null) {
    //         $response = [
    //             'status' => 404,
    //             'error' => "true",
    //             'message' => "Instruktur not found"
    //         ];
    //         return $this->respond($response, 404);
    //     }
    //     $id_instruktur = $instruktur['id_instruktur'];
   
    //     $Modelkelas = new Modelkelas();
    //     $kelas = $Modelkelas->where('nama_kelas', $nama_kelas)->first();
    //     $id_kelas = $kelas['id'];
    //         $Modeljadwalumum->insert([
    //             'Tanggal' => $tanggal_kelas,
    //             'Jam'=> $jam,
    //             'id_instruktur' => $id_instruktur,
    //             'id_kelas' => $id_kelas
    //         ]);
    //         $response = [
    //             'status' => 201,
    //             'error' => "false",
    //             'message' => "Register Berhasil"
    //         ];
    //         return $this->respond($response, 201);
    // }

    public function create()
    {
        $Modeljadwalumum = new Modeljadwalumum();
        $hari = $this->request->getPost("hari");
        $Jam = $this->request->getPost("Jam");
        $nama = $this->request->getPost("nama");
        $nama_kelas = $this->request->getPost("nama_kelas");

        $Modelinstruktur = new Modelinstruktur();
        $instruktur = $Modelinstruktur->where('nama', $nama)->first();
        $id_instruktur = null;
        // $id_instruktur = $instruktur['id_instruktur'];
        if ($instruktur !== null) {
            $id_instruktur = $instruktur['id_instruktur'];
        }

        $Modelkelas = new Modelkelas();
        $kelas = $Modelkelas->where('nama_kelas', $nama_kelas)->first();
        // $id_kelas = $kelas['id_kelas'];
        $id_kelas = null;
        if ($kelas !== null) {
            $id_kelas = $kelas['id_kelas'];
        }
            $Modeljadwalumum->insert([
                'hari' => $hari,
                'Jam'=> $Jam,
                'id_instruktur' => $id_instruktur,
                'id_kelas' => $id_kelas
            ]);

            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Register Berhasil"
            ];
            return $this->respond($response, 201);
    }

    public function update($id = null, $id_jadwalumum = null, $status = null)
    {
        $model = new Modeljadwalumum();
        $data = $this->request->getJSON(true);
        $nama = $this->request->getVar("nama");
        $nama_kelas = $this->request->getVar("nama_kelas");

        $Modelinstruktur = new Modelinstruktur();
        $instruktur = $Modelinstruktur->where('nama', $nama)->first();
        if ($instruktur === null) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => 'Gagal',
            ];
            return $this->respond($response, 200);
        }
        $id_instruktur = $instruktur['id_instruktur'];

        $Modelkelas = new Modelkelas();
        $kelas = $Modelkelas->where('nama_kelas', $nama_kelas)->first();
        $id_kelas = $kelas['id_kelas'];
        
        $data['hari'] = $this->request->getVar("hari");
        $data['Jam'] = $this->request->getVar("Jam");
        $data['id_kelas'] = $id_kelas;
        $data['id_instruktur'] = $id_instruktur;
        $model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Done"
        ];
        return $this->respond($response, 201);
        
    }

    public function delete($id_jadwalumum)
    {
        $Modeljadwalumum = new Modeljadwalumum();
        $cekData = $Modeljadwalumum->find($id_jadwalumum);
        if ($cekData) {
            $Modeljadwalumum->delete($id_jadwalumum);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Selamat data sudah berhasil dihapus maksimal"
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }
}
