<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelkelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_kelas','tarif'
    ];
}
