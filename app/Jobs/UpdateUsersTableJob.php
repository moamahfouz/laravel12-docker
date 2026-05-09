<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateUsersTableJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // this should take the last 10 users and update their email_verified with now
        $users = \App\Models\User::orderBy('created_at', 'desc')->take(10)->get();
        foreach ($users as $user) {
            $user->email_verified_at = now();
            $user->save();
        }
    }
}
