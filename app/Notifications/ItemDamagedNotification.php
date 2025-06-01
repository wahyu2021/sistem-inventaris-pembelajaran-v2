<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str; // Pastikan Str di-import
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Item;
use App\Models\DamageReport;

class ItemDamagedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Item $item;
    public DamageReport $damageReport;

    /**
     * Create a new notification instance.
     */
    public function __construct(Item $item, DamageReport $damageReport)
    {
        $this->item = $item;
        $this->damageReport = $damageReport;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Tetap kirim ke database
        // Jika Anda juga menggunakan email, tambahkan 'mail'
        // return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification. (JIKA ANDA MENGGUNAKAN EMAIL)
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->subject('Laporan Barang Rusak: ' . $this->item->name . ' (' . Str::title($this->damageReport->severity) . ')') // Tambahkan severity di subjek
    //                 ->line('Barang "' . $this->item->name . '" (' . $this->item->unique_code . ') dilaporkan rusak.')
    //                 ->line('Tingkat Kerusakan: ' . Str::title($this->damageReport->severity)) // Tambahkan baris untuk severity
    //                 ->line('Deskripsi kerusakan: ' . $this->damageReport->description)
    //                 ->action('Lihat Detail Laporan', route('admin.damages.index')); // Sesuaikan route jika perlu ke detail spesifik
    // }

    /**
     * Get the array representation of the notification. (Untuk channel database)
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        // Modifikasi pesan utama untuk menyertakan tingkat kerusakan
        $message = 'Barang "' . $this->item->name . '" dilaporkan rusak dengan tingkat kerusakan: ' . Str::title($this->damageReport->severity) . '.';

        return [
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            'item_unique_code' => $this->item->unique_code,
            'damage_report_id' => $this->damageReport->id,
            'damage_description' => Str::limit($this->damageReport->description, 100),
            'damage_severity' => $this->damageReport->severity, // <-- TAMBAHKAN FIELD BARU INI
            'message' => $message, // Pesan utama sekarang menyertakan severity
            'action_url' => route('admin.damages.index'), // Tetap, atau arahkan ke detail laporan spesifik
            'action_text' => 'Lihat Laporan Kerusakan', // Contoh teks aksi yang lebih spesifik
            'icon' => 'fas fa-exclamation-triangle', // Ganti ikon jika perlu, misal yang lebih menunjukkan kerusakan
        ];
    }
}