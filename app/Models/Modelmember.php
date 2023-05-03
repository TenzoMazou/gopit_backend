<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelmember extends Model
{
    protected $table = 'member';
    protected $primaryKey = 'id_member';
    protected $allowedFields = [
        'nama_member','umur','email','Tanggal_lahir','deposit_uang','deposit_kelas','Expired_Date','status', 'Tanggal_Daftar'
    ];
}
