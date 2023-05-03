<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelinstruktur extends Model
{
    protected $table = 'instruktur';
    protected $primaryKey = 'id_instruktur';
    protected $allowedFields = [
        'email','nama','password','umur'
    ];
}
