<?php

namespace App\Jobs;

use DeyanArdi\GanadevNotif\GanadevApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMailMediaJob implements ShouldQueue
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
    private $filename;
    private $link;
    private $mime_type;
    
    public function __construct($to, $subject, $text, $filename, $link, $mime_type)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->text = $text;
        $this->filename = $filename;
        $this->link = $link;
        $this->mime_type = $mime_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return GanadevApi::sendMailMedia($this->to, $this->subject, $this->text, $this->filename, $this->link, $this->mime_type);
    }
}
