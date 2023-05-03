<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\Modelmember;
use App\Controllers\BaseController;

class member extends BaseController
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
    //     $Modelmember = new Modelmember();
    //     $data = $Modelmember->findAll();
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
        $Modelmember = new Modelmember();
        $data = $Modelmember->findAll();

        // foreach ($data as &$row) {
        //     $row['tanggal_lahir'] = $encryption->decrypt(hex2bin($row['tanggal_lahir']));
        // }

        $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
        ];

        return $this->respond($response, 200);
    }

    

    // public function show($id_member = null,$password = null,$category = null)
    // {
    //     if($category == "forgot"){
    //         $Modelmember = new Modelmember();
    //         $data = $Modelmember->where('id_member', $id_member)->get()->getResult();
    //         if (count($data) > 1) {
    //             $response = [
    //                 'status' => 200,
    //                 'error' => "false",
    //                 'message' => '',
    //                 'totaldata' => count($data),
    //                 'data' => $data,
    //             ];
    //             return $this->respond($response, 200);
    //         } else if (count($data) == 1) {
    //             $response = [
    //                 'status' => 200,
    //                 'error' => "false",
    //                 'message' => '',
    //                 'totaldata' => count($data),
    //                 'data' => $data,
    //             ];
    //             return $this->respond($response, 200);
    //         } else {
    //             return $this->failNotFound('maaf data ' . $id_member .
    //                 ' tidak ditemukan');
    //         }
    //     }else{
    //         $encryption = \Config\Services::encrypter();
    //         $Modelmember = new Modelmember();
    //         $data = $Modelmember->where('id_member', $id_member)->get()->getRow();
    //         if ($data && $password == $data->tanggal_lahir) {
    //             $encryption->decrypt(hex2bin($data->tanggal_lahir));
    //             $response = [
    //                 'status' => 200,
    //                 'error' => false,
    //                 'message' => '',
    //                 'totaldata' => 1,
    //                 'data' => $data,
    //             ];
    //             return $this->respond($response, 200);
    //         } else {
    //             return $this->failNotFound('Maaf, data ' . $id_member . ' tidak ditemukan atau password salah');
    //         }
    //     }
    // }
        public function show($id_member = null, $password = null, $category = null){
        if ($category == "forgot") {
            $Modelmember = new Modelmember();
            $data = $Modelmember->where('id_member', $id_member)->get()->getResult();
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
                return $this->failNotFound('maaf data ' . $id_member .
                    ' tidak ditemukan');
            }
        } else {
            $encryption = \Config\Services::encrypter();
            $Modelmember = new Modelmember();
            $data = $Modelmember->where('id_member', $id_member)->get()->getRow();
            if ($data) {
                if ($password == $data->Tanggal_lahir) {
                    // $encryption->decrypt(hex2bin($data->Tanggal_lahir));
                    $response = [
                        'status' => 200,
                        'error' => false,
                        'message' => '',
                        'totaldata' => 1,
                        'data' => $data,
                    ];
                    return $this->respond($response, 200);
                } else {
                    return $this->failNotFound('Maaf, data ' . $id_member . ' tidak ditemukan atau password salah');
                }
            } else {
                return $this->failNotFound('Maaf, data ' . $id_member . ' tidak ditemukan');
            }
        }
    }


    public function create()
    {
        $Modelmember = new Modelmember();
        $nama_member = $this->request->getPost("nama_member");
        $umur = $this->request->getPost("umur");
        // $encryption= \Config\Services::encrypter();
        // $tanggal_lahir = bin2hex($encryption->encrypt($this->request->getPost("tanggal_lahir"))); 
        // Generate Bcrypt hash of the password
        $Tanggal_lahir = $this->request->getPost("Tanggal_lahir");
        $email = $this->request->getPost("email");
        $deposit_uang = $this->request->getPost("deposit_uang");
        $deposit_kelas = $this->request->getPost("deposit_kelas");
        $Expired_Date = $this->request->getPost("Expired_Date");
        $Tanggal_Daftar = $this->request->getPost("Tanggal_Daftar");
        $status = $this->request->getPost("status");
        // $validation = \Config\Services::validation();
        // $valid = $this->validate([
        //     'id_member' => [
        //         'rules' => 'is_unique[akun.id_member]',
        //         'label' => 'id_member Akun',
        //         'errors' => [
        //             'is_unique' => "Akun {field} sudah ada"
        //         ]
        //     ]
        // ]);
        // if (!$valid) {
        //     $response = [
        //         'status' => 404,
        //         'error' => true,
        //         'message' => $validation->getError("id_member"),
        //     ];
        //     return $this->respond($response, 404);
        // } else {
            $Modelmember->insert([
                'nama_member' => $nama_member,
                'Tanggal_lahir'=> $Tanggal_lahir,
                'umur' => $umur,
                'email' => $email,
                'deposit_uang' => $deposit_uang,
                'deposit_kelas' => $deposit_kelas,
                'Tanggal_Daftar' => $Tanggal_Daftar,
                'Expired_Date' => $Expired_Date,
                'status' => $status
            ]);
            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Register Berhasil"
            ];
            return $this->respond($response, 201);
        // }
    }

    // public function update($id_member = null,$status = null)
    // {
    //     if($status == "verifikasi"){
    //         $model = new Modelmember();
    //         $data = [
    //             'nama_member' => $this->request->getVar("nama_member"),
    //             'Tanggal_lahir' => $this->request->getVar("Tanggal_lahir"),
    //             'email' => $this->request->getVar("email"),
    //             'deposit_uang' => $this->request->getVar("deposit_uang"),
    //             'deposit_kelas' => $this->request->getVar("deposit_kelas"),
    //             'Expired_Date' => $this->request->getVar("Expired_Date"),
    //             'status' => $this->request->getVar("status")
    //         ];
    //         $data = $this->request->getRawInput();
    //         $model->update($id_member, $data);
    //         $response = [
    //             'status' => 200,
    //             'error' => null,
    //             'message' => "Akun $id_member berhasil diupdate"
    //         ];
    //         return $this->respond($response, 201);
    //     }else{
    //         $model = new Modelmember();
    //         $data = [
    //             'nama_member' => $this->request->getVar("nama_member"),
    //             'Tanggal_lahir' => $this->request->getVar("Tanggal_lahir"),
    //             'email' => $this->request->getVar("email"),
    //             'deposit_uang' => $this->request->getVar("deposit_uang"),
    //             'deposit_kelas' => $this->request->getVar("deposit_kelas"),
    //             'Expired_Date' => $this->request->getVar("Expired_Date"),
    //             'status' => $this->request->getVar("status")
    //         ];
    //         $validation = \Config\Services::validation();
    //         // $valid = $this->validate([
    //         //     'id_member' => [
    //         //         'rules' => 'is_unique[akun.id_member]',
    //         //         'label' => 'id_member Akun',
    //         //         'errors' => [
    //         //             'is_unique' => "Akun {field} sudah ada"
    //         //         ]
    //         //     ]
    //         // ]);
    //         // if (!$valid) {
    //         //     $response = [
    //         //         'status' => 404,
    //         //         'error' => true,
    //         //         'message' => $validation->getError("id_member"),
    //         //     ];
    //         //     return $this->respond($response, 404);
    //         // } else {
    //             $data = $this->request->getRawInput();
    //             $model->update($id_member, $data);
    //             $response = [
    //                 'status' => 200,
    //                 'error' => null,
    //                 'message' => "Akun $id_member berhasil diupdate"
    //             ];
    //             return $this->respond($response, 201);
    //         // }
    //     }
        
    // }
    public function update($id_member = null,$status = null)
    {
    $model = new Modelmember();
        $encryption = \Config\Services::encrypter();
        $data = $this->request->getRawInput();
        // Update only non-empty values
        if (!empty($this->request->getVar("nama_member"))) {
            $data['nama_member'] = $this->request->getVar("nama_member");
        }
        if (empty($this->request->getVar("Tanggal_lahir"))) {
            $data['Tanggal_lahir'] = bin2hex($encryption->encrypt($data['Tanggal_lahir']));
        }
        if (!empty($this->request->getVar("umur"))) {
            $data['umur'] = $this->request->getVar("umur");
        }
        if (!empty($this->request->getVar("email"))) {
            $data['email'] = $this->request->getVar("email");
        }
        if (!empty($this->request->getVar("status"))) {
            $data['status'] = $this->request->getVar("status");
        }
        if (!empty($this->request->getVar("deposit_uang"))) {
            $data['deposit_uang'] = $this->request->getVar("deposit_uang");
        }
        if (!empty($this->request->getVar("deposit_kelas"))) {
            $data['deposit_kelas'] = $this->request->getVar("deposit_kelas");
        }
        if (!empty($this->request->getVar("Tanggal_Daftar"))) {
            $data['Tanggal_Daftar'] = $this->request->getVar("Tanggal_Daftar");
        }
        if (!empty($this->request->getVar("Expired_Date"))) {
            $data['Expired_Date'] = $this->request->getVar("Expired_Date");
        }
        $model->update($id_member, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Akun $id_member berhasil diupdate"
        ];
        return $this->respond($response, 201);
    }

    public function delete($id_member)
    {
        $Modelmember = new Modelmember();
        $cekData = $Modelmember->find($id_member);
        if ($cekData) {
            $Modelmember->delete($id_member);
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
