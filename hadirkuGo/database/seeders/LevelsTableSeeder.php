<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelsTableSeeder extends Seeder
{
    public function run()
    {
        $levels = [
            [
                'name' => 'Pioneer',
                'description' => 'Begin your journey as a Pioneer.',
                'image_url' => 'https://drive.pastibisa.app/1731904801_673ac52145424.png',
                'stages' => 5,
                'min_points' => 0,
                'max_points' => 5000,
            ],
            [
                'name' => 'Academic Voyager',
                'description' => 'Explore the realms of consistent presence.',
                'image_url' => 'https://drive.pastibisa.app/1731905892_673ac964d51a5.jpeg',
                'stages' => 5,
                'min_points' => 5000,
                'max_points' => 15000,
            ],
            [
                'name' => 'Scholastic Trailblazer',
                'description' => 'Light the path for others with your dedication.',
                'image_url' => 'https://drive.pastibisa.app/1731905938_673ac992a7b30.png',
                'stages' => 5,
                'min_points' => 15000,
                'max_points' => 40000,
            ],
            [
                'name' => 'Intellectual Pathfinder',
                'description' => 'Chart unexplored territories of excellence.',
                'image_url' => 'https://drive.pastibisa.app/1731905901_673ac96d00623.png',
                'stages' => 5,
                'min_points' => 40000,
                'max_points' => 90000,
            ],
            [
                'name' => 'Knowledge Vanguard',
                'description' => 'Stand at the forefront of attendance mastery.',
                'image_url' => 'https://drive.pastibisa.app/1731905925_673ac985d16dd.png',
                'stages' => 5,
                'min_points' => 90000,
                'max_points' => 200000,
            ],
            [
                'name' => 'Master of Attendance',
                'description' => 'Hone your craft to perfection.',
                'image_url' => 'https://drive.pastibisa.app/1731905919_673ac97fb668b.png',
                'stages' => 5,
                'min_points' => 200000,
                'max_points' => 500000,
            ],
            [
                'name' => 'Savants of the Semester',
                'description' => 'Embody brilliance and dedication.',
                'image_url' => 'https://drive.pastibisa.app/1731906076_673aca1c63591.png',
                'stages' => 5,
                'min_points' => 500000,
                'max_points' => 1000000,
            ],
            [
                'name' => 'Attendance Luminary',
                'description' => 'Shine brightest in the galaxy of achievers.',
                'image_url' => 'https://drive.pastibisa.app/1731905911_673ac977c5d4d.png',
                'stages' => 5,
                'min_points' => 1000000,
                'max_points' => 2000000,
            ],
            [
                'name' => 'Legendary Learner',
                'description' => 'Transcend boundaries and leave a legacy.',
                'image_url' => 'https://drive.pastibisa.app/1731906133_673aca55b5ac2.png',
                'stages' => 5,
                'min_points' => 2000000,
                'max_points' => 5000000,
            ],
        ];

        foreach ($levels as $level) {
            $this->createStages($level);
        }
    }

    /**
     * Membuat tahapan untuk setiap level.
     */
    private function createStages($level)
    {
        $stages = $level['stages'];
        $minPoints = $level['min_points'];
        $maxPoints = $level['max_points'];
        $pointsRange = $maxPoints - $minPoints;
        $pointsPerStage = $pointsRange / $stages;

        for ($i = 1; $i <= $stages; $i++) {
            Level::create([
                'name' => $level['name'] . ' ' . $this->getRomanNumeral($i),
                'description' => $level['description'],
                'image_url' => $level['image_url'],
                'minimum_points' => $minPoints + ($pointsPerStage * ($i - 1)),
                'maximum_points' => $minPoints + ($pointsPerStage * $i),
            ]);
        }
    }

    /**
     * Mengonversi angka ke angka Romawi.
     */
    private function getRomanNumeral($number)
    {
        $map = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
        ];
        return $map[$number] ?? '';
    }
}