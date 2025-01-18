<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereNotNull('team_id')->get();

        foreach ($users as $user) {
            Course::factory()->count(3)->create([
                'team_id' => $user->team_id,
                'user_id' => $user->id,
            ]);
        }
    }
}
