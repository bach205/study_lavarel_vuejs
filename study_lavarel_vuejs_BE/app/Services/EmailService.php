<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendEmail($to, $subject, $content, $filePath = null)
    {
        Mail::html($content, function ($message) use ($to, $subject, $filePath) {
            $message->to($to)
                ->subject($subject);

            if ($filePath && file_exists($filePath)) {
                $message->attach($filePath);
            }
        });
    }
}
