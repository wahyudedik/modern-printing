<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spesifikasi extends BaseModel
{
    protected $table = 'spesifikasis';

    protected $fillable = [
        'vendor_id',
        'nama_spesifikasi',
        'tipe_input',
        'satuan'
    ];

    const TIPE_INPUT = [
        'number' => 'number',
        'select' => 'select'
    ];

    protected $casts = [
        'nama_spesifikasi' => 'string',
        'tipe_input' => 'string',
        'satuan' => 'string'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function spesifikasiProduk()
    {
        return $this->hasMany(SpesifikasiProduk::class, 'spesifikasi_id');
    }

    public function isNumeric()
    {
        return $this->tipe_input === self::TIPE_INPUT['number'];
    }

    public function isText()
    {
        return $this->tipe_input === self::TIPE_INPUT['text'];
    }

    public function isSelect()
    {
        return $this->tipe_input === self::TIPE_INPUT['select'];
    }
}
