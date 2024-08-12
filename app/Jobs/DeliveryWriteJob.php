<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\DeliveryRequest;
use App\Events\DeliveryProvidedEvent;
use Illuminate\Support\Facades\Log;

class DeliveryWriteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param array $deliveryData
     * @return void
     */
    public function __construct(
        protected array $deliveryData
    ){}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DeliveryRequest::create($this->deliveryData);

            // Dispatch the event
            event(new DeliveryProvidedEvent($this->deliveryData));
        }catch (\Exception $e) {
            Log::error('Delivery write job failed', [
                'data' => $this->deliveryData
            ]);
        }
    }
}
