<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('admin.master', compact('rooms'));
    }

    /**
     * Simpan data ruangan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
        ]);

        try {
            // Simpan ruangan baru
            Room::create([
                'name' => $request->input('name'),
                'capacity' => $request->input('capacity'),
                'location' => $request->input('location'),
            ]);

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Ruangan berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Tangani kesalahan dan tampilkan pesan
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booking_date' => 'required|date',
            'jam_awal' => 'required|date_format:H:i',
            'jam_akhir' => 'required|date_format:H:i|after:jam_awal',
            'jumlah_peserta' => 'required|integer|min:1',
        ]);

        // Fetch room capacity
        $room = Room::findOrFail($request->room_id);
        if ($request->jumlah_peserta > $room->capacity) {
            return response()->json(['available' => false, 'message' => 'Jumlah peserta melebihi kapasitas ruangan.']);
        }

        // Check room booking conflicts
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
            return response()->json(['available' => false, 'message' => 'Ruangan sudah dipesan pada waktu yang dipilih.']);
        }

        return response()->json(['available' => true, 'message' => 'Ruangan tersedia.']);
    }
}
