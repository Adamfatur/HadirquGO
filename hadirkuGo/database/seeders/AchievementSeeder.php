<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        // Achievement: Daily MP
        Achievement::create([
            'name' => 'Daily MP',
            'image' => 'https://drive.pastibisa.app/1731986547_673c0473e25f7.png',
            'description' => 'Awarded to the first person to arrive, showcasing exceptional punctuality and dedication.'
        ]);

        // Achievement: Longest Activity
        Achievement::create([
            'name' => 'Longest Activity',
            'image' => 'https://drive.pastibisa.app/1732006066_673c50b2ad696.png',
            'description' => 'Awarded to the individual with the longest active participation, demonstrating endurance and commitment.'
        ]);

        // Achievement: Adventure Student
        Achievement::create([
            'name' => 'Adventure Student',
            'image' => 'https://drive.pastibisa.app/1732006059_673c50ab85e5e.png',
            'description' => 'Awarded to the explorer who visits the most unique locations, showcasing curiosity and a spirit of adventure.'
        ]);
    }
}

