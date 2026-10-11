<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\RsvpConfirmation;


class SendRsvpEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user,
         public Event $event)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user)->send(
            new RsvpConfirmation($this->user, $this->event)
        );
    }
}
