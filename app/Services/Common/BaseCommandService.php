<?php

namespace App\Services\Common;

use App\Enums\Command;

class BaseCommandService
{
    public WeatherService $weatherService;
    public function __construct(
        WeatherService $weatherService
    ) {
        $this->weatherService = $weatherService;
    }

    public function handle(Command $command): ?string
    {
        return match ($command) {
            Command::weather => $this->getDefaultWeather(),
            default => null,
        };
    }

    public function getDefaultWeather()
    {
        $response = '';
        $cities = [
            'novosibirsk',
            'sankt-peterburg',
            'stupino'
        ];
    
        foreach ($cities as $city) {
            $weatherData = $this->weatherService->getCurrentWeather($city);
            $response .= "Текущая погода в {$weatherData['name']}:\n" . "Температура: {$weatherData['main']['temp']}°C\n" . "Ощущается как: {$weatherData['main']['feels_like']}°C\n" . "Влажность: {$weatherData['main']['humidity']}%\n" . "Скорость ветра: {$weatherData['wind']['speed']} м/с\n" . "Погодное условие: {$weatherData['weather'][0]['description']}\n\n";
        }

        return $response;
    }
}
