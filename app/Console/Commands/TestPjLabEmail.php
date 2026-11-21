<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TestPjLabEmail extends Command
{
    /*
    |--------------------------------------------------------------------------
    | NAMA PERINTAH
    |--------------------------------------------------------------------------
    */

    protected $signature = 'silabkom:test-email';

    /*
    |--------------------------------------------------------------------------
    | DESKRIPSI
    |--------------------------------------------------------------------------
    */

    protected $description =
        'Menguji pengiriman email SI-LABKOM ke PJ Lab';

    /*
    |--------------------------------------------------------------------------
    | PROSES PENGIRIMAN
    |--------------------------------------------------------------------------
    */

    public function handle(): int
    {
        $email = config(
            'silabkom.pjlab_email'
        );

        $mailer = config('mail.default');

        if (
            !is_string($email) ||
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $this->error(
                'Email PJ Lab tidak valid.'
            );

            return self::FAILURE;
        }

        if ($mailer !== 'smtp') {

            $this->error(
                'MAIL_MAILER harus smtp untuk tes pengiriman nyata.'
            );

            return self::FAILURE;
        }

        $this->info(
            'Memulai pengujian SMTP SI-LABKOM.'
        );

        $this->line(
            'Server: ' .
            config('mail.mailers.smtp.host')
        );

        $this->line(
            'Penerima: ' . $email
        );

        try {

            Mail::raw(
                "Halo Penanggung Jawab Laboratorium.\n\n" .
                "Ini adalah email percobaan otomatis dari SI-LABKOM MTs Al Ittihadiyah Pekanbaru.\n\n" .
                "Jika pesan ini diterima, konfigurasi SMTP sudah dapat digunakan untuk mengirim notifikasi pengajuan peminjaman laboratorium.\n\n" .
                "Waktu pengujian: " .
                now()
                    ->timezone('Asia/Jakarta')
                    ->format('d/m/Y H:i:s') .
                " WIB",

                function ($message) use ($email) {

                    $message
                        ->to($email)
                        ->subject(
                            'TES EMAIL SI-LABKOM'
                        );

                }
            );

            $this->info(
                'Email diterima oleh server SMTP.'
            );

            $this->comment(
                'Periksa Inbox, Spam, dan log transaksi Brevo untuk memastikan email sampai.'
            );

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error(
                'Pengiriman email gagal.'
            );

            $this->error(
                $e->getMessage()
            );

            return self::FAILURE;
        }
    }
}
