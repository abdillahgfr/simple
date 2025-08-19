<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanAset extends Model
{
    protected $table = 'permohonan_aset';
    protected $connection = 'sqlsrv_2';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'guid_aset', 'kolokskpd', 'kolok', 'nalok', 'kobar_108', 'nabar', 'noreg_108', 'bahan', 'merk', 'tipe',
        'harga', 'tgloleh', 'kondisi', 'deskripsi', 'penggunaan_bmd', 'alasan_permohonan', 'disetujui'
    ];

    public $timestamps = false;
}
