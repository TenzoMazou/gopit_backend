<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeljadwalharian extends Model
{
    protected $table = 'jadwalharian';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'jadwal','tanggal','status','jumlah_peserta'
    ];
}
