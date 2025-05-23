<?php

namespace App\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'nama',
        'nama_panggilan',
        'nipd',
        'kewarganegaraan',
        'bahasa_keseharian',
        'jk',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'telepon',
        'hp',
        'email',
        'skhun',
        'penerima_kps',
        'no_kps',
        'penerima_kip',
        'nomor_kip',
        'nama_di_kip',
        'nomor_kks',
        'no_registrasi_akta',
        'bank',
        'nomor_rekening',
        'rekening_atas_nama',
        'layak_pip',
        'alasan_layak_pip',
        'kebutuhan_khusus',
        'anak_ke',
        'no_kk',
        'jumlah_saudara_kandung',
        'saudara_tiri',
        'saudara_angkat',
        'status_anak',
        'jarak_rumah_km',
        'lintang',
        'bujur',
        'agama_id',
        'alat_transportasi_id',
        'jenis_tinggal_id',
        'riwayat_sekolah_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Uuid::uuid4()->toString();
            }
        });
    }


    public function agama()
    {
        return $this->belongsTo(Agama::class);
    }


    public function alatTransportasi()
    {
        return $this->belongsTo(AlatTransportasi::class, 'alat_transportasi_id');
    }

    public function jenisTinggal()
    {
        return $this->belongsTo(JenisTinggal::class, 'jenis_tinggal_id');
    }

    public function orangTuas()
    {
        return $this->hasMany(OrangTua::class, 'siswa_uuid', 'uuid');
    }

    public function riwayatSekolah()
    {
        return $this->hasOne(RiwayatSekolah::class, 'siswa_uuid', 'uuid');
    }

    public function studentRombels()
    {
        return $this->hasMany(StudentRombel::class, 'siswa_id', 'id');
    }
    public function currentRombel()
    {
        return $this->hasOne(StudentRombel::class, 'siswa_uuid', 'uuid')->latestOfMany();
    }

    public function getTanggalLahirFormattedAttribute()
    {
        return Carbon::parse($this->tanggal_lahir)->translatedFormat('F d, Y');
    }

    public function rombels()
    {
        return $this->hasMany(StudentRombel::class, 'siswa_uuid', 'uuid');
    }

    public function fotoTerbaru()
    {
        return $this->hasOne(FotoSiswa::class, 'siswa_uuid', 'uuid')->latestOfMany('created_at');
    }

    public function fotoSiswa()
    {
        return $this->hasMany(FotoSiswa::class, 'siswa_uuid', 'uuid')->orderByDesc('created_at');
    }

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function getTanggalLahirIndoAttribute()
    {
        return $this->tanggal_lahir
            ? \Carbon\Carbon::parse($this->tanggal_lahir)->translatedFormat('d F Y')
            : null;
    }

    public function dokumen()
    {
        return $this->hasMany(StudentDocument::class, 'student_id', 'id');
    }

    public function statusHistories()
    {
        return $this->hasMany(StudentStatus::class, 'student_uuid', 'uuid')->orderBy('tanggal', 'desc');
    }

    public function statusTerakhir()
    {
        return $this->hasOne(StudentStatus::class, 'student_uuid', 'uuid')->latestOfMany('tanggal');
    }

    protected static function booted()
    {
        static::created(function ($student) {
            $student->statusHistories()->create([
                'status' => 'aktif',
                'tanggal' => now(),
                'alasan' => 'Pendaftaran awal',
            ]);
        });
    }

    public function raporFiles()
    {
        return $this->hasMany(StudentRaporFile::class, 'student_uuid', 'uuid');
    }
}
