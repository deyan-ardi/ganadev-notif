<?php

namespace App\Jobs;

use DeyanArdi\GanadevNotif\GanadevApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWaMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $send_to;
    private $file;
    private $type;
    private $other;
    
    public function __construct($send_to, $file, $type, $other = null)
    {
        $this->send_to = $send_to;
        $this->file = $file;
        $this->type = $type;
        $this->other = $other;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return GanadevApi::sendWaMedia($this->send_to, $this->file, $this->type, $this->other);
    }
}
