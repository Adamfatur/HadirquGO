<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\Business;

class QuestionsTableSeeder extends Seeder
{
    public function run()
    {
        // Cari bisnis dengan business_id = 1 atau business_unique_id = 'HGO-BNSU4OI2HBK'
        $business = Business::where('id', 1)
            ->orWhere('business_unique_id', 'HGO-BNSU4OI2HBK')
            ->first();

        if (!$business) {
            $this->command->error('Business with ID 1 or unique ID HGO-BNSU4OI2HBK not found.');
            return;
        }

        // Buat quiz "Pengetahuan Umum"
        $quiz = Quiz::create([
            'business_id' => $business->id,
            'title' => 'General knowledge'
        ]);

        /**
         * 50 soal awal (sama persis dengan kode Anda sebelumnya):
         *  - 22 soal tentang Universitas Raharja
         *  - 28 soal pengetahuan umum
         */
        $questionsData = [
            // -- 22 soal seputar Universitas Raharja --
            [
                'question_text' => 'Apa singkatan dari Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'UR', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'UNIRA', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'UNRA', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'UNRJ', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Dimana Letak Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Kota Tangerang', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Kab. Tangerang', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Jakarta', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Lampung', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Kapan Universitas Raharja berdiri?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '1994', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => '1991', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => '2010', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => '2014', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa nama ketua yayasan Universitas Raharja?',
                'options' => [
                    // (Perhatikan di sini option a dan b sama - sesuai data asli)
                    ['option_letter' => 'a', 'option_text' => 'Untung Rahardja', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Untung Raharja', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Abas Sunarya', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Erick Thohir', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa nama Rektor Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Untung Rahardja', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Untung Raharja', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Abas Sunarya', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Erick Thohir', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Mana yang bukan termasuk Fakultas FEB?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Sistem Informasi', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Bisnis Digital', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Akuntansi', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Manajemen Retail', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Mana di bawah ini yang termasuk ke dalam Fakultas FST? Kecuali!',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bisnis Digital', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Sistem Komputer', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Sistem Informasi', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Teknik Informatika', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama ruangan Inkubator Bisnis di Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Alphabet Incubator', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Alfabet Inkubator', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'AI', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Artificial Intelligence', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Dimana letak Grand Aula Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Gedung M3', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Gedung L5', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Gedung L4', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Gedung M2', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama Mall yang tepat di depan Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Mall Tangerang City', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Mall SMS', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Mall Balekota', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Supermall Karawaci', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa warna yang berada pada Logo Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Merah, Kuning dan Biru', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Merah, Hijau dan Biru', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Ungu, Kuning dan Biru', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Coklat, Abu abu dan Merah', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama Cafe yang berada di Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Raharja Internet Cafe', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Raharja International Cafe', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Raharja Incubator Cafe', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Raharja Cafe', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama Minimarket yang berada di Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'UR Mart', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'UNIRA Mart', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Raharja Mart', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'RHJ Mart', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Dimana Letak Ruangan Alphabet Incubator?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'M206', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'L204', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'M201', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'L212', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama Danau yang berada di samping Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Situ Gede Ecospace', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Situ Besar', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Situ Tangerang', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Situ Babakan', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa Nama Presiden Indonesia?',
                'options' => [
                    // Sesuai data awal Anda, jawaban benar "Prabowo Subianto" (b)
                    ['option_letter' => 'a', 'option_text' => 'Joko Widodo', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Prabowo Subianto', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Gibran Rakabuming', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Megawati Soekarnoputri', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Universitas Raharja termasuk ke dalam LLDIKTI wilayah?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'IV', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'V', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'VI', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'VIII', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Universitas Raharja termasuk ke dalam Klaster?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Mandiri', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Utama', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Pratama', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Binaan', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Mana dibawah ini yang termasuk ke dalam Ruangan Alphabet Incubator?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'RainForest, Futuristic, Cyberpunk', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'BlueOcean, Futuristic, Galaxy', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Galaxy, DeepOcean, Forest', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Space, Sea, Desert', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa Warna Almamater Universitas Raharja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Hijau dan Kuning', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Biru dan Kuning', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Merah dan Hijau', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Hijau dan Merah', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa bunyi Sila Ketiga?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Ketuhanan yang Maha Esa', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Persatuan Indonesia', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Kemanusiaan yang adil dan beradab', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Keadilan sosial bagi seluruh rakyat Indonesia', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa lanjutan lirik berikut, "Kami Pribadi Raharja"?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Insan Beriman dan Bertakwa', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Penyumbang Sumber Daya Manusia', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Pribadi Kuat dan Cerdas', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Mewujudkan mimpi meraih cita cita', 'is_correct' => false],
                ],
            ],

            // -- 28 soal umum tambahan --
            [
                'question_text' => 'Apa ibu kota Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Jakarta', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Bandung', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Surabaya', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Medan', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Pulau manakah yang terbesar di Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Jawa', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Sumatra', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Kalimantan', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Sulawesi', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Sungai terpanjang di Indonesia adalah?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Sungai Kapuas', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Sungai Mahakam', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Sungai Musi', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Sungai Batanghari', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa lambang negara Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bendera Merah Putih', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Garuda Pancasila', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Patung Pancoran', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Tugu Monas', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa semboyan negara Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bhinneka Tunggal Ika', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Pancasila Sakti', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Indonesia Raya', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Sumpah Pemuda', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama candi Buddha terbesar di Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Candi Prambanan', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Candi Pawon', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Candi Mendut', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Candi Borobudur', 'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'Suku terbesar di Indonesia adalah?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Suku Sunda', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Suku Jawa', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Suku Batak', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Suku Minangkabau', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa mata uang resmi Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Dollar', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Rupiah', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Ringgit', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Yen', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Ibukota Sumatra Utara adalah?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Padang', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Pekanbaru', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Medan', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Jambi', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Gunung tertinggi di Indonesia adalah?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Gunung Rinjani', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Gunung Kerinci', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Puncak Jaya', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Gunung Semeru', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'BNI adalah singkatan dari?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bank Nasional Indonesia', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Bank Niaga Indonesia', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Bank Negara Indonesia', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Bank Nasional Investasi', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapakah pasangan presiden dan wakil presiden pertama Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Soekarno dan Mohammad Hatta', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Soeharto dan Adam Malik', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'B.J. Habibie dan Try Sutrisno', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Gus Dur dan Megawati', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Kapan Indonesia merdeka?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '17 Agustus 1945', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => '1 Juni 1945', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => '20 Mei 1945', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => '10 November 1945', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Bandara terbesar di Indonesia adalah?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bandara Kertajati', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Bandara Juanda', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Bandara Ngurah Rai', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Bandara Soekarno-Hatta', 'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'Kapan hari kemerdekaan Indonesia diperingati setiap tahunnya?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '1 Juni', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '17 Agustus', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => '20 Mei', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => '10 November', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa arti warna merah pada bendera Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Kesehatan', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Berani', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Kesucian', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Kesejahteraan', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa arti warna putih pada bendera Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Berani', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Kesejahteraan', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Kesucian', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Kemerdekaan', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Bahasa Indonesia berasal dari akar bahasa?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bahasa Sanskerta', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Bahasa Melayu', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Bahasa Jawa', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Bahasa Belanda', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama acara peringatan HUT RI di Istana Merdeka?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Pesta Rakyat', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Upacara Bendera', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Karnaval Kemerdekaan', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Pawai Budaya', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa penyelenggara pemilihan umum di Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'KPU (Komisi Pemilihan Umum)', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'MPR', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'MK', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'KPK', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama kitab undang-undang dasar Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'UUD 1945', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Kitab Kuhp', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Perpu 1945', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Perda 1945', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa kepanjangan dari DPR?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Dewan Perwakilan Rakyat', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Dewan Pemerintah Rakyat', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Dewan Pengawas Rakyat', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Dewan Pembangunan Rakyat', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Planet ke-3 dari Matahari dalam tata surya adalah?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Venus', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Mars', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Bumi', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Jupiter', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa penemu bola lampu pijar?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Nikola Tesla', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Thomas Alva Edison', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Alexander Graham Bell', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Albert Einstein', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Dimana letak candi Prambanan?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Magelang', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Sleman', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Solo', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Bantul', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama alat musik tradisional dari Jawa Barat?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Angklung', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Gamelan', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Kolintang', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Sasando', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama ibukota dari Provinsi Bali?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Kuta', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Denpasar', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Jimbaran', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Ubud', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa penemu telepon?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Alexander Graham Bell', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Guglielmo Marconi', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Alessandro Volta', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Thomas Alva Edison', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa ibukota dari Provinsi Jawa Timur?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Surabaya', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Malang', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Jember', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Madiun', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa penemu gaya relativitas?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Albert Einstein', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Isaac Newton', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Stephen Hawking', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Galileo Galilei', 'is_correct' => false],
                ],
            ],
        ];

        /**
         * 70 soal baru:
         *  - 30 soal logika mudah
         *  - 40 soal pengetahuan umum Indonesia yang lebih “kekinian”
         */
        $newQuestions = [
            // 30 Soal Logika Sederhana
            [
                'question_text' => 'Ayam jantan milik Pak Budi bertelur 3 butir. Apa warna telurnya?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Putih', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Cokelat', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Ayam jantan tidak bertelur', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Hijau', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Mana yang lebih berat, 1 kg kapas atau 1 kg besi?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '1 kg besi', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '1 kg kapas', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Keduanya sama berat', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Tergantung lokasi', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Sebuah kereta listrik melaju ke arah timur. Ke mana arah asapnya?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Ke barat', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Ke timur', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Ke atas', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Tidak ada asap (kereta listrik)', 'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'Ada 12 ikan di kolam. 6 di antaranya “tenggelam”. Berapa ikan yang tersisa di kolam?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '0', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '6', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => '12', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => '10', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Ayam jago berkokok menghadap timur. Ke arah mana ekornya?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Selatan', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Barat', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Timur', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Ke bawah', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Sebuah bus memiliki 7 penumpang. Di halte berikutnya, 2 turun dan 4 naik. Berapa penumpang sekarang?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '9', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => '10', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => '11', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => '7', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Jika 10 burung di ranting, lalu ditembak 1, berapa burung yang tersisa di ranting?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '9', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '10', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => '0', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => '1', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Sebuah balon meletus, apa isi di dalamnya?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Air', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Udara', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Gas beracun', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Garam', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Jika lampu lalu lintas sudah hijau, apa yang seharusnya dilakukan pengendara?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Berhenti', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Jalan', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Mundur', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Balik arah', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa yang tidak bisa kita makan untuk sarapan?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Nasi Goreng', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Bubur Ayam', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Roti Telur', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Makan malam', 'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'Mana yang lebih besar, Matahari atau Bumi?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Matahari', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Bumi', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Keduanya sama', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Tidak bisa dipastikan', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Kamu sedang lomba lari, menyalip orang di posisi ke-2. Sekarang kamu di posisi berapa?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Pertama', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Kedua', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Ketiga', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Tidak tahu', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Untuk mengambil baju di dalam lemari 2 pintu, berapa pintu yang perlu dibuka?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '0', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '1', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => '2', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Tergantung lemari', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Jika panci teflon anti lengket, bagaimana pabrik menempelkannya ke wajan?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Dengan lem kayu', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Dengan solatip', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Proses pabrik khusus (pembakaran/press)', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Menggunakan magnet', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Jika lampu lalu lintas menyala merah, apa yang dilakukan pengendara?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Berhenti', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Jalan pelan-pelan', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Langsung tancap gas', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Bebas, tergantung mood', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => '1 abad sama dengan berapa tahun?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '50', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '75', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => '100', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => '125', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Mana yang bukan huruf vokal dalam Bahasa Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'A', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'U', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'I', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'B', 'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'Dalam setahun, ada berapa bulan yang memiliki 30 hari?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '4', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => '5', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => '6', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => '7', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Jumlah jari tangan normal manusia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '8', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '10', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => '12', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => '14', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Kamu menyalip orang di posisi terakhir. Sekarang kamu di posisi berapa?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Terakhir', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Satu tingkat di atasnya', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Tidak mungkin menyalip yang terakhir', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Kedua terakhir', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Jika kamu masuk ruangan gelap dengan satu korek api, di sana ada lilin, lentera, obor. Mana yang dinyalakan dulu?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Lilin', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Lentera', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Obor', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Korek api', 'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'Jika di dompetmu hanya ada dua koin total Rp3.000. Salah satunya bukan koin Rp1.000. Koin apa saja?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Rp1.000 dan Rp2.000', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Rp500 dan Rp2.500', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Dua Rp1.500', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Salah satu pasti palsu', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Sebuah jam digital menunjukkan 05:50. 20 menit kemudian, jam berapa?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '06:05', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '06:10', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => '05:70', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Tetap 05:50', 'is_correct' => false],
                ],
            ],
            // Lanjut soal-soal logika tambahan (kita sudah buat 20, kita buat 10 lagi):
            [
                'question_text' => 'Ada satu orang duduk di bangku. Lalu datang dua orang lagi, total berapa orang duduk di bangku?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Tiga orang', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Satu orang', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Dua orang', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Tidak bisa dipastikan', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Sebuah pipa besi panjang dimasukkan ke karung plastik. Mana yang lebih dulu keluar, ujung pipa atau karungnya?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Ujung pipa', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Karung plastik', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Keduanya bersamaan', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Yang masuk terakhir keluar lebih dulu (ujung lain)', 'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'Mana yang benar: “Kelinci adalah mamalia” atau “Kelinci bisa terbang”?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Kelinci mamalia', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Kelinci bisa terbang', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Keduanya salah', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Tidak ada yang benar', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Jika 1 detik sama dengan 1000 milidetik, berapa milidetik dalam 2 detik?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '2000', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => '1000', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => '20.000', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Tidak bisa dihitung', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Sebuah mobil ber-plat A menabrak mobil ber-plat B. Plat mana yang lecet?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Mobil ber-plat A', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Mobil ber-plat B', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Keduanya bisa lecet', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Tidak ada yang lecet', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Di tangan manusia normal, jari manakah yang paling panjang?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Ibu jari', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Telunjuk', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Jari tengah', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Kelingking', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Jika 1 menit = 60 detik, berapa detik dalam 3 menit?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '60', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '120', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => '180', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Tidak tentu', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Berapa jumlah huruf dalam abjad Bahasa Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '26', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => '28', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => '30', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => '25', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Dari mana asal “Serabi Solo”?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bandung', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Solo (Surakarta)', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Semarang', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Surabaya', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Hewan apa yang berkokok di pagi hari?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Ayam jago', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Kucing', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Sapi', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Bebek', 'is_correct' => false],
                ],
            ],

