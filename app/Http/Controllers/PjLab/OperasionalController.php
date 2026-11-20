<?php

namespace App\Http\Controllers\PjLab;

use App\Http\Controllers\Controller;
use App\Models\ChecklistLab;
use App\Models\JadwalLab;
use App\Models\LogbookSiswa;
use App\Models\PeminjamanLab;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OperasionalController extends Controller
{
    public const KELAS = [
        '7A', '7B', '7C', '7D',
        '8A', '8B', '8C', '8D',
        '9A', '9C', '9D',
    ];

    public const HARI = [
        'Senin', 'Selasa', 'Rabu',
        'Kamis', 'Jumat', 'Sabtu',
    ];

    public const LOKASI = [
        'Lab Komputer 1',
    ];

    public const ITEM_CHECKLIST = [
        'Komputer',
        'Monitor',
        'Keyboard',
        'Mouse',
        'Internet',
        'Proyektor',
        'Speaker',
        'AC',
        'Listrik',
        'Kebersihan',
    ];

    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN HALAMAN OPERASIONAL
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'pengajuan');

        if (!in_array($tab, [
            'pengajuan',
            'jadwal',
            'checklist',
            'logbook',
        ], true)) {
            $tab = 'pengajuan';
        }

        // Pengajuan dari Guru.
        $pengajuan = PeminjamanLab::with('guru')
            ->latest()
            ->get();

        // Jadwal mingguan.
        $jadwal = JadwalLab::query()
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        // Riwayat checklist PJ Lab.
        $riwayatChecklist = ChecklistLab::with('pjlab')
            ->latest()
            ->limit(20)
            ->get();

        // Monitoring data Logbook Siswa.
        $queryLogbook = LogbookSiswa::with('siswa')
            ->latest();

        $filterTanggal = $request->query(
            'tanggal'
        );

        $filterKelas = $request->query(
            'kelas'
        );

        // Hanya filter tanggal dan kelas.
        // Tidak ada filter Guru maupun Nomor PC.
        if (
            is_string($filterTanggal) &&
            preg_match(
                '/^\d{4}-\d{2}-\d{2}$/',
                $filterTanggal
            )
        ) {
            $queryLogbook->whereDate(
                'created_at',
                $filterTanggal
            );
        } else {
            $filterTanggal = '';
        }

        if (
            is_string($filterKelas) &&
            in_array($filterKelas, self::KELAS, true)
        ) {
            $queryLogbook->whereHas(
                'siswa',
                function ($query) use ($filterKelas) {
                    $query->where('kelas', $filterKelas);
                }
            );
        } else {
            $filterKelas = '';
        }

        $logbooks = $queryLogbook->paginate(20)
            ->withQueryString();

        $jumlahSiswa = Siswa::query()
            ->selectRaw('kelas, COUNT(*) as total')
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        return view(
            'pjlab.operasional.index',
            [
                'tab' => $tab,
                'pengajuan' => $pengajuan,
                'jadwal' => $jadwal,
                'riwayatChecklist' => $riwayatChecklist,
                'logbooks' => $logbooks,
                'jumlahSiswa' => $jumlahSiswa,
                'filterTanggal' => $filterTanggal,
                'filterKelas' => $filterKelas,
                'daftarKelas' => self::KELAS,
                'daftarHari' => self::HARI,
                'daftarLokasi' => self::LOKASI,
                'itemChecklist' => self::ITEM_CHECKLIST,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SETUJUI ATAU TOLAK PENGAJUAN GURU
    |--------------------------------------------------------------------------
    */

    public function keputusan(
        Request $request,
        PeminjamanLab $peminjaman
    ): RedirectResponse {

        $data = $request->validate([
            'keputusan' => [
                'required',
                Rule::in(['disetujui', 'ditolak']),
            ],
        ]);

        // Pastikan status tidak berubah oleh request
        // lain ketika dua admin menekan tombol bersamaan.
        $berhasil = PeminjamanLab::query()
            ->whereKey($peminjaman->id)
            ->where('status', 'menunggu')
            ->update([
                'status' => $data['keputusan'],
            ]);

        if (!$berhasil) {
            return back()->with(
                'warning',
                'Pengajuan ini sudah diproses sebelumnya.'
            );
        }

        $pesan = $data['keputusan'] === 'disetujui'
            ? 'Pengajuan berhasil disetujui.'
            : 'Pengajuan berhasil ditolak.';

        return redirect()
            ->route('pjlab.operasional.index', [
                'tab' => 'pengajuan',
            ])
            ->with('success', $pesan);
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAH JADWAL MATA PELAJARAN
    |--------------------------------------------------------------------------
    */

    public function simpanJadwal(
        Request $request
    ): RedirectResponse {

        $data = $request->validate([
            'hari' => [
                'required',
                Rule::in(self::HARI),
            ],
            'kelas' => [
                'required',
                Rule::in(self::KELAS),
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
            'mata_pelajaran' => [
                'required',
                'string',
                'max:100',
            ],
            'guru' => [
                'required',
                'string',
                'max:150',
            ],
            'lokasi' => [
                'required',
                Rule::in(self::LOKASI),
            ],
            'status' => [
                'required',
                Rule::in([
                    'Belum Dimulai',
                    'Berlangsung',
                    'Selesai',
                ]),
            ],
        ]);

        DB::transaction(function () use ($data) {

            // Cek jika laboratorium dan hari sama,
            // lalu jam penggunaan saling bertumpukan.
            $bentrok = JadwalLab::query()
                ->where('hari', $data['hari'])
                ->where('lokasi', $data['lokasi'])
                ->where('jam_mulai', '<', $data['jam_selesai'])
                ->where('jam_selesai', '>', $data['jam_mulai'])
                ->lockForUpdate()
                ->first();

            if ($bentrok) {
                throw ValidationException::withMessages([
                    'jam_mulai' =>
                        'Jadwal bentrok dengan kelas ' .
                        $bentrok->kelas . ' (' .
                        $bentrok->mata_pelajaran . ') pukul ' .
                        substr($bentrok->jam_mulai, 0, 5) .
                        '–' .
                        substr($bentrok->jam_selesai, 0, 5) .
                        ' di ' . $bentrok->lokasi . '.',
                ]);
            }

            JadwalLab::create($data);
        });

        return redirect()
            ->route('pjlab.operasional.index', [
                'tab' => 'jadwal',
            ])
            ->with(
                'success',
                'Jadwal mata pelajaran berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN CHECKLIST KESIAPAN
    |--------------------------------------------------------------------------
    */

    public function simpanChecklist(
        Request $request
    ): RedirectResponse {

        $rules = [
            'lokasi' => [
                'required',
                Rule::in(self::LOKASI),
            ],
            'hasil' => [
                'required',
                'array',
            ],
        ];

        foreach (self::ITEM_CHECKLIST as $item) {
            $rules['hasil.' . $item] = [
                'required',
                Rule::in(['Siap', 'Tidak Siap']),
            ];
        }

        $data = $request->validate($rules);

        // Hanya ambil item yang diizinkan.
        $hasil = [];

        foreach (self::ITEM_CHECKLIST as $item) {
            $hasil[$item] = $data['hasil'][$item];
        }

        $adaTidakSiap = in_array(
            'Tidak Siap',
            $hasil,
            true
        );

        ChecklistLab::create([
            'pjlab_id' => $request->user()->id,
            'lokasi' => $data['lokasi'],
            'hasil' => $hasil,
            'status' => $adaTidakSiap
                ? 'Tidak Siap'
                : 'Siap',
        ]);

        return redirect()
            ->route('pjlab.operasional.index', [
                'tab' => 'checklist',
            ])
            ->with(
                $adaTidakSiap ? 'warning' : 'success',
                $adaTidakSiap
                    ? 'Checklist tersimpan. Terdapat item Tidak Siap yang perlu ditindaklanjuti.'
                    : 'Checklist kesiapan berhasil disimpan.'
            );
    }
}
