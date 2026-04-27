<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nik',
        'phone',
        'photo',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'status_pekerjaan',       // ← ditambah
        'pendapatan_bulanan',     // ← ditambah
        'pendidikan_terakhir',    // ← ditambah
        'status_rumah',           // ← ditambah
        'kategori_sosial',        // ← ditambah
        'is_penerima_bantuan',    // ← ditambah
        'jumlah_tanggungan',      // ← ditambah
        'kepala_keluarga_nik',    // ← ditambah
        'alamat',
        'rt_rw',
        'kode_pos',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'    => 'datetime',
        'tanggal_lahir'        => 'date',
        'pendapatan_bulanan'   => 'integer',
        'is_penerima_bantuan'  => 'boolean',
        'jumlah_tanggungan'    => 'integer',
    ];

    // ========== HELPER FUNCTIONS ==========

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isWarga()
    {
        return $this->role === 'warga';
    }

    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return '-';
        if (strlen($this->phone) >= 10) {
            return substr($this->phone, 0, 4) . '-' . substr($this->phone, 4, 4) . '-' . substr($this->phone, 8);
        }
        return $this->phone;
    }

    public function getFormattedNikAttribute()
    {
        if (!$this->nik) return '-';
        if (strlen($this->nik) >= 12) {
            return substr($this->nik, 0, 4) . '-' . substr($this->nik, 4, 4) . '-' . substr($this->nik, 8, 4) . '-' . substr($this->nik, 12);
        }
        return $this->nik;
    }

    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) return '-';
        return $this->tanggal_lahir->age . ' tahun';
    }

    public function getAlamatLengkapAttribute()
    {
        $alamat = $this->alamat;
        if ($this->rt_rw) {
            $alamat .= ', RT/RW ' . $this->rt_rw;
        }
        if ($this->kode_pos) {
            $alamat .= ', ' . $this->kode_pos;
        }
        return $alamat ?: '-';
    }

    // ========== RELATIONS ==========

    public function pengajuanSurat()
    {
        return $this->hasMany(PengajuanSurat::class, 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function produkUmkm()
    {
        return $this->hasMany(ProdukUmkm::class, 'user_id');
    }
}
