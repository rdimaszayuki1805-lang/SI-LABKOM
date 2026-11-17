<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\LogbookSiswa;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LogbookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI MATA PELAJARAN
    |--------------------------------------------------------------------------
    | Daftar ini dapat disesuaikan dengan kurikulum sekolah.
    */

    private const MATA_PELAJARAN = [
        'Informatika',
        'TIK',
        'Matematika',
        'IPA',
        'Bahasa Indonesia',
        'Bahasa Inggris',
        'IPS',
        'PPKn',
        'Lainnya',
    ];

    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI NOMOR PC
    |--------------------------------------------------------------------------
    | Untuk sementara PC-01 sampai PC-20.
    | Sesuaikan dengan jumlah komputer sebenarnya.
    */

    private function daftarPc(): array
    {
        $pc = [];

        for ($i = 1; $i <= 20; $i++) {
            $pc[] = 'PC-' . str_pad(
                (string) $i,
                2,
                '0',
                STR_PAD_LEFT
            );
        }

        return $pc;
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN LOGBOOK SISWA
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $siswa = Siswa::findOrFail(
            $request->session()->get('siswa_id')
        );

        $waktuLogin = Carbon::parse(
            $request->session()->get('siswa_login_at')
        )->timezone('Asia/Jakarta');

        $riwayat = LogbookSiswa::query()
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('siswa.logbook.index', [
            'siswa' => $siswa,
            'waktuLogin' => $waktuLogin,
            'riwayat' => $riwayat,
            'daftarMapel' => self::MATA_PELAJARAN,
            'daftarPc' => $this->daftarPc(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MENYIMPAN LOGBOOK
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mata_pelajaran' => [
                'required',
                Rule::in(self::MATA_PELAJARAN),
            ],

            'nomor_komputer' => [
                'required',
                Rule::in($this->daftarPc()),
            ],

            'monitor' => [
                'required',
                Rule::in(['Baik', 'Rusak']),
            ],

            'keyboard' => [
                'required',
                Rule::in(['Baik', 'Rusak']),
            ],

            'mouse' => [
                'required',
                Rule::in(['Baik', 'Rusak']),
            ],

            'cpu' => [
                'required',
                Rule::in(['Baik', 'Rusak']),
            ],

            'jaringan' => [
                'required',
                Rule::in(['Baik', 'Rusak']),
            ],

            'ada_kendala' => [
                'required',
                Rule::in(['0', '1']),
            ],

            'kendala' => [
                'nullable',
                'string',
                'max:2000',
                'required_if:ada_kendala,1',
            ],
        ]);

        $siswa = Siswa::findOrFail(
            $request->session()->get('siswa_id')
        );

        $waktuLogin = Carbon::parse(
            $request->session()->get('siswa_login_at')
        );

        $kondisi = [
            $validated['monitor'],
            $validated['keyboard'],
            $validated['mouse'],
            $validated['cpu'],
            $validated['jaringan'],
        ];

        $adaKerusakan = in_array(
            'Rusak',
            $kondisi,
            true
        );

        // Menjaga kompatibilitas dengan kolom lama.
        $kondisiPerangkat = $adaKerusakan
            ? 'Bermasalah'
            : 'Baik';

        // Jika ada perangkat rusak, wajib laporkan kendala.
        if ($adaKerusakan && $validated['ada_kendala'] === '0') {

            return back()
                ->withInput()
                ->withErrors([
                    'ada_kendala' =>
                        'Terdapat perangkat yang dipilih Rusak. Pilih Ya dan jelaskan kendalanya.',
                ]);
        }

        LogbookSiswa::create([
            'siswa_id' => $siswa->id,

            'mata_pelajaran' =>
                $validated['mata_pelajaran'],

            'nomor_komputer' =>
                $validated['nomor_komputer'],

            // Mengisi kolom kegiatan lama secara otomatis.
            'kegiatan' =>
                'Penggunaan laboratorium untuk mata pelajaran ' .
                $validated['mata_pelajaran'],

            'monitor' => $validated['monitor'],
            'keyboard' => $validated['keyboard'],
            'mouse' => $validated['mouse'],
            'cpu' => $validated['cpu'],
            'jaringan' => $validated['jaringan'],

            'ada_kendala' =>
                $validated['ada_kendala'] === '1',

            'kondisi_perangkat' => $kondisiPerangkat,

            'kendala' =>
                $validated['ada_kendala'] === '1'
                    ? $validated['kendala']
                    : null,

            'waktu_login' => $waktuLogin,
        ]);

        return redirect()
            ->route('logbook.siswa')
            ->with(
                'success',
                'Logbook berhasil disimpan. Terima kasih telah memeriksa kondisi perangkat.'
            );
    }
}
