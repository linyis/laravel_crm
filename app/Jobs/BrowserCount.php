<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Crm;

class BrowserCount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $crm;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Crm $crm)
    {
        $this->crm = $crm;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Redis::throttle('Count_increasment')->allow(2)->every(1)->then(function () {
            $this->crm->count = $this->crm->count + 1;
            $this->crm->save();
        } , function () {
            // Could not obtain lock; this job will be re-queued
            return $this->release(2);
        });
    }
}
