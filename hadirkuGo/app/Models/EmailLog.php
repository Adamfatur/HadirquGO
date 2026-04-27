<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     */
    protected $table = 'email_logs';

    /**
     * Kolom yang dapat diisi (mass assignable).
     */
    protected $fillable = [
        'recipient', // Email tujuan
        'status',    // Status pengiriman (sent/failed)
        'sent_at',   // Waktu pengiriman email
    ];

    /**
     * Menonaktifkan timestamps jika tidak diperlukan.
     * Jika menggunakan kolom created_at & updated_at, biarkan ini kosong atau hapus.
     */
    public $timestamps = true;

    /**
     * Menambahkan akses atribut untuk nomor urut.
     */
    public function getLogNumberAttribute()
    {
        return $this->id; // Mengembalikan nomor urut (ID)
    }
}
