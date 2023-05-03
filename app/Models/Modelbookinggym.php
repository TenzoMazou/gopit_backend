<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbookinggym extends Model
{
    protected $table = 'bookinggym';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_member','tanggal','jam_masuk','jam_keluar'
    ];
}