<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelpegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    protected $allowedFields = [
        'email','nama','password','umur','role','no_telp'
    ];
}
