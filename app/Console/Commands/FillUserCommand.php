<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command as CommandAlias;

class FillUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:users-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill users table with test users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name'     => 'Test '.$i,
                'email'    => Uuid::uuid4(),
                'password' => bcrypt('123001'.$i),
            ]);
        }

        Log::info('Fill users has been done!');

        return CommandAlias::SUCCESS;

    }
}
