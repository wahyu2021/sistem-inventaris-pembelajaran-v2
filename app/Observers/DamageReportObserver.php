<?php

namespace App\Observers;

use App\Models\DamageReport;
use App\Models\User; // Sesuaikan jika path model User Anda berbeda
use App\Notifications\DamageReportSubmittedNotification;
use Illuminate\Support\Facades\Notification;

class DamageReportObserver
{
    /**
     * Handle the DamageReport "created" event.
     */
    public function created(DamageReport $damageReport): void
    {
        // Kirim notifikasi ke semua admin (contoh)
        $admins = User::where('role', 'admin')->get(); // Sesuaikan cara Anda mendapatkan admin
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new DamageReportSubmittedNotification($damageReport));
        }
    }
}