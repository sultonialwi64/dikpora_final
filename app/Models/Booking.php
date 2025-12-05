<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'booked_by',
        'user_email',
        'booking_date',
        'jam_awal',
        'jam_akhir',
        'jumlah_peserta',
        'agenda',
        'penanggung_jawab',
        'status',
        'alasan' // Tambahkan ini
    ];


    // Relasi ke model Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
