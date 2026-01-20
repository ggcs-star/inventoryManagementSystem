<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LocationService
{
    public function getLocationByIp(string $ip): array
    {
        try {
            $response = Http::timeout(3)
                ->get("http://ip-api.com/json/{$ip}");

            if ($response->successful()) {
                return [
                    'country' => $response->json('country'),
                    'city' => $response->json('city'),
                ];
            }
        } catch (\Exception $e) {
            // silently fail
        }

        return [
            'country' => null,
            'city' => null,
        ];
    }
}
