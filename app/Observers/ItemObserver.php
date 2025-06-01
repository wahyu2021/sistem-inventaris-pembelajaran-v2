<?php

namespace App\Observers;

use App\Models\Item;
use App\Models\User; // Sesuaikan jika path model User Anda berbeda
use App\Notifications\ItemConditionUpdatedNotification;
use Illuminate\Support\Facades\Notification;

class ItemObserver
{
    /**
     * Handle the Item "updated" event.
     */
    public function updated(Item $item): void
    {
        // Cek apakah field 'condition' berubah dan apakah nilai barunya adalah salah satu kondisi "rusak"
        if ($item->isDirty('condition')) {
            $damagedConditions = ['rusak ringan', 'rusak berat', 'perlu investigasi']; // Definisikan kondisi yang dianggap rusak
            $previousCondition = $item->getOriginal('condition');

            if (in_array(strtolower($item->condition), $damagedConditions) && !in_array(strtolower($previousCondition), $damagedConditions)) {
                // Kirim notifikasi ke semua admin (contoh)
                $admins = User::where('role', 'admin')->get(); // Sesuaikan cara Anda mendapatkan admin
                if ($admins->isNotEmpty()) {
                    Notification::send($admins, new ItemConditionUpdatedNotification($item, $previousCondition));
                }
            }
        }
    }
}