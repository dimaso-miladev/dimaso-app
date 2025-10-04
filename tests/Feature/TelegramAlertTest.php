<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class TelegramAlertTest extends TestCase
{
    /** @test */
    public function contact_message_succeeds_and_sends_to_telegram()
    {
        // Ensure logs don't trigger Telegram log handler in tests
        config(['logging.default' => 'single', 'logging.channels.stack.channels' => ['single']]);

        config([
            'services.telegram.token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);

        Http::fake([
            'https://api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $payload = [
            'your_name' => 'John Doe',
            'your_mail' => 'john@example.com',
            'your_phone' => '0123456789',
            'your_message' => 'Hello there',
        ];

        $resp = $this->postJson('/api/contact', $payload);
        $resp->assertStatus(200)->assertJson(['message' => 'success']);

        Http::assertSent(function ($request) {
            return Str::contains($request->url(), '/sendMessage')
                && ($request['chat_id'] ?? null) === '123456'
                && ($request['parse_mode'] ?? null) === 'HTML'
                && Str::contains($request['text'] ?? '', 'Thông báo liên hệ');
        });
    }

    /** @test */
    public function contact_message_handles_telegram_errors()
    {
        // Ensure logs don't trigger Telegram log handler in tests
        config(['logging.default' => 'single', 'logging.channels.stack.channels' => ['single']]);

        config([
            'services.telegram.token' => 'test-token',
            'services.telegram.chat_id' => '123456',
        ]);

        // Simulate Telegram returning ok=false but HTTP 200
        Http::fake([
            'https://api.telegram.org/*' => Http::response(['ok' => false, 'description' => 'Forbidden'], 200),
        ]);

        $payload = [
            'your_name' => 'Jane Doe',
            'your_mail' => 'jane@example.com',
            'your_phone' => '0987654321',
            'your_message' => 'Please contact me',
        ];

        $resp = $this->postJson('/api/contact', $payload);

        // Controller catches exceptions and returns 500 with generic message
        $resp->assertStatus(500)->assertJson(['message' => 'An unexpected error occurred.']);
    }
}
