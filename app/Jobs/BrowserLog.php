<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Log;

class BrowserLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 120;
    protected $data = [];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ary)
    {
        $this->data = $ary;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $log = new Log;
        $log->ip = $this->data['ip'];
        $log->UA = $this->data['ua'];
        $log->header = $this->data['header'];
        $log->save();

    }
}
