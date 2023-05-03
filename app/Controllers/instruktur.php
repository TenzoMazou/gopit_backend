<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\Modelinstruktur;
use App\Controllers\BaseController;

class instruktur extends BaseController
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
    //     $Modelinstruktur = new Modelinstruktur();
    //     $data = $Modelinstruktur->findAll();
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
        $Modelinstruktur = new Modelinstruktur();
        $data = $Modelinstruktur->findAll();

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

    public function show($email = null,$password = null,$category = null)
    {
        if($category == "forgot"){
            $Modelinstruktur = new Modelinstruktur();
            $data = $Modelinstruktur->where('email', $email)->get()->getResult();
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
                return $this->failNotFound('maaf data ' . $email . ' tidak ditemukan');
            }
        }else{
            $encryption = \Config\Services::encrypter();
            $Modelinstruktur = new Modelinstruktur();
            $data = $Modelinstruktur->where('email', $email)->get()->getRow();
            $passworddecrypt = $encryption->decrypt(hex2bin($data->password));
            if ($data && $password == $passworddecrypt) {
                $data->password = $encryption->decrypt(hex2bin($data->password));
                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => '',
                    'totaldata' => 1,
                    'data' => $data,
                ];
                return $this->respond($response, 200);
            } else {
                return $this->failNotFound('Maaf, data ' . $email . ' tidak ditemukan atau password salah');
            }
        }
    }
    

    public function create()
    {
        $Modelinstruktur = new Modelinstruktur();
        $nama = $this->request->getPost("nama");
        $email = $this->request->getPost("email");
        $encryption= \Config\Services::encrypter();
        $password = bin2hex($encryption->encrypt($this->request->getPost("password"))); 
        // Generate Bcrypt hash of the password
        $umur = $this->request->getPost("umur");

        $Modelinstruktur->insert([
            'nama' => $nama,
            'email' => $email,
            'password'=> $password,
            'umur' => $umur,
        ]);
        $response = [
            'status' => 201,
            'error' => "false",
            'message' => "Register Berhasil"
        ];
        return $this->respond($response, 201);
    }

    public function update($id = null)
    {
        $model = new Modelinstruktur();
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
        $Modelinstruktur = new Modelinstruktur();
        $cekData = $Modelinstruktur->find($nama);
        if ($cekData) {
            $Modelinstruktur->delete($nama);
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
