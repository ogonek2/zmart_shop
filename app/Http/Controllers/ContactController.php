<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $botToken = env('TG_BOT_TOKEN');
        $chatId = env('TG_CHAT_ID');

        $messageLines = [
            '📩 Нове звернення з сайту',
            '👤 Імʼя: ' . $data['name'],
            '📞 Телефон: ' . $data['phone'],
        ];

        $trimmedMessage = trim((string) ($data['message'] ?? ''));
        $messageLines[] = '💬 Повідомлення: ' . ($trimmedMessage !== '' ? $trimmedMessage : 'не вказано');

        try {
            if (empty($botToken) || empty($chatId)) {
                throw new \RuntimeException('Telegram credentials are missing.');
            }

            $http = Http::timeout(10);

            if (! app()->environment('production')) {
                $http = $http->withoutVerifying();
            }

            $response = $http
                ->asForm()
                ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => implode("\n", $messageLines),
                ]);

            $responseBody = $response->json();

            if (! $response->ok() || ! data_get($responseBody, 'ok')) {
                $description = data_get($responseBody, 'description');
                throw new \RuntimeException($description ?: 'Telegram API error');
            }

            $successPayload = [
                'success' => true,
                'message' => 'Дякуємо! Ми зв’яжемося з вами найближчим часом.',
            ];

            if ($request->expectsJson()) {
                return response()->json($successPayload);
            }

            return back()
                ->with('contact_success', $successPayload['message']);
        } catch (\Throwable $exception) {
            Log::error('Telegram contact form error: ' . $exception->getMessage(), [
                'trace' => $exception->getTraceAsString(),
            ]);

            $errorPayload = [
                'success' => false,
                'message' => 'Сталася помилка. Спробуйте, будь ласка, пізніше.',
            ];

            if ($request->expectsJson()) {
                return response()->json($errorPayload, 500);
            }

            return back()
                ->with('contact_error', $errorPayload['message'])
                ->withInput();
        }
    }
}

