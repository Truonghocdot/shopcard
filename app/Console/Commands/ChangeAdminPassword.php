<?php

namespace App\Console\Commands;

use App\Constants\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangeAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:change-password {email? : The email of the admin account}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the password for an admin account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (!$email) {
            $email = $this->ask('Please enter the admin email (e.g., admin@vanhfco.com)');
        }

        $admin = User::where('email', $email)
            ->where('role', UserRole::ADMIN)
            ->first();

        if (!$admin) {
            $this->error("Admin account with email '{$email}' not found.");
            return Command::FAILURE;
        }

        $password = $this->secret('Enter the new password');
        $passwordConfirmation = $this->secret('Confirm the new password');

        if ($password !== $passwordConfirmation) {
            $this->error('Passwords do not match.');
            return Command::FAILURE;
        }

        $validator = Validator::make(['password' => $password], [
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return Command::FAILURE;
        }

        $admin->password = Hash::make($password);
        $admin->save();

        $this->info("Successfully changed the password for admin '{$email}'.");

        return Command::SUCCESS;
    }
}
