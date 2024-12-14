<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Room;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booked_by' => 'required|string|max:255',
            'user_email' => 'required|email',
            'booking_date' => 'required|date',
            'jam_awal' => 'required|date_format:H:i', // Validasi format jam awal
            'jam_akhir' => 'required|date_format:H:i|after:jam_awal', // Validasi jam akhir harus setelah jam awal
            'jumlah_peserta' => 'required|integer',
            'agenda' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255'
        ]);

        // Cek apakah sudah ada booking di tanggal dan waktu yang tumpang tindih
        $isBooked = Booking::where('room_id', $request->room_id)
            ->where('booking_date', $request->booking_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_awal', [$request->jam_awal, $request->jam_akhir])
                    ->orWhereBetween('jam_akhir', [$request->jam_awal, $request->jam_akhir])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('jam_awal', '<=', $request->jam_awal)
                            ->where('jam_akhir', '>=', $request->jam_akhir);
                    });
            })
            ->exists();

        if ($isBooked) {
            return back()->withErrors(['error' => 'Ruangan sudah dibooking pada tanggal dan waktu yang dipilih.'])->withInput();
        }

        // Simpan data booking jika tersedia
        Booking::create([
            'room_id' => $request->room_id,
            'booked_by' => $request->booked_by,
            'user_email' => $request->user_email,
            'booking_date' => $request->booking_date,
            'jam_awal' => $request->jam_awal, 
            'jam_akhir' => $request->jam_akhir, 
            'jumlah_peserta' => $request->jumlah_peserta, 
            'agenda' => $request->agenda, 
            'penanggung_jawab' => $request->penanggung_jawab, 
        ]);

        return redirect('/pengajuan')->with('success', 'Booking berhasil diajukan!');
    }

    public function index()
    {
        // Ambil semua booking
        $bookings = Booking::with('room')->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function jadwal()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            // Ambil nama user yang sedang login
            $userName = $user->name;

            // Ambil peminjaman ruang berdasarkan nama user dan status "disetujui"
            $bookings = Booking::where('booked_by', $userName)
                ->where('booking_date', '>=', \Carbon\Carbon::today()) // Filter untuk tanggal hari ini
                ->where('status', 'accepted') // Filter status disetujui
                ->orderBy('booking_date', 'asc')
                ->get();

            // Format tanggal dalam data yang dikembalikan
            $formattedBookings = $bookings->map(function ($booking) {
                $booking->formatted_booking_date = \Carbon\Carbon::parse($booking->booking_date)
                    ->translatedFormat('d F Y'); // 27 Juni 2004
                return $booking;
            });

            // Menampilkan halaman home dengan data peminjaman
            return view('rooms.jadwal', compact('bookings'));
        }

        // Jika user tidak login atau invalid
        return response()->json([
            'status' => 'error',
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    public function jadwalAdmin()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            // Ambil nama user yang sedang login (jika dibutuhkan)
            $userName = $user->name;

            // Ambil peminjaman ruang dengan status "disetujui" dan tanggal hari ini
            $bookings = Booking::where('status', 'accepted') // Filter status disetujui
                ->whereDate('booking_date', \Carbon\Carbon::today()) // Filter untuk tanggal hari ini
                ->orderBy('jam_awal', 'asc')
                ->get();

            // Format tanggal dalam data yang dikembalikan
            $formattedBookings = $bookings->map(function ($booking) {
                $booking->formatted_booking_date = \Carbon\Carbon::parse($booking->booking_date)
                    ->translatedFormat('d F Y'); // Contoh: 27 Juni 2004
                return $booking;
            });

            // Menampilkan halaman home dengan data peminjaman
            return view('admin.jadwal', compact('bookings'));
        }

        // Jika user tidak login atau invalid
        return response()->json([
            'status' => 'error',
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    public function getHistory(Request $request)
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            $userName = $user->name;

            // Ambil bulan yang dipilih (default ke bulan saat ini)
            $month = $request->input('month', \Carbon\Carbon::now()->month);
            $year = \Carbon\Carbon::now()->year; // Tahun langsung diambil dari sistem

            // Ambil peminjaman ruang dengan status "accepted" dan bulan yang dipilih
            $bookings = Booking::where('status', 'accepted')
                ->whereYear('booking_date', $year) // Gunakan tahun saat ini
                ->whereMonth('booking_date', $month) // Filter bulan
                ->get();

            // Format tanggal dalam data yang dikembalikan
            $formattedBookings = $bookings->map(function ($booking) {
                $booking->formatted_booking_date = \Carbon\Carbon::parse($booking->booking_date)
                    ->translatedFormat('d F Y'); // Format tanggal
                return $booking;
            });

            // Menampilkan halaman history dengan data peminjaman dan filter bulan
            return view('admin.history', compact('bookings', 'month', 'year'));
        }

        // Jika user tidak login atau invalid
        return response()->json([
            'status' => 'error',
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    public function pembatalan()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            // Ambil nama user yang sedang login
            $userName = $user->name;

            // Ambil peminjaman ruang berdasarkan nama user dan status "disetujui"
            $bookings = Booking::where('booked_by', $userName)
                ->where('status', 'accepted') // Filter status disetujui
                ->get();

            // Format tanggal dalam data yang dikembalikan
            $formattedBookings = $bookings->map(function ($booking) {
                $booking->formatted_booking_date = \Carbon\Carbon::parse($booking->booking_date)
                    ->translatedFormat('d F Y'); // 27 Juni 2004
                return $booking;
            });

            // Menampilkan halaman home dengan data peminjaman
            return view('rooms.pembatalan', compact('bookings'));
        }

        // Jika user tidak login atau invalid
        return response()->json([
            'status' => 'error',
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    public function getNotif()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();
    
        if ($user instanceof \App\Models\User) {
            // Ambil nama user yang sedang login
            $userName = $user->name;
    
            // Ambil peminjaman ruang berdasarkan nama user
            $bookings = Booking::where('booked_by', $userName)
                ->orderBy('updated_at', 'desc')
                ->get();
    
            // Format data peminjaman sesuai kategori status
            $formattedBookings = $bookings->map(function ($booking) {
                $booking->formatted_booking_date = \Carbon\Carbon::parse($booking->booking_date)
                    ->translatedFormat('d F Y'); // Format tanggal
                return $booking;
            });
    
            // Kirim data peminjaman ke view
            return view('rooms.notifikasi', compact('formattedBookings'));
        }
    
        // Jika user tidak login atau invalid
        return response()->json([
            'status' => 'error',
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    public function getNotifAdmin()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            // Ambil semua peminjaman ruang, diurutkan berdasarkan waktu pembaruan
            $bookings = Booking::where('status', 'pending')
                ->orderBy('updated_at', 'desc')
                ->get();

            // Format data peminjaman sesuai kebutuhan
            $formattedBookings = $bookings->map(function ($booking) {
                $booking->formatted_booking_date = \Carbon\Carbon::parse($booking->booking_date)
                    ->translatedFormat('d F Y'); // Format tanggal
                return $booking;
            });

            // Kirim data peminjaman ke view
            return view('admin.notifikasi', compact('formattedBookings'));
        }

        // Jika user tidak login atau invalid
        return response()->json([
            'status' => 'error',
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    public function downloadHistory(Request $request)
    {
        // Ambil bulan dan tahun yang dipilih, lalu konversi $month ke integer
        $month = intval($request->input('month', \Carbon\Carbon::now()->month));
        $year = \Carbon\Carbon::now()->year; // Tahun langsung diambil dari sistem
            
        // Ambil peminjaman ruang dengan status "accepted" dan bulan yang dipilih
        $bookings = Booking::where('status', 'accepted')
            ->whereYear('booking_date', $year) // Filter tahun
            ->whereMonth('booking_date', $month) // Filter bulan
            ->get();
    
        // Format data peminjaman sesuai kategori status
        $formattedBookings = $bookings->map(function ($booking) {
            $booking->formatted_booking_date = \Carbon\Carbon::parse($booking->booking_date)
                ->translatedFormat('d F Y'); // Format tanggal
            return $booking;
        });
    
        // Ambil nama bulan dalam format teks (Indonesia)
        $monthName = \Carbon\Carbon::create()->month($month)->translatedFormat('F');
    
        // Render view ke dalam format PDF
        $pdf = Pdf::loadView('export-history', compact('formattedBookings', 'month', 'year')); // Kirim $month dan $year ke view
        return $pdf->download("history-peminjaman-{$monthName}-{$year}.pdf"); // Gunakan nama bulan dalam file
    }
    
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = $request->status; // "accepted" atau "rejected"
        $booking->save();

        return response()->json(['message' => 'Status berhasil diperbarui.']);
    }

    public function cancel($id)
    {
        // Cari booking berdasarkan ID
        $booking = Booking::find($id);

        // Jika booking tidak ditemukan, kembalikan error
        if (!$booking) {
            return redirect()->back()->withErrors(['error' => 'Booking tidak ditemukan.']);
        }

        // Perbarui status booking menjadi 'cancelled'
        $booking->status = 'cancelled';
        $booking->save();

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    public function getPengajuan()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            // Ambil nama user yang sedang login
            $userName = $user->name;

            // Ambil peminjaman ruang berdasarkan nama user dan status "disetujui"
            $bookings = Booking::where('booked_by', $userName)
                ->orderBy('booking_date', 'desc')
                ->get();

            // Format tanggal dalam data yang dikembalikan
            $formattedBookings = $bookings->map(function ($booking) {
                $booking->formatted_booking_date = \Carbon\Carbon::parse($booking->booking_date)
                    ->translatedFormat('d F Y'); // 27 Juni 2004
                return $booking;
            });

            // Mengambil semua ruangan yang tersedia untuk pengajuan
            $rooms = Room::all();

            // Menampilkan halaman home dengan data peminjaman
            return view('rooms.pengajuan', compact('bookings', 'rooms'));
        }

        // Jika user tidak login atau invalid
        return response()->json([
            'status' => 'error',
            'message' => 'Silakan login terlebih dahulu'
        ], 401);
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'accepted']);
        return redirect('/admin/bookings')->with('success', 'Booking approved.');
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'rejected']);
        return redirect('/admin/bookings')->with('success', 'Booking rejected.');
    }
}
