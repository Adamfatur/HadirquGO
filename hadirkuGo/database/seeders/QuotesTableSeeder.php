<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quote;

class QuotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quotes = [
            ["quote" => "Kesuksesan adalah hasil dari kesempurnaan, kerja keras, belajar dari kegagalan, loyalitas, dan ketekunan.", "author" => "Colin Powell"],
            ["quote" => "Jangan pernah menyerah ketika kamu masih mampu berusaha lagi. Tidak ada kata berakhir sampai kamu berhenti mencoba.", "author" => "Brian Dyson"],
            ["quote" => "Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas.", "author" => "Henry Ford"],
            ["quote" => "Belajar adalah investasi terbaik yang bisa Anda lakukan untuk diri sendiri.", "author" => "Anonim"],
            ["quote" => "Jangan pernah berhenti belajar, karena hidup tidak pernah berhenti mengajarkan.", "author" => "Anonim"],
            ["quote" => "Belajar adalah kunci untuk membuka pintu kesuksesan.", "author" => "Anonim"],
            ["quote" => "Pendidikan adalah senjata paling ampuh untuk mengubah dunia.", "author" => "Nelson Mandela"],
            ["quote" => "Belajar tidak pernah melelahkan pikiran.", "author" => "Leonardo da Vinci"],
            ["quote" => "Orang yang berhenti belajar akan menjadi pemilik masa lalu. Orang yang terus belajar akan menjadi pemilik masa depan.", "author" => "Mario Teguh"],
            ["quote" => "Belajar adalah awal dari kekayaan. Belajar adalah awal dari kesehatan. Belajar adalah awal dari spiritualitas. Mencari dan belajarlah terlebih dahulu, semua hal lainnya akan datang.", "author" => "Jim Rohn"],
            ["quote" => "Jangan takut untuk mengambil langkah besar ketika diperlukan. Anda tidak bisa melompati jurang dengan dua lompatan kecil.", "author" => "David Lloyd George"],
            ["quote" => "Orang bijak belajar ketika mereka bisa. Orang bodoh belajar ketika mereka terpaksa.", "author" => "Arthur Wellesley"],
            ["quote" => "Belajar adalah proses seumur hidup. Jangan berhenti sampai Anda mencapai tujuan Anda.", "author" => "Anonim"],
            ["quote" => "Semakin banyak Anda membaca, semakin banyak hal yang Anda ketahui. Semakin banyak Anda belajar, semakin banyak tempat yang Anda tuju.", "author" => "Dr. Seuss"],
            ["quote" => "Jangan biarkan apa yang tidak bisa Anda lakukan mengganggu apa yang bisa Anda lakukan.", "author" => "John Wooden"],
            ["quote" => "Belajar adalah perjalanan, bukan tujuan.", "author" => "Anonim"],
            ["quote" => "Kesuksesan adalah hasil dari kesempurnaan, kerja keras, belajar dari kegagalan, loyalitas, dan ketekunan.", "author" => "Colin Powell"],
            ["quote" => "Belajar adalah investasi terbaik yang bisa Anda lakukan untuk diri sendiri.", "author" => "Anonim"],
            ["quote" => "Jangan pernah berhenti belajar, karena hidup tidak pernah berhenti mengajarkan.", "author" => "Anonim"],
            ["quote" => "Belajar adalah kunci untuk membuka pintu kesuksesan.", "author" => "Anonim"],
            ["quote" => "Pendidikan adalah senjata paling ampuh untuk mengubah dunia.", "author" => "Nelson Mandela"],
            ["quote" => "Belajar tidak pernah melelahkan pikiran.", "author" => "Leonardo da Vinci"],
            ["quote" => "Orang yang berhenti belajar akan menjadi pemilik masa lalu. Orang yang terus belajar akan menjadi pemilik masa depan.", "author" => "Mario Teguh"],
            ["quote" => "Belajar adalah awal dari kekayaan. Belajar adalah awal dari kesehatan. Belajar adalah awal dari spiritualitas. Mencari dan belajarlah terlebih dahulu, semua hal lainnya akan datang.", "author" => "Jim Rohn"],
            ["quote" => "Jangan pernah menyerah ketika kamu masih mampu berusaha lagi. Tidak ada kata berakhir sampai kamu berhenti mencoba.", "author" => "Brian Dyson"],
            ["quote" => "Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas.", "author" => "Henry Ford"],
            ["quote" => "Pendidikan bukanlah persiapan untuk hidup, pendidikan adalah hidup itu sendiri.", "author" => "John Dewey"],
            ["quote" => "Belajar adalah harta karun yang akan mengikuti pemiliknya ke mana pun.", "author" => "Pepatah Cina"],
            ["quote" => "Jangan takut untuk mengambil langkah besar ketika diperlukan. Anda tidak bisa melompati jurang dengan dua lompatan kecil.", "author" => "David Lloyd George"],
            ["quote" => "Orang bijak belajar ketika mereka bisa. Orang bodoh belajar ketika mereka terpaksa.", "author" => "Arthur Wellesley"],
            ["quote" => "Belajar adalah proses seumur hidup. Jangan berhenti sampai Anda mencapai tujuan Anda.", "author" => "Anonim"],
            ["quote" => "Semakin banyak Anda membaca, semakin banyak hal yang Anda ketahui. Semakin banyak Anda belajar, semakin banyak tempat yang Anda tuju.", "author" => "Dr. Seuss"],
            ["quote" => "Jangan biarkan apa yang tidak bisa Anda lakukan mengganggu apa yang bisa Anda lakukan.", "author" => "John Wooden"],
            ["quote" => "Belajar adalah perjalanan, bukan tujuan.", "author" => "Anonim"],
            ["quote" => "Kesuksesan adalah hasil dari kesempurnaan, kerja keras, belajar dari kegagalan, loyalitas, dan ketekunan.", "author" => "Colin Powell"],
            ["quote" => "Belajar adalah investasi terbaik yang bisa Anda lakukan untuk diri sendiri.", "author" => "Anonim"],
            ["quote" => "Jangan pernah berhenti belajar, karena hidup tidak pernah berhenti mengajarkan.", "author" => "Anonim"],
            ["quote" => "Belajar adalah kunci untuk membuka pintu kesuksesan.", "author" => "Anonim"],
            ["quote" => "Pendidikan adalah senjata paling ampuh untuk mengubah dunia.", "author" => "Nelson Mandela"],
            ["quote" => "Belajar tidak pernah melelahkan pikiran.", "author" => "Leonardo da Vinci"],
            ["quote" => "Orang yang berhenti belajar akan menjadi pemilik masa lalu. Orang yang terus belajar akan menjadi pemilik masa depan.", "author" => "Mario Teguh"],
            ["quote" => "Belajar adalah awal dari kekayaan. Belajar adalah awal dari kesehatan. Belajar adalah awal dari spiritualitas. Mencari dan belajarlah terlebih dahulu, semua hal lainnya akan datang.", "author" => "Jim Rohn"],
            ["quote" => "Jangan pernah menyerah ketika kamu masih mampu berusaha lagi. Tidak ada kata berakhir sampai kamu berhenti mencoba.", "author" => "Brian Dyson"],
            ["quote" => "Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas.", "author" => "Henry Ford"],
            ["quote" => "Pendidikan bukanlah persiapan untuk hidup, pendidikan adalah hidup itu sendiri.", "author" => "John Dewey"],
            ["quote" => "Belajar adalah harta karun yang akan mengikuti pemiliknya ke mana pun.", "author" => "Pepatah Cina"],
            ["quote" => "Jangan takut untuk mengambil langkah besar ketika diperlukan. Anda tidak bisa melompati jurang dengan dua lompatan kecil.", "author" => "David Lloyd George"],
            ["quote" => "Orang bijak belajar ketika mereka bisa. Orang bodoh belajar ketika mereka terpaksa.", "author" => "Arthur Wellesley"],
            ["quote" => "Belajar adalah proses seumur hidup. Jangan berhenti sampai Anda mencapai tujuan Anda.", "author" => "Anonim"],
            ["quote" => "Semakin banyak Anda membaca, semakin banyak hal yang Anda ketahui. Semakin banyak Anda belajar, semakin banyak tempat yang Anda tuju.", "author" => "Dr. Seuss"],
        ];

        // Masukkan data ke dalam tabel quotes
        foreach ($quotes as $quote) {
            Quote::create($quote);
        }
    }
}
