
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Pengajuan Peminjaman Laboratorium
    </title>
</head>

<body style="
    margin:0;
    padding:30px 12px;
    background:#f4f8f5;
    font-family:Arial,Helvetica,sans-serif;
    color:#24392d;">

    <div style="
        max-width:620px;
        margin:auto;
        background:#ffffff;
        border:1px solid #e1eae4;
        border-radius:14px;
        overflow:hidden;">

        <!-- HEADER EMAIL -->
        <div style="
            background:#237a51;
            padding:28px 30px;
            color:white;">

            <h1 style="
                margin:0;
                font-size:24px;
                letter-spacing:.3px;">

                SI-LABKOM

            </h1>

            <p style="
                margin:8px 0 0;
                font-size:13px;
                color:#e0f3e7;">

                MTs Al Ittihadiyah Pekanbaru

            </p>

        </div>


        <!-- KONTEN EMAIL -->
        <div style="padding:30px;">

            <div style="
                display:inline-block;
                background:#fff6dc;
                color:#8a691e;
                padding:8px 12px;
                border-radius:7px;
                font-size:12px;
                font-weight:bold;">

                MENUNGGU PERSETUJUAN

            </div>


            <h2 style="
                margin:22px 0 12px;
                font-size:21px;
                line-height:1.4;
                color:#1c4c36;">

                Pengajuan Peminjaman Laboratorium Baru

            </h2>


            <p style="
                font-size:14px;
                line-height:1.8;">

                Yth. Penanggung Jawab Laboratorium,

            </p>

            <p style="
                font-size:14px;
                line-height:1.8;">

                Terdapat pengajuan peminjaman
                laboratorium komputer dari Guru
                yang memerlukan pemeriksaan dan
                persetujuan Anda.

            </p>


            <!-- DETAIL PENGAJUAN -->
            <table
                cellpadding="0"
                cellspacing="0"
                style="
                    width:100%;
                    border-collapse:collapse;
                    font-size:13px;">

                <tr>
                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        Nomor Pengajuan

                    </td>

                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;
                        font-weight:bold;">

                        #{{ $pengajuan->id }}

                    </td>
                </tr>

                <tr>
                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        Nama Guru

                    </td>

                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;
                        font-weight:bold;">

                        {{ $guru->name }}

                    </td>
                </tr>

                <tr>
                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        Laboratorium

                    </td>

                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        {{ config('silabkom.lab_name') }}

                    </td>
                </tr>

                <tr>
                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        Kelas

                    </td>

                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        {{ $pengajuan->kelas }}

                    </td>
                </tr>

                <tr>
                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        Mata Pelajaran

                    </td>

                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        {{ $pengajuan->mata_pelajaran }}

                    </td>
                </tr>

                <tr>
                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        Tanggal

                    </td>

                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        {{ $pengajuan->tanggal->locale('id')->translatedFormat('d F Y') }}

                    </td>
                </tr>

                <tr>
                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        Jam Penggunaan

                    </td>

                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;">

                        {{ substr($pengajuan->jam_mulai, 0, 5) }}
                        -
                        {{ substr($pengajuan->jam_selesai, 0, 5) }}
                        WIB

                    </td>
                </tr>

                <tr>
                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;
                        vertical-align:top;">

                        Tujuan Penggunaan

                    </td>

                    <td style="
                        padding:12px;
                        border-bottom:1px solid #e9efeb;
                        line-height:1.7;
                        overflow-wrap:anywhere;">

                        {!! nl2br(e($pengajuan->tujuan)) !!}

                    </td>
                </tr>

                <tr>
                    <td style="padding:12px;">
                        Status
                    </td>

                    <td style="
                        padding:12px;
                        color:#9a6c14;
                        font-weight:bold;">

                        Menunggu Persetujuan

                    </td>
                </tr>

            </table>


            <!-- TOMBOL MENUJU SISTEM -->
            <div style="margin-top:28px;">

                <a
                    href="{{ route('pjlab.operasional.index', ['tab' => 'pengajuan']) }}"
                    style="
                        display:inline-block;
                        background:#25835b;
                        color:#ffffff;
                        padding:14px 20px;
                        border-radius:9px;
                        text-decoration:none;
                        font-weight:bold;
                        font-size:13px;">

                    Lihat Pengajuan di SI-LABKOM

                </a>

            </div>


            <p style="
                margin-top:25px;
                font-size:12px;
                line-height:1.8;
                color:#708277;">

                Silakan masuk menggunakan akun
                Penanggung Jawab Laboratorium
                untuk menyetujui atau menolak
                permohonan tersebut.

            </p>

        </div>


        <!-- FOOTER -->
        <div style="
            border-top:1px solid #e5ede7;
            background:#f8fbf9;
            padding:20px 30px;">

            <p style="
                margin:0;
                font-size:11px;
                line-height:1.7;
                color:#83938a;">

                Email ini dikirim secara otomatis oleh
                SI-LABKOM MTs Al Ittihadiyah Pekanbaru.

                Tidak perlu membalas pesan ini.

            </p>

        </div>

    </div>

</body>

</html>
