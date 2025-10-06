<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    public function getAddressFromLatLong($latitude, $longitude)
    {
        try {
            if (empty($latitude) || empty($longitude)) {
                throw new Exception('Latitude or Longitude is missing.');
            }

//            $apiKey = env('GOOGLE_MAPS_API_KEY');
            $apiKey = config('services.google_maps.api_key');
            if (empty($apiKey)) {
                throw new Exception('Google Maps API key is not set.');
            }

            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => $latitude . ',' . $longitude,
                'key' => $apiKey,
            ]);

            if ($response->failed()) {
                throw new Exception('Geocoding API request failed: ' . $response->body());
            }

            $data = $response->json();

            if ($data['status'] !== 'OK' || empty($data['results'])) {
                throw new Exception('No results found from Geocoding API.');
            }

            $result = $data['results'][0];
            $addressComponents = $result['address_components'];

            $address = [
                'country' => '',
                'state' => '',
                'city' => '',
                'village' => '',
                'area' => '',
                'address' => $result['formatted_address'] ?? '',
            ];

            foreach ($addressComponents as $component) {
                $types = $component['types'];

                if (in_array('country', $types)) {
                    $address['country'] = $component['long_name'];
                }

                if (in_array('administrative_area_level_1', $types)) {
                    $address['state'] = $component['long_name'];
                }

                if (in_array('locality', $types)) {
                    $address['city'] = $component['long_name'];
                }

                if (in_array('administrative_area_level_3', $types)) {
                    $address['village'] = $component['long_name'];
                }

                if (in_array('sublocality', $types) || in_array('neighborhood', $types)) {
                    $address['area'] = $component['long_name'];
                }
            }

            $formattedAddress = $result['formatted_address'] ?? '';

            if (!empty($address['area']) && !empty($formattedAddress) && strpos($formattedAddress, $address['area']) === false) {

                $parts = explode(',', $formattedAddress);

                if (count($parts) >= 2) {
                    $premise = array_shift($parts);

                    $formattedAddress = trim($premise) . ', ' . $address['area'] . ', ' . implode(',', $parts);
                } else {
                    // fallback
                    $formattedAddress = $address['area'] . ', ' . $formattedAddress;
                }
            }

            $address['address'] = $formattedAddress;

            return $address;

        } catch (Exception $e) {
             Log::error('Geocoding Error: ' . $e->getMessage());
            return null;
        }
    }
}
