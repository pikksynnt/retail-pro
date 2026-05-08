<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderNotification extends Notification
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     * Kita terima data (bisa objek Vendor atau Order)
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        // Ganti dari 'mail' ke 'database'
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * Ini data yang akan disimpan di kolom 'data' tabel notifications
     */
    public function toArray($notifiable)
    {
        // Logika pesan: Cek apakah ini notifikasi buat Vendor Aktif atau lainnya
        $message = "Ada update terbaru untuk akun/pesanan Anda.";
        $url = route('dashboard');

        if (isset($this->data->shop_name)) {
            $message = "Selamat! Toko '" . $this->data->shop_name . "' Anda telah berhasil diverifikasi oleh Admin.";
            $url = route('dashboard'); // Arahkan ke dashboard vendor
        }

        return [
            'title' => 'Retail Pro Notification',
            'messages' => $message,
            'url' => $url,
            'icon' => 'fa-bell text-primary'
        ];
    }
}