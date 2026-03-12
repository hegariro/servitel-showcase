<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class DogService
{
    protected string $baseUrl = 'https://dog.ceo/api';

    public function getRandomImage(): array
    {
        $response = Http::get("{$this->baseUrl}/breeds/image/random");

        if ($response->failed()) {
            throw new Exception('Error al consumir la API externa de Dog CEO.', 502);
        }

        return $response->json();
    }
}
