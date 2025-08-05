<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentifikasiAset extends Model
{
    protected $table = 'identifikasi_aset';
    protected $connection = 'sqlsrv_2';

    protected $primaryKey = 'guid_aset';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'guid_aset', 'kolokskpd', 'kolok', 'nalok', 'kobar_108', 'nabar', 'noreg_108', 'bahan', 'merk', 'tipe',
        'harga', 'tgloleh', 'kondisi', 'deskripsi', 'penggunaan_bmd', 'image', 'image2', 'image3', 'image4', 'image5', 'main_image', 'jumlah_dilihat', 'validasi_kepalaskpd'
    ];

    public $timestamps = false;
}
