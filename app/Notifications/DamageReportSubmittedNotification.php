<?php

namespace App\Notifications;

use App\Models\DamageReport; // Sesuaikan jika path model DamageReport Anda berbeda
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DamageReportSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public DamageReport $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(DamageReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array // atau toDatabase
    {
        // Asumsi model DamageReport memiliki relasi ke Item dan User (pelapor)
        $itemName = $this->report->item ? $this->report->item->name : 'Tidak diketahui';
        $reporterName = $this->report->user ? $this->report->user->name : 'Tidak diketahui';

        return [
            'report_id' => $this->report->id,
            'item_name' => $itemName,
            'reporter_name' => $reporterName,
            'message' => "Laporan kerusakan baru untuk barang '{$itemName}' dari {$reporterName}.",
            'action_url' => route('admin.laporan.index'), // Ganti dengan route yang relevan untuk melihat laporan kerusakan
            'type' => 'damage_report_submitted',
        ];
    }
}