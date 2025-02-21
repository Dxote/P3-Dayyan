<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Shift;
use App\User;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensi = Absensi::with('user', 'shift')->get();
        $users = User::all();
        $shifts = Shift::all();

        return view('absensi.index', compact('absensi', 'users', 'shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kode_shift' => 'required|exists:shift,kode_shift',
            'tanggal_absen' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Buat kode absen otomatis
        $lastAbsensi = Absensi::orderByDesc('kode_absen')->first();
        $lastNumber = $lastAbsensi ? (int) substr($lastAbsensi->kode_absen, 3) : 0;
        $newKodeAbsen = 'ABN' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        Absensi::create([
            'kode_absen' => $newKodeAbsen,
            'user_id' => $request->user_id,
            'kode_shift' => $request->kode_shift,
            'tanggal_absen' => $request->tanggal_absen,
            'jam_absen' => now()->format('H:i'),
            'status' => $request->status,
            'keterangan' => ($request->status === 'izin' || $request->status === 'sakit') ? $request->keterangan : null,
        ]);

    return redirect()->route('absensi.index')->with('message', 'Absensi berhasil ditambahkan!');
    }


    public function edit($kode_absen)
    {
        $absensi = Absensi::findOrFail($kode_absen);
        $users = User::all();
        $shifts = Shift::all();

        return response()->json([
            'absensi' => $absensi,
            'users' => $users,
            'shifts' => $shifts,
        ]);
    }

    public function update(Request $request, $kode_absen)
    {
        $absensi = Absensi::where('kode_absen', $kode_absen)->firstOrFail();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kode_shift' => 'required|exists:shift,kode_shift',
            'tanggal_absen' => 'required|date',
            'jam_absen' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,tanpa keterangan',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi->update([
            'user_id' => $request->user_id,
            'kode_shift' => $request->kode_shift,
            'tanggal_absen' => $request->tanggal_absen,
            'jam_absen' => $request->jam_absen,
            'status' => $request->status,
            'keterangan' => $request->status !== 'hadir' ? $request->keterangan : null, 
        ]);

        return redirect()->route('absensi.index')->with('message', 'Absensi berhasil diperbarui!');
    }

        public function destroy($kode_absen)
        {
            Absensi::findOrFail($kode_absen)->delete();
            return redirect()->route('absensi.index')->with('message', 'Absensi berhasil dihapus!');
        }

        public function petugasIndex()
        {
            $user = auth()->user();
            $shifts = Shift::where('user_id', $user->id)
                ->whereDate('tanggal_shift', now()->toDateString())
                ->get();

            foreach ($shifts as $shift) {
                $absensi = Absensi::where('user_id', $user->id)
                    ->where('kode_shift', $shift->kode_shift)
                    ->whereDate('tanggal_absen', today())
                    ->first();

                $jamMulai = \Carbon\Carbon::parse($shift->jam_mulai);
                $jamSelesai = \Carbon\Carbon::parse($shift->jam_selesai);
                $expired = now()->greaterThan($jamSelesai->addHour());
                $shiftBelumMulai = now()->lessThan($jamMulai);

                if (!$absensi) {
                    $absensi = new Absensi([
                        'kode_absen' => null,
                        'user_id' => $user->id,
                        'kode_shift' => $shift->kode_shift,
                        'tanggal_absen' => today(),
                        'jam_absen' => null,
                        'status' => 'tanpa keterangan',
                    ]);
                }

                $shift->absensi = $absensi;
                $shift->expired = $expired;
                $shift->shift_belum_mulai = $shiftBelumMulai;
            }

            return view('absensi.petugas', compact('shifts'));
        }                

        public function absenHadir(Request $request)
        {
            try {
                $user = auth()->user();
                $shift = Shift::where('user_id', $user->id)
                    ->where('kode_shift', $request->kode_shift)
                    ->whereDate('tanggal_shift', today())
                    ->first();

                if (!$shift) {
                    return response()->json(['error' => 'Shift tidak ditemukan'], 404);
                }

                // Ambil kode absen terakhir untuk menentukan kode baru
                $lastAbsensi = Absensi::orderByDesc('kode_absen')->first();
                $lastNumber = $lastAbsensi ? (int) substr($lastAbsensi->kode_absen, 3) : 0;
                $newKodeAbsen = 'ABN' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

                // Simpan atau update absensi dengan status hadir atau izin/sakit
                $absensi = Absensi::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'kode_shift' => $shift->kode_shift,
                        'tanggal_absen' => now()->toDateString(),
                    ],
                    [
                        'jam_absen' => now()->format('H:i:s'),
                        'status' => $request->status ?? 'Hadir',
                        'keterangan' => in_array($request->status, ['izin', 'sakit']) ? $request->keterangan : null,
                        'kode_absen' => $newKodeAbsen,
                    ]
                );

                return response()->json([
                    'message' => 'Absensi berhasil dicatat',
                    'absensi' => $absensi,
                    'refresh' => true // Tambahkan ini
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        public function invoice()
        {
            $absensi = Absensi::with('user', 'shift')->get();
            $users = User::all();
            $shifts = Shift::all();
    
            return view('absensi.invoice', compact('absensi', 'users', 'shifts'));
        }       
}
