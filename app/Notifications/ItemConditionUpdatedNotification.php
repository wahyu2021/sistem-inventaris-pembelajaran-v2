<?php

namespace App\Notifications;

use App\Models\Item; // Sesuaikan jika path model Item Anda berbeda
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemConditionUpdatedNotification extends Notification implements ShouldQueue // Opsional: implement ShouldQueue jika ingin notifikasi di-queue
{
    use Queueable;

    public Item $item;
    public string $previousCondition; // Untuk menyimpan kondisi sebelumnya jika diperlukan

    /**
     * Create a new notification instance.
     */
    public function __construct(Item $item, string $previousCondition = null)
    {
        $this->item = $item;
        $this->previousCondition = $previousCondition;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Kita akan simpan ke database
        // Anda bisa menambahkan channel lain seperti 'mail' jika perlu
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array // atau toDatabase
    {
        $message = "Kondisi barang '{$this->item->name}'";
        if ($this->item->unique_code) {
            $message .= " ({$this->item->unique_code})";
        }
        $message .= " telah diubah menjadi '{$this->item->condition}'.";
        
        // Jika ingin menyertakan kondisi sebelumnya:
        // if ($this->previousCondition) {
        //     $message .= " (Sebelumnya: {$this->previousCondition}).";
        // }

        return [
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            'message' => $message,
            'action_url' => route('admin.inventaris.index'), // Ganti dengan route yang relevan untuk melihat item
            'type' => 'item_condition_updated',
        ];
    }
}