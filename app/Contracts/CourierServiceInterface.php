<?php

namespace App\Contracts;


interface CourierServiceInterface
{
    /**
     * Send package data to the courier service.
     *
     * @param array $data
     * @return array
     */
    public function sendPackage(array $data): array;
}
