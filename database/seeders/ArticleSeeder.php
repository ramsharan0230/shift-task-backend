<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        $userIds = User::pluck('id')->toArray();

        if (empty($userIds)) {
            throw new \Exception('No users found. Please run the UserSeeder first.');
        }

        for ($i = 0; $i < 100; $i++) {
            Article::create([
                "title" => $faker->sentence(),
                "status" => $faker->boolean(),
                "user_id" => $faker->randomElement($userIds),
            ]);
        }
    }
}
