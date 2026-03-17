<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class WhatsAppService
{
    protected Client $client;
    protected string $from;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $this->from = 'whatsapp:' . config('services.twilio.whatsapp_from');
    }

    /**
     * Send a WhatsApp message to the given phone number.
     *
     * @param  string  $to    Phone number in E.164 format (e.g. +50212345678)
     * @param  string  $body  Message body
     * @return bool
     */
    public function sendMessage(string $to, string $body): bool
    {
        // Normalize phone number to E.164 format if needed
        $to = $this->normalizePhone($to);

        if (empty($to)) {
            Log::warning('WhatsAppService: empty phone number, message not sent.');
            return false;
        }

        try {
            $this->client->messages->create(
                'whatsapp:' . $to,
                [
                    'from' => $this->from,
                    'body' => $body,
                ]
            );

            Log::info("WhatsApp message sent to {$to}");
            return true;
        } catch (\Exception $e) {
            Log::error("WhatsApp send error to {$to}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Normalize a phone number to E.164 format.
     * If the number already starts with '+', it is returned as-is.
     * Otherwise, a '+' is prepended.
     */
    protected function normalizePhone(string $phone): string
    {
        // Remove spaces, dashes, parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        if (empty($phone)) {
            return '';
        }

        // If it doesn't start with '+', prepend it
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }
}
