<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification; // Ini adalah kelas dasar

class Notification extends DatabaseNotification
{
    /**
     * Atribut yang harus di-cast ke tipe data asli.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',         // Otomatis decode JSON dari kolom 'data'
        'read_at' => 'datetime',
    ];

    // Anda bisa menambahkan relasi atau method kustom di sini jika perlu
    // Misalnya, jika Anda ingin relasi ke User (notifiable)
    // public function user()
    // {
    //     return $this->morphTo('notifiable');
    // }
}