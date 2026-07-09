<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    protected $apiKey;
    protected $baseUrl = 'https://openrouter.ai/api/v1';

    public function __construct()
    {
        $this->apiKey = env('OPEN_ROUTER');
    }

    /**
     * Send a prompt to OpenRouter and get the response.
     *
     * @param string $prompt
     * @param string $model
     * @return string|null
     */
    public function chatCompletion(string $prompt, string $systemPrompt = '', string $model = 'openai/gpt-4o-mini')
    {
        $messages = [];
        
        if (!empty($systemPrompt)) {
            $messages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $prompt,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => env('APP_URL'), 
                'X-Title' => env('APP_NAME'),
            ])->timeout(120)->post($this->baseUrl . '/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'response_format' => ['type' => 'json_object'], // Request JSON format when possible
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error('OpenRouter API Error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('OpenRouter Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Translate an array of data using OpenRouter.
     *
     * @param array $data
     * @param string $targetLanguage
     * @return array
     */
    public function translateData(array $data, string $targetLanguage): array
    {
        // Load the prompt from the text file and replace the target language placeholder
        $promptTemplate = file_get_contents(resource_path('prompts/translate_invoice.txt'));
        $systemPrompt = str_replace('{targetLanguage}', $targetLanguage, $promptTemplate);

        $prompt = json_encode($data, JSON_UNESCAPED_UNICODE);

        \Illuminate\Support\Facades\Log::info('OpenRouter Payload Sent', ['prompt' => $prompt]);

        $jsonResponse = $this->chatCompletion($prompt, $systemPrompt);
        
        \Illuminate\Support\Facades\Log::info('OpenRouter Raw Response', ['response' => $jsonResponse]);

        if ($jsonResponse) {
            // Strip markdown block if present
            $jsonResponse = preg_replace('/```json\s*/', '', $jsonResponse);
            $jsonResponse = preg_replace('/```\s*/', '', $jsonResponse);
            
            $translated = json_decode(trim($jsonResponse), true);
            \Illuminate\Support\Facades\Log::info('OpenRouter Parsed', ['translated' => $translated]);
            
            if (is_array($translated)) {
                // If AI nested it inside an object
                if (isset($translated['data']) && is_array($translated['data'])) {
                    return $translated['data'];
                }
                if (isset($translated['items']) && is_array($translated['items'])) {
                    return $translated['items'];
                }
                if (isset($translated['invoiceItems']) && is_array($translated['invoiceItems'])) {
                    return $translated['invoiceItems'];
                }
                
                return $translated;
            }
        }

        // If it fails, return the original data
        return $data;
    }
}
