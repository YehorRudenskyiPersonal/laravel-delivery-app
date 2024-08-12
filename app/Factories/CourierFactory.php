<?php

namespace App\Factories;

use Exception;
use App\Services\CourierService;
use Illuminate\Contracts\Container\Container;

class CourierFactory
{
    /**
     * Create a new factory instance.
     *
     * @param Container $container
     */
    public function __construct(
        protected Container $container
    ){}

    /**
     * Create a courier service instance.
     *
     * @param string $serviceName
     * @return CourierService
     * @throws Exception
     */
    public function make(string $serviceName): CourierService
    {
        $config = config("couriers.$serviceName");

        if (!$config) {
            throw new Exception("Courier configuration for [$serviceName] not found.");
        }

        $adapterClass = $config['adapter'];
        $endpoint = $config['endpoint'];

        if (!class_exists($adapterClass)) {
            throw new Exception("Courier adapter [$adapterClass] does not exist.");
        }

        $adapter = $this->container->make($adapterClass, ['serviceName' => $serviceName]);
        
        return new CourierService($endpoint, $adapter);
    }
}
