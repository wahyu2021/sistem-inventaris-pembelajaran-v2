<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotificationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterReadStatus = '';
    public $filterType = '';

    public $selectedNotificationData;
    public $isDetailModalOpen = false;

    protected $paginationTheme = 'tailwind';
    protected $listeners = ['$refresh'];

    public function render()
    {
        $user = Auth::user();
        $query = $user->notifications()->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                // Sesuaikan key di bawah ini dengan key yang ada di dalam array 'data' notifikasi Anda
                $q->where('data->message', 'like', '%' . $this->search . '%')
                    // Jika Anda memiliki 'item_name' di data notifikasi Anda:
                    ->orWhere('data->item_name', 'like', '%' . $this->search . '%')
                    // Jika Anda memiliki 'damage_description' di data notifikasi Anda:
                    ->orWhere('data->damage_description', 'like', '%' . $this->search . '%')
                    // Anda juga tetap bisa mencari berdasarkan tipe notifikasinya
                    ->orWhere('type', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterReadStatus === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($this->filterReadStatus === 'unread') {
            $query->whereNull('read_at');
        }

        if (!empty($this->filterType)) {
            $query->where('type', $this->filterType);
        }

        $notifications = $query->paginate(15);

        $notificationTypes = DatabaseNotification::select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        return view('livewire.admin.notification-manager', [
            'notifications' => $notifications,
            'notificationTypes' => $notificationTypes,
        ])->layout('layouts.app'); // Ganti ke 'layouts.admin' jika Anda punya layout khusus admin
    }

    // ... (sisa metode lainnya: markAsRead, markAsUnread, markAllAsRead, deleteNotification, deleteAllNotifications, showNotificationDetail, closeDetailModal) ...
    // Metode-metode ini tidak perlu diubah karena tidak terpengaruh oleh ada atau tidaknya 'subject'.

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAsUnread($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->update(['read_at' => null]);
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function deleteNotification($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->delete();
            session()->flash('message', 'Notifikasi berhasil dihapus.');
        }
    }

    public function deleteAllNotifications()
    {
        Auth::user()->notifications()->delete();
        session()->flash('message', 'Semua notifikasi berhasil dihapus.');
    }

    public function showNotificationDetail($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $this->selectedNotificationData = $notification->data;
            $this->isDetailModalOpen = true;
            if (!$notification->read_at) {
                $this->markAsRead($notificationId);
            }
        }
    }

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->selectedNotificationData = null;
    }
}
