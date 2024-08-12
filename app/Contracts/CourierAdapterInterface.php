<?php

namespace App\Contracts;

interface CourierAdapterInterface
{
    /**
     * Convert the provided data into the format required by the courier service.
     *
     * @param array $data
     * @return array
     */
    public function formatData(array $data): array;
}
