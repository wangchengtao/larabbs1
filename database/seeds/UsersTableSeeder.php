<?php

use Illuminate\Database\Seeder;

use \App\Models\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Faker\Generator::class);

        // 头像假数据
        $avatars = [
            'https://cdn.learnku.com/uploads/images/201710/14/1/s5ehp11z6s.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/Lhd1SHqu86.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/LOnMrqbHJn.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/xAuDMxteQy.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png',
            'https://cdn.learnku.com/uploads/images/201710/14/1/NDnzMutoxX.png',
        ];

        $users = factory(User::class)
            ->times(10)
            ->make()
            ->each(function ($user) use ($faker, $avatars)
            {
                $user->avatar = $faker->randomElement($avatars);
            });

        // 让隐藏字段可见
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        DB::table('users')->insert($user_array);

        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'wangct';
        $user->email = '915129420@qq.com';
        $user->avatar = 'https://cdn.learnku.com/uploads/avatars/20121_1511515154.jpeg!/both/380x380';
        $user->save();

        $user->assignRole('Founder');

        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
