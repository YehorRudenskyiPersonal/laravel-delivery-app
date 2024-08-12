<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DeliveryRequest; // Ensure you have the correct model imported
use App\Factories\CourierFactory; // Import the CourierFactory
use Illuminate\Support\Facades\Log;

class ResendFailedRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:resend-failed-requests {--service= : A service to resend} {--userEmail= : User email to resend failed request for the specific user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the failed request from database and send them again';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected CourierFactory $courierFactory
    ){
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = $this->option('service');
        $userEmail = $this->option('userEmail');

        // Build the query to retrieve failed requests
        $query = DeliveryRequest::where('response_status', false);

        // Apply filtering by service name if provided
        if ($service) {
            $query->where('provider_name', $service);
        }

        // Apply filtering by user email if provided
        if ($userEmail) {
            $query->where('request_content->recipient->email', $userEmail);
        }

        // Get the failed requests
        $failedRequests = $query->get();

        if ($failedRequests->isEmpty()) {
            $this->info('No failed requests found.');
            return;
        }

        // Resend each failed request
        foreach ($failedRequests as $request) {
            $this->info('Resending request ID: ' . $request->id);
            
            // Get the adapter and endpoint from the request
            $serviceName = $request->provider_name;
            $courierService = $this->courierFactory->make($serviceName);

            // Decode the request content
            $requestContent = json_decode($request->request_content, true);

            // Attempt to resend the package
            try {
                $response = $courierService->sendPackage($requestContent, resend: true);

                // Check if the response is successful
                if (isset($response['status']) && $response['status'] === true) {
                    // Update the database record
                    $request->update([
                        'response_status' => true,
                    ]);

                    $this->info('Request ID ' . $request->id . ' resent successfully.');
                } else {
                    $this->error('Request ID ' . $request->id . ' failed to resend.');
                }
            } catch (\Exception $e) {
                // Log the exception and continue
                Log::error('Error resending request ID ' . $request->id . ': ' . $e->getMessage() . ', ' . $e->getLine());
                $this->error('Error resending request ID ' . $request->id);
            }
        }
    }
}
