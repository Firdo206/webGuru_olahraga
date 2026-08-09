<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiTes extends Model
{
    use HasFactory;

    protected $table = 'sesi_tes';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'jenis_olahraga_id',
        'tanggal',
        'waktu_mulai',
        'waktu_berakhir',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function jenisOlahraga()
    {
        return $this->belongsTo(JenisOlahraga::class, 'jenis_olahraga_id');
    }

    public function hasilTes()
    {
        return $this->hasMany(HasilTes::class, 'sesi_tes_id');
    }
    public function syncStatusWaktu(): void
    {
        if ($this->status === 'selesai') {
            return;
        }

        $tanggal = $this->tanggal instanceof \Carbon\Carbon
            ? $this->tanggal->format('Y-m-d')
            : $this->tanggal;

        $now     = Carbon::now();
        $mulai   = Carbon::parse($tanggal . ' ' . $this->waktu_mulai);
        $selesai = Carbon::parse($tanggal . ' ' . $this->waktu_berakhir);

        $statusBaru = $this->status;

        if ($now->gte($selesai)) {
            $statusBaru = 'selesai';
        } elseif ($now->gte($mulai)) {
            $statusBaru = 'aktif';
        }

        if ($statusBaru !== $this->status) {
            $this->update(['status' => $statusBaru]);
        }
    }
    public static function syncSemuaStatus(): void
    {
        static::where('status', '!=', 'selesai')
            ->get()
            ->each(fn (SesiTes $s) => $s->syncStatusWaktu());
    }
}