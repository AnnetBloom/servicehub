<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TestRedisJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        logger()->info('TestRedisJob started', [
            'time' => now()->toDateTimeString(),
        ]);

        // if ($this->attempts() < 3) {
        //     throw new \RuntimeException('TestRedisJob finished successfully');
        // }

        

        sleep(30);

        logger()->info('job finished');
    }
}
