<?php

use Illuminate\Database\Seeder;
use App\Joke;

// composer require laracasts/testdummy
//use Laracasts\TestDummy\Factory as TestDummy;

class JokesTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $faker = Faker\Factory::create();

        foreach (range(1, 30) as $index) {
          Joke::create([
            'body' => $faker->paragraph($nbSentences = 3),
            'user_id' => $faker->numberBetween($min = 1, $max = 5)
          ]);
        }
    }
}
