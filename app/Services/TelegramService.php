<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $token;
    protected $chatId;
    protected $baseUrl;

    public function __construct()
    {
        $this->token = config('services.telegram.token');
        $this->chatId = config('services.telegram.chat_id');
        $this->baseUrl = "https://api.telegram.org/bot{$this->token}";
    }

    /**
     * Send a message to Telegram chat.
     *
     * @param  string   $message   Supports HTML
     * @return Response            HTTP client response
     *
     * @throws \InvalidArgumentException When token/chat_id is missing
     * @throws \Illuminate\Http\Client\RequestException On non-2xx HTTP status
     * @throws \RuntimeException          When Telegram returns ok=false
     */
    public function sendMessage(string $message): Response
    {
        if (empty($this->token) || empty($this->chatId)) {
            throw new \InvalidArgumentException('Telegram token/chat_id is not configured.')
            ;
        }

        $response = Http::asForm()->post("{$this->baseUrl}/sendMessage", [
            'chat_id' => $this->chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ])->throw();

        $payload = $response->json();
        if (!is_array($payload) || (array_key_exists('ok', $payload) && $payload['ok'] !== true)) {
            $desc = is_array($payload) ? ($payload['description'] ?? 'Unknown error') : 'Invalid response';
            throw new \RuntimeException('Telegram API error: ' . $desc);
        }

        return $response;
    }
}
