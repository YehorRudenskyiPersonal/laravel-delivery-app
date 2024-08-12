<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Factories\CourierFactory;
use App\Services\CourierService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourierFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_courier_service_instance()
    {
        $serviceName = 'nova_poshta';

        $courierFactory = $this->app->make(CourierFactory::class);
        $courierService = $courierFactory->make($serviceName);

        $this->assertInstanceOf(CourierService::class, $courierService);
    }
}
