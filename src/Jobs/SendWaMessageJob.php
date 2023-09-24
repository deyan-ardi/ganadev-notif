<?php

namespace App\Jobs;

use DeyanArdi\GanadevNotif\GanadevApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWaMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $send_to;
    private $message;
    public function __construct($send_to, $message)
    {
        $this->send_to = $send_to;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return GanadevApi::sendWaMessage($this->send_to, $this->message);
    }
}
