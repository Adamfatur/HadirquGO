<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reward;

class RewardsTableSeeder extends Seeder
{
    public function run()
    {
        $rewards = [
            [
                'name' => '1000 Points',
                'type' => 'point',
                'quantity' => 100,
                'probability' => 0.10,
                'image' => 'https://drive.pastibisa.app/1736766597_6784f4854efdb.png',
                'deskripsi' => 'Unlock epic power-ups with this massive point boost!',
            ],
            [
                'name' => '500 Points',
                'type' => 'point',
                'quantity' => 200,
                'probability' => 0.50,
                'image' => 'https://drive.pastibisa.app/1736766597_6784f4854efdb.png',
                'deskripsi' => 'Level up faster with this generous point reward!',
            ],
            [
                'name' => '100 Points',
                'type' => 'point',
                'quantity' => 500,
                'probability' => 1.00,
                'image' => 'https://drive.pastibisa.app/1736766597_6784f4854efdb.png',
                'deskripsi' => 'Boost your score and dominate the leaderboard!',
            ],
            [
                'name' => '50 Points',
                'type' => 'point',
                'quantity' => 1000,
                'probability' => 5.00,
                'image' => 'https://drive.pastibisa.app/1736766597_6784f4854efdb.png',
                'deskripsi' => 'A quick boost to keep you in the game!',
            ],
            [
                'name' => '25 Points',
                'type' => 'point',
                'quantity' => 2000,
                'probability' => 10.00,
                'image' => 'https://drive.pastibisa.app/1736766597_6784f4854efdb.png',
                'deskripsi' => 'Small but mighty! Every point counts!',
            ],
            [
                'name' => '10 Points',
                'type' => 'point',
                'quantity' => 5000,
                'probability' => 25.00,
                'image' => 'https://drive.pastibisa.app/1736766597_6784f4854efdb.png',
                'deskripsi' => 'A little reward to keep you motivated!',
            ],
            [
                'name' => '2 Points',
                'type' => 'point',
                'quantity' => 10000,
                'probability' => 58.40,
                'image' => 'https://drive.pastibisa.app/1736766597_6784f4854efdb.png',
                'deskripsi' => 'Every step counts on your journey to victory!',
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}