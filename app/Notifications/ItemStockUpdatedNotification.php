<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Item;
use Illuminate\Support\Str;

class ItemStockUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Item $item;
    public string $actionMessage;
    public ?int $previousQuantity;
    public ?int $newQuantity;

    /**
     * Create a new notification instance.
     */
    public function __construct(Item $item, string $actionMessage, ?int $previousQuantity = null, ?int $newQuantity = null)
    {
        $this->item = $item;
        $this->actionMessage = $actionMessage;
        $this->previousQuantity = $previousQuantity;
        $this->newQuantity = $newQuantity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Kirim ke channel database
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $message = $this->actionMessage . ': "' . $this->item->name . '"';
        if ($this->newQuantity !== null) {
            $message .= '. Jumlah sekarang: ' . $this->newQuantity;
            // Anda bisa menambahkan logika untuk menampilkan penambahan jumlah jika previousQuantity ada
            // if($this->previousQuantity !== null && $this->newQuantity > $this->previousQuantity){
            //      $message .= ' (bertambah ' . ($this->newQuantity - $this->previousQuantity) . ')';
            // }
        }

        return [
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            'item_unique_code' => $this->item->unique_code,
            'message' => $message,
            'action_message' => $this->actionMessage, // e.g., "Barang baru ditambahkan"
            'new_quantity' => $this->newQuantity,
            'action_url' => route('admin.items.index'), // Atau route ke detail item jika ada: route('admin.items.show', $this->item->id)
            'icon' => 'fas fa-box-open', // Contoh ikon
        ];
    }
}