<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusChanged extends Notification
{
    use Queueable;

    protected $order;
    protected $status;

    public function __construct(Order $order, string $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order #{$this->order->id} Status Updated")
            ->line("Your order status has been updated to: {$this->status}")
            ->line("Order Total: $" . number_format($this->order->total_amount, 2))
            ->action('View Order', url("/orders/{$this->order->id}"))
            ->line('Thank you for shopping with us!');
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->status,
            'message' => "Order #{$this->order->id} status updated to {$this->status}",
        ];
    }
}
