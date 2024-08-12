<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliveryRequest;
use App\Factories\CourierFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    public function __construct(
        protected CourierFactory $courierFactory
    ){}

    public function sendPackage(DeliveryRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $service = $data['service'] ?? env('DEFAULT_COURIER_SERVICE');

            if (empty($service)) {
                Log::error('Empty courier service');
                return response()->json(['error' => 'Courier not specified and no default is set.'], 400);
            }

            $courierService = $this->courierFactory->make($service);
            $response = $courierService->sendPackage($data, $service);

            return response()->json(['message' => $response['message'], 'response_info' => $response['response']->getBody(), 'code' => $response['code']], $response['code']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
