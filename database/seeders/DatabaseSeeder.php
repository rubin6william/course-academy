<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $courses = Course::factory()
            ->count(200)
            ->sequence(fn ($sequence) => [
                'description' => 'Course ' . $sequence->index,
            ])
            ->create();

        $users = User::factory()
            ->count(5)
            ->create();

        $chunk1 = $courses->splice(0, 50)->pluck('id')->all();
        $chunk2 = $courses->splice(50, 20)->pluck('id')->all();
        $chunk3 = $courses->splice(70, 40)->pluck('id')->all();

        $user1 = $users->get(0);

        $now = Carbon::now();

        $user1->enrolments()->attach($chunk1, [
            'completion_status' => 'not_started',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $user1->enrolments()->attach($chunk2, [
            'completion_status' => 'in_progress',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $user1->enrolments()->attach($chunk3, [
            'completion_status' => 'completed',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $user2 = $users->get(1);

        $user2->enrolments()->attach($chunk1, [
            'completion_status' => 'completed',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $user2->enrolments()->attach($chunk2, [
            'completion_status' => 'not_started',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $user3 = $users->get(2);

        $user3->enrolments()->attach($chunk1, [
            'completion_status' => 'in_progress',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $user3->enrolments()->attach($chunk3, [
            'completion_status' => 'completed',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
