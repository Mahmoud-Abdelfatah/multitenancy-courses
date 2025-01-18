<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Enums\RoleEnum;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** Create the super admin user */
        if (User::where('email', env('ADMIN_EMAIL'))->doesntExist()) {
            
            $user = User::create([
                'name' => 'Admin',
                'email' => env('ADMIN_EMAIL'),
                'password' => Hash::make(env('ADMIN_PASSWORD')),
            ]);

            $user->assignRole(RoleEnum::SUPER_ADMIN);
        }

        /** Create teams */
        $teams = Team::all();

        foreach ($teams as $team) {
             /** Create team admin */
            $admin = User::factory()->create([
                'team_id' => $team->id,
            ]);
            $admin->assignRole(RoleEnum::TENANT_ADMIN);
  
             /**create team users */
            User::factory()->count(3)->create([
                'team_id' => $team->id,
            ])->each(function ($user) {
                $user->assignRole(RoleEnum::USER);
            });
        }


    }
}
