<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeljadwalharian extends Model
{
    protected $table = 'jadwal_harian';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'jadwal','tanggal_kelas','status_kelas', 'id_instruktur', 'instruktur_pengganti'
    ];
}
