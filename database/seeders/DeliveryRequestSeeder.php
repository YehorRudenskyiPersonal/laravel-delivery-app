<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryRequest;

class DeliveryRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create two failed delivery requests
        DeliveryRequest::create([
            'provider_name' => 'nova_poshta',
            'user_email' => 'john1987@gmail.com',
            'request_content' => json_encode([
                'customer_name' => 'John Doe',
                'phone_number' => '+380999999999',
                'email' => 'john1987@gmail.com',
                'sender_address' => 'Some address',
                'delivery_address' => 'streets of love 29',
            ]),
            'response_status' => false,
        ]);

        DeliveryRequest::create([
            'provider_name' => 'nova_poshta',
            'user_email' => 'jane.work@gmail.com',
            'request_content' => json_encode([
                'customer_name' => 'Jane Doe',
                'phone_number' => '+380999899898',
                'email' => 'jane.work@gmail.com',
                'sender_address' => 'Some address',
                'delivery_address' => 'streets of love 28',
            ]),
            'response_status' => false,
        ]);

        // Create one successful delivery request
        DeliveryRequest::create([
            'provider_name' => 'nova_poshta',
            'user_email' => 'john1987@gmail.com',
            'request_content' => json_encode([
                'customer_name' => 'Just Guy',
                'phone_number' => '+380979989999',
                'email' => 'usual.guy@gmail.com',
                'sender_address' => 'Some address',
                'delivery_address' => 'Planet 0',
            ]),
            'response_status' => true,
        ]);
    }
}
