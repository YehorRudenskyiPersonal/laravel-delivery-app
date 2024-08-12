<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Adapters\NovaPoshtaAdapter;

class NovaPoshtaAdapterTest extends TestCase
{
    /** @test */
    public function it_formats_data_correctly()
    {
        $adapter = new NovaPoshtaAdapter('nova_poshta');

        $inputData = [
            'package' => [
                'width' => 10.5,
                'height' => 5.2,
                'length' => 15.0,
                'weight' => 2.5,
            ],
            'recipient' => [
                'name' => 'John Doe',
                'phone' => '+380950000000',
                'email' => 'johndoe@example.com',
                'address' => '123 Main Street',
            ],
            'service' => 'nova_poshta',
        ];

        $expectedOutput = [
            'customer_name' => 'John Doe',
            'phone_number' => '+380950000000',
            'email' => 'johndoe@example.com',
            'sender_address' => config('couriers.nova_poshta.sender_address'),
            'delivery_address' => '123 Main Street',
        ];

        $this->assertEquals($expectedOutput, $adapter->formatData($inputData));
    }
}
