<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        $faker = app(Faker\Generator::class);
        $topic_ids = \App\Models\Topic::all()->pluck('id')->toArray();
        $user_ids = \App\Models\User::all()->pluck('id')->toArray();

        $replys = factory(Reply::class)
            ->times(100)
            ->make()
            ->each(function ($reply) use ($faker, $topic_ids, $user_ids) {
                $reply->topic_id = $faker->randomElement($topic_ids);
                $reply->user_id = $faker->randomElement($user_ids);
            });

        Reply::insert($replys->toArray());
    }

}

