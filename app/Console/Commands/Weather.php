<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Services\Common\OllamaService;
use App\Services\Common\WeatherService;
use App\Services\Telegram\TelegramBotTestService;
use Carbon\Carbon;

class Weather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public WeatherService $weatherService;
    public TelegramBotTestService $telegramBotTestService;
    public OllamaService $ollamaService;
    public function __construct(
        WeatherService $weatherService,
        TelegramBotTestService $telegramBotTestService,
        OllamaService $ollamaService,
    ) {
        parent::__construct();
        $this->weatherService = $weatherService;
        $this->telegramBotTestService = $telegramBotTestService;
        $this->ollamaService = $ollamaService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $botToken = env('TELEGRAM_TEST_BOT_TOKEN');
        $chatId = env('TELEGRAM_FUN_GROUP');

        $cities = [
            'novosibirsk',
            'sankt-peterburg',
            'stupino'
        ];

        $greeting = $this->getGreeting();
        // $greeting = $this->ollamaService->sendRequest('Напиши один вариант короткого приветствия');
        $response = $greeting . "\n\n";
        foreach ($cities as $city) {
            $weatherData = $this->weatherService->getCurrentWeather($city);
            $response .= "Текущая погода в городе {$weatherData['name']}:\n" .
                "Температура: {$weatherData['main']['temp']}°C\n" .
                "Ощущается как: {$weatherData['main']['feels_like']}°C\n" .
                "Влажность: {$weatherData['main']['humidity']}%\n" .
                "Скорость ветра: {$weatherData['wind']['speed']} м/с\n" .
                "Погодные условие: {$weatherData['weather'][0]['description']}\n\n";
        }

        $response = $client->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $response,
                'parse_mode' => 'HTML'
            ]
        ]);
        $this->info('Прогноз успешно отправлен!');
    }
    public function getGreeting()
    {
        $currentTime = Carbon::now();
        if ($currentTime->hour < 12) {
            return "Доброе утро!";
        } else {
            return "Добрый день!";
        }
    }
}
