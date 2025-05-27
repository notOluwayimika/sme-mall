<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use App\Models\Section;
use App\Models\Survey;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Survey::factory()
            ->count(5)
            ->has(
                Section::factory()
                    ->count(2)
                    ->has(
                        Question::factory()
                            ->count(3)
                            ->has(Option::factory()->count(4))
                    )
            )
            ->create();
    }
}
