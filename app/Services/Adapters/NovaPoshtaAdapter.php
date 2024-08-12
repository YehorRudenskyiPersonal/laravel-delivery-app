<?php

namespace App\Services\Adapters;

class NovaPoshtaAdapter extends BaseCourierAdapter
{
    public function __construct(string $serviceName)
    {
        parent::__construct($serviceName);
    }


    public function formatData(array $data): array
    {
        return [
            'customer_name' => $data['recipient']['name'],
            'phone_number' => $data['recipient']['phone'],
            'email' => $data['recipient']['email'],
            'sender_address' => $this->senderAddress,
            'delivery_address' => $data['recipient']['address'],
        ];
    }
}
