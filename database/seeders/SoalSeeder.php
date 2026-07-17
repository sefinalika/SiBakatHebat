<?php

namespace Database\Seeders;

use App\Models\Soal;
use Illuminate\Database\Seeder;

class SoalSeeder extends Seeder
{
    /**
     * Seed 76 soal observasi (teks final dari form PKBM ADZKA).
     *
     * Bagian dihitung dari nomor soal:
     *   1-9   => aqidah
     *   10-18 => ibadah
     *   19-27 => karakter_belajar
     *   28-76 => karakter_bakat (28-36 bakat umum, 37-76 = 40 karakter TB-40)
     */
    public function run(): void
    {
        $teks = [
            1 => 'Ananda tidak perlu disuruh ketika melakukan ibadah sholat.',
            2 => 'Meskipun tanpa tekanan dan juga bukan karena takut kepada orang tua, guru, atau orang lain, ananda tetap berkata jujur.',
            3 => 'Ananda tidak harus diingatkan untuk menerapkan tuntunan adab pada aktifitas hariannya.',
            4 => 'Ananda sering membicarakan tentang surga atau neraka.',
            5 => 'Ananda sering mengingatkan atau berkomentar kepada saudara, teman, atau orang lain yang melakukan pelanggaran syariat.',
            6 => 'Ananda tidak marah (berbesar hati) jika dibangunkan untuk melaksanakan sholat subuh.',
            7 => 'Ananda tidak sembunyi-sembunyi agar tidak diketahui orang tua atau gurunya, ketika ananda akan bermain HP atau permainan lainnya.',
            8 => 'Ananda sering mengucapkan dzikir tertentu ketika mengalami sesuatu kejadian yang menyenangkan maupun yang tidak menyenangkan.',
            9 => 'Ananda mengembalikan kepada yang memiliki atau menyerahkan kepada orangtua/guru, ketika ananda menemukan uang atau barang yang bukan miliknya.',

            10 => "Ananda sering membaca atau mendengarkan Al qur'an.",
            11 => "Ananda sering melakukan muroja'ah hafalan Al Qur'an.",
            12 => 'Tata caranya sudah benar, ketika ananda melaksanakan wudhu.',
            13 => 'Ananda sering melaksanakan aktifitas ibadah sholat wajib.',
            14 => 'Tata caranya sudah benar, ketika ananda melaksanakan sholat.',
            15 => 'Ananda sering menerapkan tata cara adab Islami dalam aktifitas hariannya.',
            16 => 'Ananda sering mengajak saudara atau teman untuk melaksanakan ibadah.',
            17 => 'Ananda ikut aktif dalam kegiatan ibadah bulan Ramadhan.',
            18 => 'Ananda dapat mempraktekkan tata cara tayamum.',

            19 => 'Ananda tidak perlu disuruh untuk belajar.',
            20 => 'Ananda berusaha menemukan solusi ketika menghadapi kesulitan (dalam bermain atau dalam kondisi lainnya).',
            21 => 'Ananda tidak canggung atau tidak malu untuk bertanya kepada orang yang tidak dikenal, ketika ananda ingin menanyakan arah jalan atau sesuatu yang belum diketahuinya.',
            22 => 'Ananda sering memanfaatkan benda-benda sekitar untuk media/obyek belajarnya.',
            23 => 'Ananda sering menjelajah lingkungan sekitar atau tempat-tempat tertentu untuk menuntaskan rasa ingin tahunya.',
            24 => 'Ananda banyak bertanya kepada orang tua, guru, atau orang lain tentang sesuatu hal yang tidak diketahuinya.',
            25 => 'Ananda sering berusaha keras untuk mencoba melakukan sesuatu yang belum pernah dilakukannya.',
            26 => 'Tanpa ada rasa takut salah atau takut disalahkan, ketika ananda mencoba melakukan sesuatu.',
            27 => 'Ananda sering lupa waktu ketika sedang mempelajari sesuatu yang belum diketahuinya.',

            28 => 'Ananda memiliki sifat tertentu yang menjadikan dirinya berbeda dengan lainnya.',
            29 => 'Ananda memiliki permainan atau aktifitas khusus yang sangat disukainya dan favorit baginya.',
            30 => 'Ananda mampu melakukan/membuat dengan baik sehingga hasilnya bagus, terhadap permainan/aktifitas favoritnya.',
            31 => 'Ananda berulang-ulang melakukan, terhadap permainan/aktifitas favoritnya.',
            32 => 'Tanpa persiapan matang (spontanitas), ananda dapat melakukan permainan/aktifitas favoritnya dengan baik.',
            33 => 'Ananda dapat memanfaatkan barang mainan buatan sendiri.',
            34 => 'Meskipun menghadapi kesulitan, ananda tidak putus asa untuk tetap berusaha menyelesaikan mainan/aktifitas favoritnya.',
            35 => 'Meskipun sering gagal, ananda tetap mencoba menyelesaikan mainan/aktivitas favoritnya.',
            36 => 'Untuk mendapatkan mainan atau untuk dapat melakukan aktifitas favoritnya, ananda rela menabung uang jajannya.',

            37 => 'Ananda bersikap adil, tidak berat sebelah, tidak memihak, bersikap tengah-tengah, dan fair dalam "permainan" terhadap orang lain.',
            38 => 'Ananda berusaha melaksanakan tugas, tanggung jawab, atau amanah yang diberikan kepadanya dengan sebaik-baiknya.',
            39 => 'Ananda tidak tergesa-gesa ketika akan bertindak dan terlebih dahulu mempertimbangkan berbagai hal agar tidak terjadi kesalahan.',
            40 => 'Ananda memiliki tekad yang kuat untuk segera memulai pekerjaan atau aktifitas dan kadang seperti orang yang tidak sabaran.',
            41 => 'Ananda suka beramah-tamah dan tersenyum kepada orang lain, berkata lembut, dan suka memberikan penyambutan sebaik-baiknya.',
            42 => 'Ananda termasuk orang yang cepat dan mudah dalam memahami sesuatu yang dihadapkan kepadanya, seperti menghitung, menghafal, menganalisa dll.',
            43 => 'Ananda mampu menyampaikan atau menjelaskan sesuatu menjadi menarik dan mudah dipahami, dan berbicaranya fasih.',
            44 => 'Ananda memiliki firasat kuat sehingga mampu menduga sesuatu yang akan terjadi dengan memperhatikan tanda-tanda yang tampak.',
            45 => 'Ananda tidak senang atau sering berkomentar jika ada yang melanggar aturan, melanggar haknya, atau kesepakatan yang telah ditetapkan.',
            46 => 'Ananda merasa memiliki kekurangan dan merasa apa yang telah diterimanya melebihi haknya sehingga menjadikan Ananda merasa malu.',
            47 => 'Ananda suka memikirkan sesuatu secara mendalam sehingga Ananda mampu menemukan hikmah dari sesuatu (peristiwa) yang telah terjadi.',
            48 => 'Ananda mampu menahan diri untuk tidak membalas orang yang bersalah kepadanya meskipun Ananda mampu melakukannya dan Ananda tetap santun.',
            49 => 'Ananda memiliki rasa ingin tahu yang tinggi terhadap sesuatu sehingga Ananda memiliki gairah belajar dan keinginan untuk meraih cita-citanya yang tinggi.',
            50 => 'Ananda selalu berprasangka baik terhadap orang lain meskipun faktanya mendukung kepada dugaan yang buruk.',
            51 => 'Ananda mampu menjaga diri sehingga Ananda sangat menyesal jika terlanjur melakukan suatu kesalahan, pelanggaran, atau larangan.',
            52 => 'Ananda memiliki sifat perfeksionis sehingga Ananda selalu ingin segala sesuatu harus sempurna dan lebih sempurna lagi, dan Ananda tidak suka seadanya.',
            53 => 'Ananda suka mendahulukan orang lain meskipun sebenarnya Ananda lebih membutuhkannya sehingga Ananda menjadi orang yang suka melayani.',
            54 => 'Ananda memiliki prinsip yang dijadikan sebagai harga dirinya dan Ananda mampu mempertahankannya meskipun dalam tekanan yang sangat berat.',
            55 => 'Ananda suka memberikan sesuatu kepada siapapun meskipun tanpa diminta dan Ananda tidak mengharap balasan atau ucapan terima kasih.',
            56 => 'Ananda mampu menahan diri untuk tidak menyebarkan kabar (yang bukan aib) karena Ananda anggap sebagai privasi bagi orang yang bersangkutan.',
            57 => 'Ananda suka memperdalam hubungan yang sudah terjalin, dengan memberikan kasih sayang secara khusus kepada yang bersangkutan.',
            58 => 'Ananda suka bersaing, berlomba atau berkompetisi agar dirinya menjadi yang paling unggul dari yang lainnya.',
            59 => 'Ananda suka bercanda dan mengungkapkan kata-kata jenaka sehingga suasana menjadi lebih ceria dan lebih akrab.',
            60 => 'Ananda suka memberikan motivasi atau dorongan, saran-saran atau nasehat agar orang lain menjadi lebih baik.',
            61 => 'Ananda selalu ingin menyelesaikan suatu pekerjaan yang sedang dikerjakannya dengan penuh semangat dan kerja keras.',
            62 => 'Ketika Ananda dalam situasi kesulitan, Ananda memiliki banyak akal untuk menemukan solusinya.',
            63 => 'Ananda suka membantu orang lain yang mengalami kesusahan sampai orang tersebut terlepas dari kesusahan tersebut.',
            64 => 'Ananda merasa cukup dengan apa yang telah dimilikinya dan rela menerima apapun yang diberikan kepadanya.',
            65 => 'Ananda mudah merasa kasihan dan bersimpati kepada orang yang mengalami kesusahan dan berkeinginan untuk memberikan yang terbaik kepadanya.',
            66 => 'Ananda selalu mengambil sisi termudah dalam menyikapi semua urusan, sehingga Ananda selalu berlemah-lembut dalam berbuat maupun berkata.',
            67 => 'Ananda mampu menutupi dan tidak menampakkan atau menceritakan aib diri sendiri atau orang lain.',
            68 => 'Ananda mampu menahan diri untuk tidak bereaksi dan sabar terhadap tekanan, kesulitan atau apapun yang menimpa dirinya.',
            69 => 'Ananda cenderung banyak diam dan berbicara seperlunya, sehingga Ananda akan berbicara untuk ucapan-ucapan yang diperlukan saja.',
            70 => 'Ananda mudah menerima saran dan masukan serta berkata dan berbuat sesuai dengan apa yang sebenarnya.',
            71 => 'Ananda berani menghadapi orang secara langsung untuk berbicara kepadanya, memerintahnya, mengaturnya, atau menguasainya.',
            72 => 'Ananda suka menjalin kerjasama dengan orang lain, berdamai, dan menghindari konflik, untuk mencapai tujuan bersama.',
            73 => 'Ananda tidak suka menampakkan atau ditampakkan kehebatannya meskipun Ananda memilikinya, sehingga ada orang lain yang menyebutnya rendah hati.',
            74 => 'Ananda suka berkumpul dengan orang banyak dan berusaha menambah anggota perkumpulan sebanyak-banyaknya.',
            75 => 'Ananda memegang teguh janji yang telah disepakati bersama, meskipun posisinya sebagai pihak yang dicurangi.',
            76 => 'Ananda tampak tenang tapi tangguh, tidak banyak bicara tapi bisa memahamkan, tidak tergesa tapi mendahului dalam semua urusan.',
        ];

        foreach ($teks as $nomor => $isi) {
            Soal::updateOrCreate(
                ['nomor_soal' => $nomor],
                [
                    'teks' => $isi,
                    'bagian' => $this->bagian($nomor),
                ]
            );
        }
    }

    private function bagian(int $nomor): string
    {
        return match (true) {
            $nomor <= 9 => 'aqidah',
            $nomor <= 18 => 'ibadah',
            $nomor <= 27 => 'karakter_belajar',
            default => 'karakter_bakat',
        };
    }
}
