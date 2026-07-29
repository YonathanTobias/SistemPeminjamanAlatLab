<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanLab extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function alat()
    {
        return $this->belongsTo(AlatLab::class, 'alat_lab_id');
    }
}