<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeljadwalumum extends Model
{
    protected $table = 'jadwal_umum';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'Jam','id_kelas','id_instruktur','hari'
    ];
}
