<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id'; 
    public $timestamps = false; 
    protected $fillable = ['id_barang', 'jumlah_terjual', 'tanggal_transaksi']; 

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}

