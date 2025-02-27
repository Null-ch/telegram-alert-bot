<?php

namespace App\Services\Common;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use App\Repositories\ArchiveMessageRepository;

class OllamaService
{
    public Client $client;
    public ArchiveMessageRepository $archiveMessageRepository;
    public function __construct(
        ArchiveMessageRepository $archiveMessageRepository,
    )
    {
        $this->archiveMessageRepository = $archiveMessageRepository;
        $this->init();
    }

    private function init(): void
    {
        $this->client = new Client;
    }

    public function sendRequest(string $message)
    {
        try {
            $url = env('LLAMA_URL') . 'api/generate';
            $response = Http::withOptions([
                'timeout' => 60,
                'connect_timeout' => 20,
                'stream' => true
            ])
            ->post($url, [
                'model' => 'llama3.1',
                'prompt' => $message,
            ]);

            $content = $response->getBody()->getContents();
            preg_match_all('/"response":"([^"]*)"/', $content, $matches);
            $parts = array_filter($matches[1]);
            $result = '';

            foreach ($parts as $part) {
                $decodedPart = json_decode('"' . $part . '"', true);
                $result .= str_replace('\n', "\n", $decodedPart);
            }

            return $result;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return "Ошибка подключения: " . $e->getMessage();
        } catch (\Exception $e) {
            return "Ошибка запроса: " . $e->getMessage();
        }
    }

    public function getSummary(string $account, string $dateStart, string $dateEnd = null): string
    {
        $todayMessages = $this->archiveMessageRepository->getMessages($account, $dateStart, $dateEnd);
        $history = '';
        foreach ($todayMessages as $message) {
            $history .= 'Новый чат:' . $message->messages . "\n";
        }

        $options = 'Проанализируй сообщение. Каждый новый чат начинается со слов Новый Чат:, сообщения разделены знаком |. Составь топ 5 проблем с которыми обратились пользователи';
        return $options . ' ' . $history;
    }
}
