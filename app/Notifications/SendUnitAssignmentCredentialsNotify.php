<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Channels\WhatsappChannel;

class SendUnitAssignmentCredentialsNotify extends Notification
{
    use Queueable;

    protected string $phone;
    protected string $memberId;
    protected string $accessCode;
    protected string $propertyCode;

    public function __construct(string $phone, string $memberId, string $accessCode, string $propertyCode)
    {
        $this->phone = $phone;
        $this->memberId = $memberId;
        $this->accessCode = $accessCode;
        $this->propertyCode = $propertyCode;
    }

    public function via($notifiable): array
    {
        return [WhatsappChannel::class];
    }

    public function toWhatsapp($notifiable): array
    {
        // Create message with credentials
        // Note: custom_code in beon.chat may have length limit, so we keep it concise
        $message = "مرحباً بك في Bayt-Link\n";
        $message .= "رقم العضوية: {$this->memberId}\n";
        $message .= "كود الدخول: {$this->accessCode}\n";
        $message .= "كود العقار: {$this->propertyCode}";

        return [
            'phone' => $this->phone,
            'code' => $message, // Using code field to send the message via custom_code
        ];
    }
}