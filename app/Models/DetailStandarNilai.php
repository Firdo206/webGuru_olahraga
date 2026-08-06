<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailStandarNilai extends Model
{
    use HasFactory;

    protected $table = 'detail_standar_nilais';
    protected $fillable = ['standar_nilai_id', 'grade', 'minimal', 'maksimal'];

    public function standarNilai()
    {
        return $this->belongsTo(StandarNilai::class, 'standar_nilai_id');
    }
}