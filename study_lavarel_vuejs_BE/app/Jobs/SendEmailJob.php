<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralMail;
use Throwable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3; // số lần retry
    public $timeout = 60; // timeout 60s

    protected $email;
    protected $subject;
    protected $content;

    public function __construct($email, $subject, $content)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function handle()
    {
        Mail::to($this->email)
        ->send(new GeneralMail($this->subject, $this->content));
    }

    public function failed(Throwable $exception)
    {
        \Log::error("SendEmailJob failed for {$this->email}: " . $exception->getMessage());
    }
}

