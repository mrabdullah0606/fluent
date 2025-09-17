<?php

// Add this to your database/seeders/DatabaseSeeder.php or create a separate AdminWalletSeeder.php

namespace Database\Seeders;

use App\Models\AdminWallet;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin wallet for the main admin user
        $adminUser = User::where('role', 'admin')->first();

        if ($adminUser) {
            AdminWallet::firstOrCreate(
                ['admin_id' => $adminUser->id],
                [
                    'balance' => 0.00,
                    'total_earned' => 0.00,
                    'total_withdrawn' => 0.00,
                ]
            );

            $this->command->info("Admin wallet created for user ID: {$adminUser->id}");
        } else {
            $this->command->warn("No admin user found. Please create an admin user first.");
        }
    }
}
