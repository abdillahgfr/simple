<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoAset extends Model
{
    protected $table = 'foto_aset';
    protected $connection = 'sqlsrv_2'; 

    protected $fillable = [
        'id_asset', 'nama_file', 'is_utama', 'created_at'
    ];

    public $timestamps = false; // Karena tidak ada updated_at
}
