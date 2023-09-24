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
    private $to;
    private $subject;
    private $text;
    public function __construct($to, $subject, $text)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return GanadevApi::sendMailMessage($this->to, $this->subject, $this->text);
    }
}
