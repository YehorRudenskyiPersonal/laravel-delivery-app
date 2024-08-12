<?php

namespace App\Services;

use App\Contracts\CourierServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Jobs\DeliveryWriteJob;

class CourierService implements CourierServiceInterface
{

    public function __construct(
        protected string $endpoint, 
        protected $adapter
    ){}

    public function sendPackage(array $data, ?string $courierService = null, $resend = false) : array
    {
        try {
            $dataFormatted = $data;

            if(!$resend) {
                $dataFormatted = $this->adapter->formatData($data);
            }

            $response = Http::post($this->endpoint, $dataFormatted);

            $result = [
                'response' => $response,
                'message' => 'All good.',
                'code' => $response->status(),
                'status' => $response->successful()
            ];

            if ($response->failed()) {
                Log::error('Delivery request failed', [
                    'request' => $dataFormatted,
                    'response' => $response->body(),
                ]);
                $result['message'] = 'Something went wrong...';
                // Not stopping, writing failed case to DB
            }

            $writeToDb = env('WRITE_TO_THE_DATABASE', false);
            if(!$resend && $writeToDb) {
                // Added email as a field from our data because in service adapted data it's possible that email will be not provided, but in our income data we can controll it
                $deliveryData = $this->prepareDeliveryData($courierService, $data['recipient']['email'], $dataFormatted, $response->successful());
                dispatch(new DeliveryWriteJob($deliveryData));
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Delivery request exception', [
                'error' => $dataFormatted,
                'request' => $e->getMessage(),
            ]);
            return ['error' => 'Service unavailable'];
        }
    }

    protected function prepareDeliveryData(string $providerName, string $userEmail, array $dataFormatted, bool $responseStatus) : array {
        $deliveryData = [
            'provider_name' => $providerName,
            'user_email' => $userEmail,
            'request_content' => json_encode($dataFormatted),
            'response_status' => $responseStatus,
            //Add more params in the feature
        ];

        return $deliveryData;
    }
}
