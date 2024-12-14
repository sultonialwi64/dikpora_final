<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; // Gunakan model Booking
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    /**
     * Tampilkan halaman kalender.
     */
    public function index()
    {
        return view('rooms.kalender'); // Nama file blade untuk halaman kalender
    }

    /**
     * API untuk mendapatkan agenda berdasarkan tanggal.
     */
    public function getAgenda(Request $request)
    {
        $date = $request->query('date'); // Tanggal yang diminta dari frontend

        if (!$date) {
            return response()->json([
                'error' => 'Tanggal tidak diberikan.'
            ], 400);
        }

        // Ambil agenda dari tabel bookings berdasarkan tanggal
        $agendas = Booking::whereDate('booking_date', $date)
            ->select('id', 'agenda', 'jam_awal', 'jam_akhir', 'booked_by')
            ->where('status', 'accepted')
            ->get();

        return response()->json($agendas);
    }

    /**
     * API untuk mendapatkan semua agenda dalam format yang kompatibel dengan FullCalendar.
     */
    public function getAllAgenda()
    {
        $bookings = Booking::select('id', 'agenda as title', 'booking_date', 'jam_awal', 'jam_akhir', 'booked_by', 'status')
            ->where('status', 'accepted') // Hanya agenda yang sudah disetujui
            ->get();

        $formattedAgendas = $bookings->map(function ($booking) {
            $now = Carbon::now(); // Waktu saat ini
            $bookingDate = Carbon::parse($booking->booking_date)->format('Y-m-d');

            // Menggabungkan booking_date dengan jam_awal dan jam_akhir
            $startTime = Carbon::parse("{$bookingDate} {$booking->jam_awal}");
            $endTime = Carbon::parse("{$bookingDate} {$booking->jam_akhir}");

            // Menentukan status berdasarkan waktu
            if ($endTime->isPast()) {
                $status = 'Selesai';
                $color = '#647687'; // Abu-abu untuk "Selesai"
            } elseif ($now->between($startTime, $endTime)) {
                $status = 'Berlangsung';
                $color = '#7F00FF'; // Ungu untuk "Berlangsung"
            } else {
                $status = 'Terkonfirmasi';
                $color = '#28A745'; // Hijau untuk "Terkonfirmasi"
            }

            return [
                'title' => $booking->title, // Tambahkan status ke judul event
                'start' => $startTime->toDateTimeString(), // Tanggal dan waktu mulai
                'end' => $endTime->toDateTimeString(), // Tanggal dan waktu selesai
                'color' => $color // Warna sesuai status
            ];
        });

        return response()->json($formattedAgendas);
    }

    /**
     * Tampilkan halaman kalender.
     */
    public function admin()
    {
        return view('admin.kalender'); // Nama file blade untuk halaman kalender
    }

    /**
     * API untuk mendapatkan agenda berdasarkan tanggal.
     */
    public function getAgendaAdmin(Request $request)
    {
        $date = $request->query('date'); // Tanggal yang diminta dari frontend

        if (!$date) {
            return response()->json([
                'error' => 'Tanggal tidak diberikan.'
            ], 400);
        }

        // Ambil agenda dari tabel bookings berdasarkan tanggal
        $agendas = Booking::whereDate('booking_date', $date)
            ->select('id', 'agenda', 'jam_awal', 'jam_akhir', 'booked_by')
            ->whereIn('status', ['accepted', 'pending'])
            ->get();

        return response()->json($agendas);
    }

    /**
     * API untuk mendapatkan semua agenda dalam format yang kompatibel dengan FullCalendar.
     */
    public function getAllAgendaAdmin()
    {
        $bookings = Booking::select('id', 'agenda as title', 'booking_date', 'jam_awal', 'jam_akhir', 'booked_by', 'status')
            ->whereIn('status', ['accepted', 'pending'])
            ->get();

        $formattedAgendas = $bookings->map(function ($booking) {
            $now = Carbon::now(); // Waktu saat ini
            $bookingDate = Carbon::parse($booking->booking_date)->format('Y-m-d');

            // Menggabungkan booking_date dengan jam_awal dan jam_akhir
            $startTime = Carbon::parse("{$bookingDate} {$booking->jam_awal}");
            $endTime = Carbon::parse("{$bookingDate} {$booking->jam_akhir}");

            // Menentukan status berdasarkan waktu
            if ($endTime->isPast()) {
                $status = 'Selesai';
                $color = '#647687'; // Abu-abu untuk "Selesai"
            } elseif ($now->between($startTime, $endTime)) {
                $status = 'Berlangsung';
                $color = '#7F00FF'; // Ungu untuk "Berlangsung"
            } elseif ($booking->status == 'pending') {
                $status = 'Baru';
                $color = '#1BA1E2'; // Biru untuk "Baru"
            } else {
                $status = 'Terkonfirmasi';
                $color = '#28A745'; // Hijau untuk "Terkonfirmasi"
            }

            return [
                'title' => $booking->title, // Tambahkan status ke judul event
                'start' => $startTime->toDateTimeString(), // Tanggal dan waktu mulai
                'end' => $endTime->toDateTimeString(), // Tanggal dan waktu selesai
                'color' => $color // Warna sesuai status
            ];
        });

        return response()->json($formattedAgendas);
    }
}
