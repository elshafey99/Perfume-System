<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappChannel
{
    /**
     * إعدادات beon.chat
     */
    protected string $apiBase = 'https://v3.api.beon.chat/api/v3';
    protected string $token   = 'Vg0k1T2VVs4QPvj4QvRXC0MH8oFQbldOGyctDIOiMroypouxcWlds41p2oTm';

    /**
     * يرسل OTP عبر beon.chat باستخدام form-data.
     *
     * يدعم طريقتين لاستخراج الداتا من النوتيفكيشن:
     * - toBeon($notifiable)  => المفضّل
     * - toWhatsapp($notifiable) => توافقًا مع الكود القديم
     *
     * توقُّع الداتا من النوتيفكيشن:
     *   [
     *     'phone' => '+2010...',   // إجباري
     *     'code'  => '8807',       // إجباري
     *     'name'  => 'fisal',      // اختياري
     *     'type'  => 'sms',        // اختياري (sms | whatsapp)
     *     'lang'  => 'ar',         // اختياري
     *   ]
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toBeon') && !method_exists($notification, 'toWhatsapp')) {
            // لا توجد داتا للإرسال
            return;
        }

        // أعطي أولوية لـ toBeon ولو مش موجودة أستخدم toWhatsapp (توافقًا مع القديم)
        $message = method_exists($notification, 'toBeon')
            ? $notification->toBeon($notifiable)
            : $notification->toWhatsapp($notifiable);

        if (!is_array($message) || !isset($message['phone'], $message['code'])) {
            Log::error('رسالة OTP غير صالحة (مطلوب phone & code).', ['message' => $message]);
            return;
        }

        // تجهيز الحقول المطلوبة لـ beon.chat
        $phone = $this->normalizeE164($message['phone']);
        $name  = $message['name'] ?? ($notifiable->name ?? 'Bayt-Link');
        $code  = (string) $message['code'];

        $url = rtrim($this->apiBase, '/') . '/messages/otp';

        try {
            $response = Http::asForm()
                ->timeout(15)
                ->withHeaders([
                    'beon-token' => $this->token,
                    'Accept'     => 'application/json',
                ])
                ->post($url, [
                    'phoneNumber' => $phone,
                    'name'        => $name,
                    'type'        => 'sms',  // sms : whatsapp
                    'lang'        => 'ar',  // ar
                    'custom_code' => $code,  // 8807
                ]);

            if ($response->failed()) {
                Log::error('❌ فشل إرسال OTP عبر beon.chat', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return;
            }
        } catch (\Throwable $e) {
            Log::error('📛 Beon OTP Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * تطبيع الرقم لصيغة E.164 البسيطة:
     * - يضمن وجود + في البداية
     */
    protected function normalizeE164(string $phone): string
    {
        $phone = trim($phone);
        if (strpos($phone, '+') !== 0) {
            $phone = '+' . ltrim($phone, '+');
        }
        return preg_replace('/[\s\-()]/', '', $phone);
    }
}