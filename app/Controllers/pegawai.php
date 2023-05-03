<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\Modelpegawai;
use App\Controllers\BaseController;

class pegawai extends BaseController
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
    //     $Modelpegawai = new Modelpegawai();
    //     $data = $Modelpegawai->findAll();
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
        $Modelpegawai = new Modelpegawai();
        $data = $Modelpegawai->findAll();

        foreach ($data as &$row) {
            $row['password'] = $encryption->decrypt(hex2bin($row['password']));
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

    public function show($email = null, $password = null,$category = null)
    {
        if($category == "forgot"){
            $Modelpegawai = new Modelpegawai();
            $data = $Modelpegawai->where('email', $email)->get()->getResult();
            if (count($data) > 0) { // Update condition to check if data is not empty
                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => '',
                    'totaldata' => count($data),
                    'data' => $data, // Return $data directly
                ];
                return $this->respond($response, 200);
            } else {
                return $this->failNotFound('maaf data ' . $email . ' tidak ditemukan');
            }
        } else {
            $encryption = \Config\Services::encrypter();
            $Modelpegawai = new Modelpegawai();
            $data = $Modelpegawai->where('email', $email)->get()->getRow();
            $passworddecrypt = $encryption->decrypt(hex2bin($data->password));
            if ($data && $password == $passworddecrypt) {
                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => '',
                    'totaldata' => 1,
                    'data' => $data, // Wrap $data in an array
                ];
                return $this->respond($response, 200);
            } else {
                return $this->failNotFound('Maaf, data ' . $email . ' tidak ditemukan atau password salah');
            }
        }
    }
    

    public function create()
    {
        $Modelpegawai = new Modelpegawai();
        $nama = $this->request->getPost("nama");
        $email = $this->request->getPost("email");
        $encryption= \Config\Services::encrypter();
        $password = bin2hex($encryption->encrypt($this->request->getPost("password"))); 
        // Generate Bcrypt hash of the password
        $umur = $this->request->getPost("umur");
        $role = $this->request->getPost("role");
        // $validation = \Config\Services::validation();
        // $valid = $this->validate([
        //     'nama' => [
        //         'rules' => 'is_unique[akun.nama]',
        //         'label' => 'nama Akun',
        //         'errors' => [
        //             'is_unique' => "Akun {field} sudah ada"
        //         ]
        //     ]
        // ]);
        // if (!$valid) {
        //     $response = [
        //         'status' => 404,
        //         'error' => true,
        //         'message' => $validation->getError("nama"),
        //     ];
        //     return $this->respond($response, 404);
        // } else {
            $Modelpegawai->insert([
                'nama' => $nama,
                'email' => $email,
                'password'=> $password,
                'umur' => $umur,
                'role' => $role
            ]);
            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Register Berhasil"
            ];
            return $this->respond($response, 201);
        // }
    }

    public function update($id = null)
    {
        $model = new Modelpegawai();
        $encryption = \Config\Services::encrypter();
        $data = $this->request->getRawInput();
        // Update only non-empty values
        if (!empty($this->request->getVar("nama"))) {
            $data['nama'] = $this->request->getVar("nama");
        }
        if (!empty($this->request->getVar("email"))) {
            $data['email'] = $this->request->getVar("email");
        }
        if (empty($this->request->getVar("password"))) {
            $data['password'] = bin2hex($encryption->encrypt($data['password']));
        }
        if (!empty($this->request->getVar("umur"))) {
            $data['umur'] = $this->request->getVar("umur");
        }
        if (!empty($this->request->getVar("role"))) {
            $data['role'] = $this->request->getVar("role");
        }
        $model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Akun $id berhasil diupdate"
        ];
        return $this->respond($response, 201);
    }


    public function delete($nama)
    {
        $Modelpegawai = new Modelpegawai();
        $cekData = $Modelpegawai->find($nama);
        if ($cekData) {
            $Modelpegawai->delete($nama);
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