<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Emails;
use App\Jobs\SendEmailJob;

class ProcessEmails extends Command
{
    protected $signature = 'app:process-emails';
    protected $description = 'Process pending emails every 10 seconds';

    public function handle()
    {
        // Lấy email chưa gửi
        $emails = Emails::all();

        foreach ($emails as $email) {
            // Dispatch job vào queue
            SendEmailJob::dispatch(
                $email->email,
                'Scheduled Email',
                '<p>This is an automated email.</p>'
            );
        }

        $this->info(count($emails) . " emails dispatched.");
    }
}
