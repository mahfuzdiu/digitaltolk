<?php

namespace App\Jobs;

use App\Mail\TaskHasBeenAssignedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailForAssignedTask implements ShouldQueue
{
    use Queueable;

    public $to;
    public $title;
    public $description;


    /**
     * Create a new job instance.
     */
    public function __construct($email, $title, $description)
    {
        $this->to = $email;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->to)->send(new TaskHasBeenAssignedMail($this->title, $this->description));
    }
}
