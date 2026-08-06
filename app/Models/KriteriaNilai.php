<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KriteriaNilai extends Model
{
    protected $table = 'kriteria_nilai';

    protected $fillable = ['sesi_tes_id', 'batas_waktu', 'nilai'];

    public function sesiTes(): BelongsTo
    {
        return $this->belongsTo(SesiTes::class);
    }
}