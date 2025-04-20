<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment Failed - Action Required')
            ->greeting('Hello!')
            ->line('We were unable to process your recent payment for your Realmsz subscription.')
            ->line('Your subscription is currently marked as past due.')
            ->action('Update Payment Method', url('/subscription/update-payment'))
            ->line('Please update your payment information to avoid service interruption.')
            ->line('If you need assistance, please contact our support team.');
    }
} 