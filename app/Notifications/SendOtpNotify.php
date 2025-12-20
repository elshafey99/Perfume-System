<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Ichtrojan\Otp\Otp;
use App\Channels\WhatsappChannel;

class SendOtpNotify extends Notification
{
    use Queueable;

    protected string $phone;
    protected string $code;

    public function __construct(string $phone)
    {
        $this->phone = $phone;

        // توليد كود OTP
        $otp = (new Otp)->generate($this->phone, 'numeric', 5, 5);
        $this->code = $otp->token;

        if (app()->environment('local')) {
            Log::info("[OTP for {$this->phone}]: {$this->code}");
        }
    }

    public function via($notifiable): array
    {
        return [WhatsappChannel::class];
    }

    public function toWhatsapp($notifiable): array
    {
        return [
            'phone' => $this->phone,
            'code'  => $this->code,
        ];
    }

    /**
     * Get the generated OTP code
     */
    public function getCode(): string
    {
        return $this->code;
    }
}