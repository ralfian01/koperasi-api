<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWaMessage implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $url;
    protected $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(string $url, array $payload)
    {
        $this->url = $url;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Http::asJson()
                ->timeout(120)
                ->post($this->url, $this->payload);
        } catch (\Exception $e) {
            Log::error('Failed send to whatsapp WA: ' . $e->getMessage());
        }
    }
}
