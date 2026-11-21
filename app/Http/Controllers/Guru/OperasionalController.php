<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalLab;
use App\Models\LogbookSiswa;
use App\Models\PeminjamanLab;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class OperasionalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR KELAS
    |--------------------------------------------------------------------------
    */

    private const KELAS = [
        '7A',
        '7B',
        '7C',
        '7D',
        '8A',
        '8B',
        '8C',
        '8D',
        '9A',
        '9C',
        '9D',
    ];

    /*
    |--------------------------------------------------------------------------
    | DAFTAR HARI
    |--------------------------------------------------------------------------
    */

    private const HARI = [
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
    ];

    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN OPERASIONAL GURU
    |--------------------------------------------------------------------------
    |
    | 1. Pengajuan Saya
    | 2. Pengajuan Peminjaman
    | 3. Jadwal Mata Pelajaran
    | 4. Logbook Kelas
    |
    */

    public function index(Request $request): View
    {
        $tab = $request->query(
            'tab',
            'pengajuan-saya'
        );

        $tabTersedia = [
            'pengajuan-saya',
            'ajukan',
            'jadwal',
            'logbook',
        ];

        if (
            !is_string($tab) ||
            !in_array($tab, $tabTersedia, true)
        ) {
            $tab = 'pengajuan-saya';
        }

        /*
        | Pengajuan milik Guru yang login.
        */

        $pengajuanSaya = PeminjamanLab::query()
            ->where(
                'guru_id',
                $request->user()->id
            )
            ->latest()
            ->get();

        /*
        | Jadwal dari PJ Lab.
        | Guru hanya bisa melihat.
        */

        $jadwal = JadwalLab::query()
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        /*
        | Monitoring Logbook Kelas.
        */

        $filterTanggal = '';
        $filterKelas = '';

        $logbooks = null;

        if ($tab === 'logbook') {

            $query = LogbookSiswa::query()
                ->with('siswa')
                ->latest();

            $tanggal = $request->query('tanggal');
            $kelas = $request->query('kelas');

            if (
                is_string($tanggal) &&
                preg_match(
                    '/^\d{4}-\d{2}-\d{2}$/',
                    $tanggal
                ) &&
                checkdate(
                    (int) substr($tanggal, 5, 2),
                    (int) substr($tanggal, 8, 2),
                    (int) substr($tanggal, 0, 4)
                )
            ) {

                $filterTanggal = $tanggal;

                $query->whereDate(
                    'created_at',
                    $tanggal
                );
            }

            if (
                is_string($kelas) &&
                in_array(
                    $kelas,
                    self::KELAS,
                    true
                )
            ) {

                $filterKelas = $kelas;

                $query->whereHas(
                    'siswa',
                    function ($q) use ($kelas) {

                        $q->where(
                            'kelas',
                            $kelas
                        );

                    }
                );
            }

            $logbooks = $query
                ->paginate(15)
                ->withQueryString();
        }

        /*
        | Kirim data ke halaman Blade.
        */

        return view(
            'guru.operasional.index',
            [
                'tab' => $tab,

                'pengajuanSaya' => $pengajuanSaya,

                'jadwal' => $jadwal,

                'logbooks' => $logbooks,

                'filterTanggal' => $filterTanggal,

                'filterKelas' => $filterKelas,

                'daftarKelas' => self::KELAS,

                'daftarHari' => self::HARI,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MENYIMPAN PENGAJUAN PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {

        /*
        | Validasi formulir pengajuan.
        */

        $validated = $request->validate([

            'kelas' => [
                'required',
                Rule::in(self::KELAS),
            ],

            'mata_pelajaran' => [
                'required',
                'string',
                'max:100',
            ],

            'tanggal' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],

            'jam_mulai' => [
                'required',
                'date_format:H:i',
            ],

            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
            ],

            'tujuan' => [
                'required',
                'string',
                'max:2000',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK PEMINJAMAN YANG SUDAH DISETUJUI
        |--------------------------------------------------------------------------
        */

        $bentrokPeminjaman = PeminjamanLab::query()
            ->where('status', 'disetujui')
            ->whereDate(
                'tanggal',
                $validated['tanggal']
            )
            ->where(
                'jam_mulai',
                '<',
                $validated['jam_selesai']
            )
            ->where(
                'jam_selesai',
                '>',
                $validated['jam_mulai']
            )
            ->exists();

        if ($bentrokPeminjaman) {

            throw ValidationException::withMessages([
                'jam_mulai' =>
                    'Laboratorium sudah memiliki peminjaman yang disetujui pada tanggal dan jam tersebut.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN PENGAJUAN KE DATABASE
        |--------------------------------------------------------------------------
        */

        $guru = $request->user();

        $pengajuan = PeminjamanLab::create([

            'guru_id' => $guru->id,

            'kelas' => $validated['kelas'],

            'mata_pelajaran' =>
                $validated['mata_pelajaran'],

            'tanggal' => $validated['tanggal'],

            'jam_mulai' =>
                $validated['jam_mulai'],

            'jam_selesai' =>
                $validated['jam_selesai'],

            'tujuan' => $validated['tujuan'],

            'status' => 'menunggu',

            'status_email' => 'belum_dikirim',

            'email_terkirim_at' => null,

        ]);

        /*
        |--------------------------------------------------------------------------
        | AMBIL EMAIL PJ LAB
        |--------------------------------------------------------------------------
        */

        $emailPjLab = config(
            'silabkom.pjlab_email'
        );

        if (
            !is_string($emailPjLab) ||
            filter_var(
                $emailPjLab,
                FILTER_VALIDATE_EMAIL
            ) === false
        ) {

            Log::warning(
                'Email penerima PJ Lab tidak valid.',
                [
                    'pengajuan_id' => $pengajuan->id,
                ]
            );

            return redirect()
                ->route(
                    'guru.operasional.index',
                    [
                        'tab' => 'pengajuan-saya',
                    ]
                )
                ->with(
                    'warning',
                    'Pengajuan berhasil disimpan, tetapi email PJ Lab belum dikonfigurasi dengan benar.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN SMTP AKTIF
        |--------------------------------------------------------------------------
        |
        | MAIL_MAILER=log hanya untuk tes lokal.
        | Kita tidak boleh menandainya terkirim.
        |
        */

        if (config('mail.default') !== 'smtp') {

            Log::warning(
                'Pengajuan belum dikirim karena mailer bukan SMTP.',
                [
                    'pengajuan_id' => $pengajuan->id,
                    'mailer' => config('mail.default'),
                ]
            );

            return redirect()
                ->route(
                    'guru.operasional.index',
                    [
                        'tab' => 'pengajuan-saya',
                    ]
                )
                ->with(
                    'warning',
                    'Pengajuan tersimpan, tetapi pengiriman email belum aktif. Konfigurasi MAIL_MAILER harus smtp.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | KIRIM NOTIFIKASI EMAIL MELALUI BREVO
        |--------------------------------------------------------------------------
        */

        try {

            Mail::send(

                'emails.pengajuan-lab',

                [
                    'pengajuan' => $pengajuan,
                    'guru' => $guru,
                ],

                function ($message) use (
                    $pengajuan,
                    $emailPjLab
                ) {

                    $message
                        ->to($emailPjLab)
                        ->subject(
                            'Pengajuan Peminjaman Lab #' .
                            $pengajuan->id .
                            ' - SI-LABKOM'
                        );

                }

            );

            /*
            | Berhasil diserahkan ke SMTP.
            | Bukan berarti pasti masuk Inbox.
            */

            $pengajuan->update([
                'status_email' => 'terkirim',
                'email_terkirim_at' => now(),
            ]);

            Log::info(
                'Notifikasi pengajuan diterima SMTP.',
                [
                    'pengajuan_id' => $pengajuan->id,
                ]
            );

            return redirect()
                ->route(
                    'guru.operasional.index',
                    [
                        'tab' => 'pengajuan-saya',
                    ]
                )
                ->with(
                    'success',
                    'Pengajuan berhasil disimpan dan pemberitahuan telah diserahkan ke layanan email PJ Lab.'
                );

        } catch (Throwable $e) {

            /*
            | Jika SMTP gagal, pengajuan
            | tetap tersimpan di database.
            */

            $pengajuan->update([
                'status_email' => 'gagal',
                'email_terkirim_at' => null,
            ]);

            Log::error(
                'Gagal mengirim email peminjaman.',
                [
                    'pengajuan_id' => $pengajuan->id,
                    'error' => $e->getMessage(),
                ]
            );

            return redirect()
                ->route(
                    'guru.operasional.index',
                    [
                        'tab' => 'pengajuan-saya',
                    ]
                )
                ->with(
                    'warning',
                    'Pengajuan berhasil disimpan, tetapi email belum berhasil dikirim. PJ Lab tetap dapat melihat pengajuan melalui sistem.'
                );
        }
    }
}
