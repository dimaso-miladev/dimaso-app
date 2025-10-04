<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TelegramService; // Thêm dòng này
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Thêm dòng này
use Illuminate\Validation\ValidationException; // Thêm dòng này

class ContactController extends Controller
{
    protected $telegramService;

    // Sử dụng constructor injection để nạp TelegramService
    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function sendMailContact(Request $request)
    {
        $ipAddress = $request->ip();
        try {
            $validatedData = $request->validate([
                'your_name' => 'required|string|max:255',
                'your_mail' => 'required|email',
                'your_phone' => 'required|string|max:20',
                'your_message' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $message = "<b>===Thông báo liên hệ===</b>\n\n"
                 . "Khách hàng: " . htmlspecialchars($validatedData['your_name']) . "\n"
                 . "Email: " . htmlspecialchars($validatedData['your_mail']) . "\n"
                 . "Số điện thoại: " . htmlspecialchars($validatedData['your_phone']) . "\n\n"
                 . "Nội dung: " . htmlspecialchars($validatedData['your_message']). "\n\n"
                 . "IP: " . htmlspecialchars($ipAddress);

        try {
            $response = $this->telegramService->sendMessage($message);
            return response()->json(['message' => 'success'], 200);
        } catch (\Throwable $e) {
            Log::error('Error sending message to Telegram: ' . $e->getMessage());
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
}
