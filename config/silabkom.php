<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Identitas Sistem
    |--------------------------------------------------------------------------
    */

    'name' => 'SI-LABKOM',

    'school' => 'MTs Al Ittihadiyah Pekanbaru',

    /*
    |--------------------------------------------------------------------------
    | Email Penerima Pengajuan Laboratorium
    |--------------------------------------------------------------------------
    |
    | Diambil dari PJLAB_NOTIFICATION_EMAIL pada .env.
    | Tidak memerlukan password Gmail penerima.
    |
    */

    'pjlab_email' => env(
        'PJLAB_NOTIFICATION_EMAIL',
        'pjlabkommtsalittihadiyapku@gmail.com'
    ),

    /*
    |--------------------------------------------------------------------------
    | Nama Laboratorium
    |--------------------------------------------------------------------------
    */

    'lab_name' => 'Lab Komputer 1',

];
