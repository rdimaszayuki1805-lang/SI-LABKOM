<?php

namespace App\Http\Controllers\PjLab;


use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SiswaLogbookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR SISWA PER KELAS
    |--------------------------------------------------------------------------
    */

    public function index(?string $kelas = null): View
    {
        $kelas = strtoupper($kelas ?? '7A');

        abort_unless(
            in_array($kelas, Siswa::KELAS, true),
            404
        );

        $siswa = Siswa::query()
            ->where('kelas', $kelas)
            ->orderBy('nama')
            ->get();

        $jumlahPerKelas = Siswa::query()
            ->selectRaw('kelas, COUNT(*) as total')
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        return view('pjlab.siswa.index', [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'daftarKelas' => Siswa::KELAS,
            'jumlahPerKelas' => $jumlahPerKelas,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH SISWA MANUAL
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'nama' => trim((string) $request->input('nama')),
            'nisn' => trim((string) $request->input('nisn')),
            'kelas' => strtoupper(
                trim((string) $request->input('kelas'))
            ),
        ]);

        $data = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:150',
            ],
            'nisn' => [
                'required',
                'digits:10',
                Rule::unique('siswas', 'nisn'),
            ],
            'kelas' => [
                'required',
                Rule::in(Siswa::KELAS),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        Siswa::create([
            'nama' => $data['nama'],
            'nisn' => $data['nisn'],
            'kelas' => $data['kelas'],
            'password' => $data['password'],
        ]);

        return redirect()
            ->route('pjlab.siswa.index', [
                'kelas' => $data['kelas'],
            ])
            ->with('success', 'Akun siswa berhasil ditambahkan.');
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT NAMA, NISN, DAN PASSWORD
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Siswa $siswa
    ): RedirectResponse {

        $request->merge([
            'nama' => trim((string) $request->input('nama')),
            'nisn' => trim((string) $request->input('nisn')),
        ]);

        $data = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:150',
            ],
            'nisn' => [
                'required',
                'digits:10',
                Rule::unique('siswas', 'nisn')
                    ->ignore($siswa->id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $siswa->nama = $data['nama'];
        $siswa->nisn = $data['nisn'];

        // Kosongkan password jika tidak ingin menggantinya.
        if (!empty($data['password'])) {
            $siswa->password = $data['password'];
        }

        $siswa->save();

        return redirect()
            ->route('pjlab.siswa.index', [
                'kelas' => $siswa->kelas,
            ])
            ->with('success', 'Data siswa berhasil diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS SISWA
    |--------------------------------------------------------------------------
    */

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $kelas = $siswa->kelas;
        $nama = $siswa->nama;

        $siswa->delete();

        return redirect()
            ->route('pjlab.siswa.index', [
                'kelas' => $kelas,
            ])
            ->with(
                'success',
                "Akun siswa {$nama} berhasil dihapus."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD TEMPLATE EXCEL
    |--------------------------------------------------------------------------
    */

    public function template(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Data Siswa');

        $sheet->setCellValue('A1', 'nama');
        $sheet->setCellValue('B1', 'nisn');
        $sheet->setCellValue('C1', 'password');

        $sheet->getStyle('A1:C1')
            ->getFont()
            ->setBold(true);

        $sheet->getColumnDimension('A')
            ->setWidth(35);

        $sheet->getColumnDimension('B')
            ->setWidth(20);

        $sheet->getColumnDimension('C')
            ->setWidth(25);

        // Format NISN sebagai teks.
        // Penting untuk NISN yang diawali angka nol.
        $sheet->getStyle('B2:B501')
            ->getNumberFormat()
            ->setFormatCode('@');

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer, $spreadsheet) {

                $writer->save('php://output');

                $spreadsheet->disconnectWorksheets();

            },
            'template-import-siswa-logbook.xlsx',
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | IMPORT DATA SISWA MELALUI EXCEL
    |--------------------------------------------------------------------------
    | - File .xlsx
    | - Maksimal 500 baris data
    | - Validasi nama, NISN, dan password
    | - NISN tidak boleh duplikat
    | - Semua siswa masuk kelas yang dipilih PJ Lab
    | - Jika ada data tidak valid, import dibatalkan
    |--------------------------------------------------------------------------
    */

    public function import(Request $request): RedirectResponse
    {
        $request->merge([
            'kelas' => strtoupper(
                trim((string) $request->input('kelas'))
            ),
        ]);

        $validated = $request->validate([
            'kelas' => [
                'required',
                Rule::in(Siswa::KELAS),
            ],
            'file_excel' => [
                'required',
                'file',
                'mimes:xlsx',
                'max:2048',
            ],
        ]);

        $kelas = $validated['kelas'];

        $file = $request->file('file_excel');

        $reader = IOFactory::createReaderForFile(
            $file->getRealPath()
        );

        $reader->setReadDataOnly(true);

        try {
            $spreadsheet = $reader->load(
                $file->getRealPath()
            );
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                'file_excel' =>
                    'File Excel tidak dapat dibaca. Gunakan template .xlsx yang tersedia.',
            ]);
        }

        try {
            $sheet = $spreadsheet->getActiveSheet();

            $highestRow = $sheet->getHighestDataRow();

            if ($highestRow < 2) {
                throw ValidationException::withMessages([
                    'file_excel' =>
                        'File Excel belum berisi data siswa.',
                ]);
            }

            if ($highestRow > 501) {
                throw ValidationException::withMessages([
                    'file_excel' =>
                        'Maksimal 500 siswa dalam satu file Excel.',
                ]);
            }

            $headers = [
                mb_strtolower(
                    trim((string) $sheet->getCell('A1')->getValue())
                ),
                mb_strtolower(
                    trim((string) $sheet->getCell('B1')->getValue())
                ),
                mb_strtolower(
                    trim((string) $sheet->getCell('C1')->getValue())
                ),
            ];

            if ($headers !== ['nama', 'nisn', 'password']) {
                throw ValidationException::withMessages([
                    'file_excel' =>
                        'Header Excel harus berurutan: nama, nisn, password.',
                ]);
            }

            $dataSiswa = [];
            $nisnDalamFile = [];

            for ($row = 2; $row <= $highestRow; $row++) {

                $namaCell = $sheet->getCell("A{$row}");
                $nisnCell = $sheet->getCell("B{$row}");
                $passwordCell = $sheet->getCell("C{$row}");

                // Jangan menerima formula pada data siswa.
                foreach ([
                    $namaCell,
                    $nisnCell,
                    $passwordCell,
                ] as $cell) {

                    if (
                        $cell->getDataType() ===
                        DataType::TYPE_FORMULA
                    ) {
                        throw ValidationException::withMessages([
                            'file_excel' =>
                                "Baris {$row}: formula Excel tidak diperbolehkan.",
                        ]);
                    }
                }

                $nama = trim(
                    (string) $namaCell->getValue()
                );

                $nisn = trim(
                    (string) $nisnCell->getValue()
                );

                $password = trim(
                    (string) $passwordCell->getValue()
                );

                // Lewati baris yang benar-benar kosong.
                if (
                    $nama === '' &&
                    $nisn === '' &&
                    $password === ''
                ) {
                    continue;
                }

                $validator = Validator::make(
                    [
                        'nama' => $nama,
                        'nisn' => $nisn,
                        'password' => $password,
                    ],
                    [
                        'nama' => [
                            'required',
                            'string',
                            'max:150',
                        ],
                        'nisn' => [
                            'required',
                            'digits:10',
                            Rule::unique('siswas', 'nisn'),
                        ],
                        'password' => [
                            'required',
                            'string',
                            'min:8',
                        ],
                    ],
                    [
                        'nisn.unique' =>
                            'NISN sudah terdaftar di sistem.',
                    ]
                );

                if ($validator->fails()) {
                    throw ValidationException::withMessages([
                        'file_excel' =>
                            "Baris {$row}: " .
                            $validator->errors()->first(),
                    ]);
                }

                if (isset($nisnDalamFile[$nisn])) {
                    throw ValidationException::withMessages([
                        'file_excel' =>
                            "Baris {$row}: NISN {$nisn} duplikat dalam file Excel.",
                    ]);
                }

                $nisnDalamFile[$nisn] = true;

                $dataSiswa[] = [
                    'nama' => $nama,
                    'nisn' => $nisn,
                    'kelas' => $kelas,
                    'password' => $password,
                ];
            }

            if (count($dataSiswa) === 0) {
                throw ValidationException::withMessages([
                    'file_excel' =>
                        'Tidak ditemukan data siswa untuk diimpor.',
                ]);
            }

            // Transaksi: seluruh data tersimpan atau
            // seluruh proses dibatalkan.
            DB::transaction(function () use ($dataSiswa) {

                foreach ($dataSiswa as $data) {
                    Siswa::create($data);
                }

            });

            return redirect()
                ->route('pjlab.siswa.index', [
                    'kelas' => $kelas,
                ])
                ->with(
                    'success',
                    count($dataSiswa) .
                    " siswa berhasil diimpor ke kelas {$kelas}."
                );

        } finally {

            $spreadsheet->disconnectWorksheets();

        }
    }
}
