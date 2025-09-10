<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskSharedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $task;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $task)
    {
        $this->user = $user;
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Task Shared')
            ->text('emails.task_shared')
            ->with([
                    'user' => $this->user,
                    'task' => $this->task,
                ]);
    }
}