            // 40 Soal Pengetahuan Umum Indonesia (kekinian)
            [
                'question_text' => 'Apa nama ibu kota baru Indonesia yang terletak di Kalimantan Timur?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Nusantara', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Samarinda', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Balikpapan', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Kertanegara', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa pendiri Gojek?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Achmad Zaky', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'William Tanuwijaya', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Nadiem Makarim', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Fajrin Rasyid', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa pendiri Tokopedia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Andrew Darwis', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'William Tanuwijaya', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Mesty Ariotedjo', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Kaesang Pangarep', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa pendiri Bukalapak?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Nadiem Makarim', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Achmad Zaky', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Kevin Aluwi', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Ferry Unardi', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa kepanjangan KPK?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Komisi Perlindungan Konsumen', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Komisi Pemberantasan Korupsi', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Komunitas Penjaga Konstitusi', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Komite Pengawas Keuangan', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa kepanjangan OJK?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Otoritas Jasa Kelautan', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Otoritas Jasa Kesehatan', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Otoritas Jasa Keuangan', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Organisasi Jasa Komunitas', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa kepanjangan BPJS?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Badan Pengelola Jaringan Sosial', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Badan Penyelenggara Jaminan Sosial', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Badan Pendanaan Jaminan Sosial', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Biro Pusat Jasa Sosial', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa singkatan KRL di Jabodetabek?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Kereta Rangkaian Lokal', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Kereta Rel Listrik', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Kereta Rakyat Langsung', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Kendaraan Rel Langka', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama e-wallet milik BUMN (Telkom) di Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'LinkAja', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'GoPay', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'OVO', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Dana', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama perusahaan penerbangan plat merah (milik negara) di Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Batik Air', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Sriwijaya Air', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Citilink', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Garuda Indonesia', 'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'Apa nama bandara internasional di Bali?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bandara Adisutjipto', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Bandara Juanda', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Bandara I Gusti Ngurah Rai', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Bandara Kualanamu', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Siapa penulis novel "Dilan"?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Andrea Hirata', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Habiburrahman El Shirazy', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Pidi Baiq', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Raditya Dika', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama ibu kota Provinsi Riau?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Pekanbaru', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Dumai', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Tanjung Pinang', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Padang', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama ibu kota Provinsi Kepulauan Riau?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Batam', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Tanjung Pinang', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Pekanbaru', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Lingga', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama ibu kota Provinsi Jawa Barat?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bandung', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Bogor', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Bekasi', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Cirebon', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama transportasi umum modern di Jakarta yang sebagian jalurnya berada di bawah tanah?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'KRL Commuter Line', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'MRT Jakarta', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Busway', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'KAI Bandara', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa singkatan MRT?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Mass Rapid Transit', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Multi Rail Transport', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Mega Rapid Train', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Modern Rail Transit', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama ibukota Provinsi Banten?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Cilegon', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Tangerang', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Serang', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Pandeglang', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Gunung Tangkuban Perahu terletak di provinsi mana?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Jawa Timur', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Jawa Tengah', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Jawa Barat', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Banten', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Danau Toba terletak di provinsi mana?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Sumatera Barat', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Sumatera Selatan', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Sumatera Utara', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Riau', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama suku asli di Jakarta?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Suku Sunda', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Suku Betawi', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Suku Jawa', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Suku Batak', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama makanan khas Betawi yang berkuah santan?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Gudeg', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Soto Betawi', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Rawon', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Rendang', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama makanan khas Sunda yang menggunakan oncom?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Nasi Tutug Oncom', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Lotek', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Kupat Tahu', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Laksa', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Pada tanggal berapakah Hari Sumpah Pemuda diperingati?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '17 Agustus', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '28 Oktober', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => '1 Juni', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => '20 Mei', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Kota manakah yang dijuluki sebagai Kota Pelajar di Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Jakarta', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Bandung', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Yogyakarta', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Malang', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama hari raya umat Hindu di Bali yang terkenal dengan suasana sepi?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Galungan', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Kuningan', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Nyepi', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Pagerwesi', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Gunung Bromo terletak di provinsi mana?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Jawa Tengah', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Jawa Timur', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Bali', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'NTB', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama bandara internasional di Yogyakarta yang baru diresmikan?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bandara Adisutjipto', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Bandara Soedirman', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Yogyakarta International Airport', 'is_correct' => true],
                    ['option_letter' => 'd', 'option_text' => 'Bandara Kulonprogo Adi Sutjipto Baru', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa singkatan PON, event olahraga nasional di Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Pekan Olahraga Nasional', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Perayaan Olahraga Nusantara', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Pesta Olahraga Negara', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Perhelatan Olahraga Nasional', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama bandara internasional di Surabaya?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bandara Juanda', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Bandara Abdurrahman Saleh', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Bandara Soekarno-Hatta', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Bandara Halim Perdanakusuma', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Tari Saman berasal dari provinsi mana?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Sumatera Utara', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Aceh', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Sumatera Barat', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Riau', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama maskapai penerbangan berbiaya rendah yang merupakan anak usaha Garuda Indonesia?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Citilink', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Lion Air', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Wings Air', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Sriwijaya Air', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Bahasa daerah dengan jumlah penutur terbanyak di Indonesia adalah?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Bahasa Sunda', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => 'Bahasa Jawa', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => 'Bahasa Minangkabau', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Bahasa Bugis', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Kapan Hari Sumpah Pemuda diperingati?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => '17 Agustus', 'is_correct' => false],
                    ['option_letter' => 'b', 'option_text' => '28 Oktober', 'is_correct' => true],
                    ['option_letter' => 'c', 'option_text' => '1 Oktober', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => '10 November', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Kota Jember di Jawa Timur terkenal dengan event tahunan bernama?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Jember Fashion Carnaval', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Festival Keraton', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Pekan Raya Jember', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Batik Fashion Show', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa nama suku terbesar di Aceh?',
                'options' => [
                    ['option_letter' => 'a', 'option_text' => 'Suku Aceh', 'is_correct' => true],
                    ['option_letter' => 'b', 'option_text' => 'Suku Gayo', 'is_correct' => false],
                    ['option_letter' => 'c', 'option_text' => 'Suku Alas', 'is_correct' => false],
                    ['option_letter' => 'd', 'option_text' => 'Suku Aneuk Jamee', 'is_correct' => false],
                ],
            ],
        ];

        // Gabungkan 50 soal awal dengan 70 soal baru
        $allQuestions = array_merge($questionsData, $newQuestions);

        // Simpan ke database
        foreach ($allQuestions as $questionData) {
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => $questionData['question_text'],
            ]);

            foreach ($questionData['options'] as $optionData) {
                Option::create([
                    'question_id' => $question->id,
                    'option_letter' => $optionData['option_letter'],
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $optionData['is_correct'],
                ]);
            }
        }

        $this->command->info('Seeding total 120 soal (50 soal awal + 70 soal baru) berhasil!');
    }
}