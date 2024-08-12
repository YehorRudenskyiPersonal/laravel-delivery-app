<?php
use App\Services\Adapters\NovaPoshtaAdapter;

return [
    'nova_poshta' => [
        'endpoint' => env('NOVA_POSHTA_ENDPOINT', 'https://novaposhta.test/api/delivery'),
        'sender_address' => env('NOVA_POSHTA_SENDER_ADDRESS', 'Some address'),
        'adapter' => NovaPoshtaAdapter::class,
    ],
    'test' => [
        'endpoint' => env('TEST_ENDPOINT', 'http://host.docker.internal/api/test'),
        'sender_address' => env('NOVA_POSHTA_SENDER_ADDRESS', 'Some address'),
        'adapter' => NovaPoshtaAdapter::class,
    ],
    //Add new delivery services here
];
