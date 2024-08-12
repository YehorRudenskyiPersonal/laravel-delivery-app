<?php

namespace App\Services\Adapters;

use App\Contracts\CourierAdapterInterface;

abstract class BaseCourierAdapter implements CourierAdapterInterface
{
    protected $senderAddress;

    public function __construct(
        protected string $serviceName
    ){
        $this->senderAddress = config("couriers.{$this->serviceName}.sender_address");
    }
    
    /**
     * Format data to a common structure.
     * 
     * Override this method to adapt data to specific courier service.
     *
     * @param array $data
     * @return array
     */
    abstract public function formatData(array $data): array;
}
