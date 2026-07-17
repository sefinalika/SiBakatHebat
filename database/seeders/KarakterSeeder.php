<?php

namespace Database\Seeders;

use App\Models\Karakter;
use Illuminate\Database\Seeder;

/**
 * Seed 40 karakter TB-40 (urut abjad), dipetakan ke soal 37-76.
 *
 * Data dasar (kode, nama_karakter, nama_arab, terjemahan, tipe) bersifat FINAL.
 * Kolom `dimensi` (dan `kelompok` turunannya) MENGIKUTI "3 STRUKTUR JIWA" di
 * docs/prd/prd_dimensi.md (KARSA=bakat, CIPTA=akal, RASA=hati) — lihat $dimensiStrukturJiwa.
 *
 * ============================================================
 *  KOLOM KONTEN DI BAWAH INI bersumber dari data resmi
 *  docs/prd/prd_dimensi.md (tabel induk 40 karakter) —
 *  BUKAN draf lagi.
 *  Yang dimaksud kolom konten:
 *    - label_diri
 *    - definisi
 *    - profesi
 *    - jurusan
 *    - sifat_tercela_melalaikan
 *    - cara_memperbaiki_melalaikan
 *    - sifat_tercela_berlebihan
 *    - cara_memperbaiki_berlebihan
 *  Kolom `dimensi` tetap MENGIKUTI "3 STRUKTUR JIWA"
 *  (lihat catatan di atas dan $dimensiStrukturJiwa).
 * ============================================================
 *
 * Aturan turunan:
 *  - id N (1..40): nomor_soal = N + 36, urut_abjad = N, urut_grafik = N.
 *  - kelompok diturunkan dari dimensi: bakat=>karsa, akal=>cipta, hati=>rasa.
 */
class KarakterSeeder extends Seeder
{
    public function run(): void
    {
        // Peta dimensi => kelompok (eksak, jangan diubah).
        $kelompokMap = [
            'bakat' => 'karsa',
            'akal'  => 'cipta',
            'hati'  => 'rasa',
        ];

        // Dimensi karakter sesuai "3 STRUKTUR JIWA" pada docs/prd/prd_dimensi.md:
        //   KARSA => bakat, CIPTA => akal, RASA => hati.
        // Map ini OTORITATIF dan menimpa nilai dimensi di tabel $karakter.
        $dimensiStrukturJiwa = [
            // KARSA (bakat) — 13
            'aziimah' => 'bakat', 'fashaahah' => 'bakat', 'ghairah' => 'bakat',
            'himmah' => 'bakat', 'ihsaan' => 'bakat', 'izzah' => 'bakat',
            'juud' => 'bakat', 'munaafasah' => 'bakat', 'nashiihah' => 'bakat',
            'nasyaath' => 'bakat', 'nushrah' => 'bakat', 'syajaa_ah' => 'bakat',
            'waqaar' => 'bakat',
            // CIPTA (akal) — 13
            'adaalah' => 'akal', 'basyaasyah' => 'akal', 'dzakaa' => 'akal',
            'firaasah' => 'akal', 'hikmah' => 'akal', 'husnuzhan' => 'akal',
            'mahabbah' => 'akal', 'muzaah' => 'akal', 'nubl' => 'akal',
            'rifq' => 'akal', 'ta_aawun' => 'akal', 'ulfah' => 'akal',
            'wafaa' => 'akal',
            // RASA (hati) — 14
            'amaanah' => 'hati', 'anaah' => 'hati', 'hayaa' => 'hati',
            'hilm' => 'hati', 'iffah' => 'hati', 'itsaar' => 'hati',
            'kitmaanus_sirr' => 'hati', 'qanaa_ah' => 'hati', 'rahmah' => 'hati',
            'satr' => 'hati', 'shabr' => 'hati', 'shamt' => 'hati',
            'shidq' => 'hati', 'tawaadhu' => 'hati',
        ];

        // Setiap baris (tanpa id; id = index + 1):
        // [kode, nama_karakter, nama_arab, terjemahan, dimensi, tipe,
        //  label_diri, definisi, profesi, jurusan,
        //  sifat_tercela_melalaikan, cara_memperbaiki_melalaikan,
        //  sifat_tercela_berlebihan, cara_memperbaiki_berlebihan]
        $karakter = [
            [
                'adaalah', "'Adaalah", 'العَدَالَة', 'adil', 'bakat', 'extrovert',
                "bersikap adil",
                "bersikap tengah-tengah, tepat dalam menempatkan sesuatu pada tempatnya tanpa berlebihan dan tidak kurang",
                "Hakim, Petugas pembinaan keluarga, Konselor masalah keluarga, Mediator, Petugas kontrol terhadap kesesuaian atas standar seperti kepatuhan kepada aturan, Quantity Surveyor, Petugas Commisioning atau peran yang bisa memiliki kekuatan untuk menyamakan aturan main dan peran lainnya yang terkait dengan sifat adil",
                "Hukum Islam, Syariah, Konseling keluarga, Hukum Publik, Hukum Perdata, Hukum Internasioanal, Ilmu Hukum, Hukum Pidana, atau jurusan lainnya yang terkait dengan keadilan",
                "Zhulm (الظُّلْم)(zhalim) — berbuat tidak adil atau berat sebelah dalam bersikap dan bertindak sehingga merugikan orang atau pihak lain",
                "Sifat tercela zhulm (الظُّلْم)(zhalim) yang ditimbulkan akibat meremehkan bakat ‘adaalah, dapat diperbaiki dengan menguatkan bakat ‘adaalah itu sendiri dan bakat lainnya, yaitu antara lain bakat itsaar, rahmah, shabr, hilm, anaah, dan amaanah",
                "Jahl (الجَهْل) (kebodohan) — ketiadaan pengetahuan sehingga menganggap yang dilakukan adalah adil, tetapi sebenarnya yang dilakukannya bukanlah keadilan",
                "sifat tercela jahl (الجَهْل) (kebodohan) yang ditimbulkan akibat berlebihan dalam bakat ‘adaalah, dapat diperbaiki dengan menguatkan bakat syajaa’ah, munaafasah, dan ghairah.",
            ],
            [
                'amaanah', 'Amaanah', 'الاَمَانَة', 'tanggung jawab', 'hati', 'introvert',
                "bertanggung jawab terhadap tugas yang diberikan kepadanya",
                "senang jika diberi tanggung jawab serta menunaikan akad atau janji yang disepakati dengan sebaik-baiknya",
                "Pengelola Lembaga Pendidikan, Pengelola Baitul Maal, Pengasuh Anak Yatim, Pedagang, Pengelola Dana Umat, Bendahara, Account Sales, Manajer Umum, Manajer Keuangan, Quality Controller, Keamanan, dan peran lainnya yang terkait dengan sifat amanah atau tanggung jawab",
                "Keguruan/tarbiyah, akuntansi, bisnis dan manajemen, administrasi",
                "Khiaanah (الخِيَانَة) (penghianatan) — perkataan dan perlakuan yang tidak sesuai dengan apa yang telah diamanahkan",
                "Sifat tercela khiaanah (الخِيَانَة) (penghianatan) yang ditimbulkan akibat meremehkan bakat amaanah, dapat diperbaiki dengan menguatkan bakat itu sendiri dan bakat lainnya, yaitu antara lain bakat iffah dan shidq.",
                "Taqliid (التَّقْلِيْد) (fanatik buta) — menerima tugas dan menuruti perkataan dan perbuatan orang lain tanpa mempedulikan kebenarannya",
                "sifat tercela taqliid (التَّقْلِيْد) (fanatik buta) yang ditimbulkan akibat berlebihan dalam bakat amaanah, dapat diperbaiki dengan menguatkan bakat izzah dan waqaar.",
            ],
            [
                'anaah', 'Anaah', 'الاَنَاة', 'tidak tergesa', 'akal', 'introvert',
                "mempertimbangkan dulu sebelum bertindak",
                "tenang, dan mencari kejelasan terlebih dahulu sebelum melakukan tindakan",
                "Mufti, Penasehat, Konsultan, Perumus peraturan, Perancang Sistem Keamanan, Pengawas, Pilot, Urusan Legal, Membuat Kontrak Bisnis yang baik atau memastikan kesesuaian dengan peraturan atau standar atau kode atau juga peran yang terkait dengan masalah keuangan dan atau keamanan",
                "Teknik Penerbangan, Teknik Mesin, Teknik Konstruksi Bangunan, Keselamatan Lalu Lintas, Teknik Listrik, Akuntansi, Manajemen Keuangan, dan jurusan lain yang terkait dengan kehati-hatian, keamanan, dan ketelitian",
                "‘ajalah (العَجَلَة) (tergesa-gesa) — keinginan untuk mendapatkan sesuatu sebelum tiba waktunya yang disebabkan oleh besarnya keinginannya terhadap sesuatu tersebut, seperti halnya orang yang memanen buah sebelum datang waktu panennya",
                "Sifat tercela ‘ajalah (العَجَلَة) (tergesa-gesa) yang ditimbulkan akibat meremehkan bakat anaah, dapat diperbaiki dengan menguatkan bakat itu sendiri dan bakat lainnya, yaitu antara lain bakat hilm.",
                "Was-was (وَسْوَاس)(ragu-ragu) — salah satu senjata iblis yang disematkan di hati hamba Allah untuk menimbulkan keraguan, baik dalam beribadah, muamalah, maupun lainnya",
                "sifat tercela was-was (وَسْوَاس)(ragu-ragu) yang ditimbulkan akibat berlebihan dalam bakat anaah, dapat diperbaiki dengan menguatkan bakat husnuzhan dan firaasah.",
            ],
            [
                'aziimah', "'Aziimah", 'العَزِيمَة', 'tekad', 'bakat', 'extrovert',
                "memiliki tekad untuk segera memulai sesuatu pekerjaan",
                "memiliki tekad kuat untuk segera memulai suatu tindakan atau amalan",
                "Perintis atau pelopor, Team penggerak, Entrepreneur, Sales, Pengelola usaha-usaha baru, atau usaha yang memerlukan perubahan besar",
                "Manajemen dan Bisnis, Teknologi Terapan, Marketing, Kewirausahaan, Pekerjaan Umum",
                "Kasal (الكَسَل) (malas) — merasa berat dan lamban terhadap sesuatu, padahal memiliki kekuatan, karena tidak adanya keinginan untuk berbuat kebaikan",
                "Sifat tercela kasal (الكَسَل) (malas) yang ditimbulkan akibat meremehkan bakat ‘aziimah, dapat diperbaiki dengan menguatkan bakat itu sendiri dan bakat lainnya, yaitu antara lain bakat himmah dan nasyaath.",
                "Thama’ (الطَّمَع) (Serakah) — keinginan kuat terhadap sesuatu yang sebenarnya tidak dibutuhkannya",
                "sifat tercela thama’ (الطَّمَع) (Serakah) yang ditimbulkan akibat berlebihan dalam bakat ‘aziimah, dapat diperbaiki dengan menguatkan bakat qanaa’ah, tawaadhu’, dan hayaa’.",
            ],
            [
                'basyaasyah', 'Basyaasyah', 'البَشَاشَة', 'berseri-seri', 'hati', 'extrovert',
                "berseri-seri tampak wajahnya",
                "berseri-seri wajahnya, murah senyum, berkata lembut, dan memberikan penyambutan yang baik karena rasa gembira ketika bertemu orang lain",
                "Guru, Konselor, Presenter, Trainer, Motivator, Pemandu Kegiatan Anak, Pelayan Publik, Tour Leader (Pemandu Wisata)",
                "Ilmu Keguruan, Ilmu Komunikasi, PGTK (Pendidikan Guru TK), Keperawatan, Psikologi, Kepariwisataan, Pelayanan Publik",
                "Abush (العَبُوْس) (muka masam) — berwajah masam dalam bergaul sehingga membuat orang-orang tidak suka, takut, bahkan lari darinya",
                "Sifat tercela abush (العَبُوْس) (muka masam) yang ditimbulkan akibat meremehkan bakat basyaasyah, dapat diperbaiki dengan menguatkan bakat itu sendiri dan bakat lainnya, yaitu antara lain bakat rifq, muzaah, dan ulfah.",
                "Dzull (الذُّلّ)(Lemah) — kelemahan untuk mempertahankan sesuatu sehingga cenderung untuk direndahkan orang lain karena kurangnya ketegasan dalam bersikap",
                "sifat tercela dzull (الذُّلّ)(Lemah) yang ditimbulkan akibat berlebihan dalam bakat basyaasyah, dapat diperbaiki dengan menguatkan bakat syajaa’ah, munaafasah, dan ghairah.",
            ],
            [
                'dzakaa', "Dzakaa'", 'الذَّكَاء', 'cerdas', 'akal', 'introvert',
                "cerdas dalam menganalisa sesuatu",
                "memiliki kecepatan dan ketajaman berpikir dalam memahami, menghafal, menganalisa, dan menyimpulkan sesuatu yang dihadapkan kepadanya",
                "Ahli ilmu (Agama atau umum: Fisika, kimia, dll), Analis, Periset (teknologi, pemasaran, keuangan, atau kesehatan), Manajemen Database, Editor, Manajemen Risiko, Accounting, Programmer",
                "Jurusan Ilmu Hadits, Ilmu Al Qur’an, Fiqih, atau ilmu Agama lainnya, Teknik kimia, Fisika, Matematika, Teknik Komputer, IT Programmer, Akuntansi",
                "Jahl (الجَهْل) (bodoh) — meyakini sesuatu yang bertentangan dengan hakikat yang sebenarnya",
                "Sifat tercela jahl (الجَهْل) (bodoh) yang ditimbulkan akibat meremehkan bakat dzakaa’, dapat diperbaiki dengan menguatkan bakat itu sendiri dan bakat lainnya, yaitu antara lain bakat hikmah,",
                "Ahlur ra’yi (اَهْلُ الرَّأْي) (pemuja akal) — mendewakan logika dan akal dalam menghukumi sesuatu sehingga tidak menerima kebenaran yang tidak dapat dilogika",
                "sifat tercela ahlur ra’yi (اَهْلُ الرَّأْي) (pemuja akal) yang ditimbulkan akibat berlebihan dalam bakat dzakaa’, dapat diperbaiki dengan menguatkan bakat tawaadhu’, qanaa’ah, dan hayaa’.",
            ],
            [
                'fashaahah', 'Fashaahah', 'الفَصَاحَة', 'fasih bicara', 'akal', 'extrovert',
                "fasih berbicara dalam menjelaskan sesuatu",
                "memiliki kemampuan untuk menyampaikan sesuatu dengan ungkapan sederhana, meskipun sebenarnya rumit, sehingga mudah untuk dipahami",
                "Guru Tajwid Qira’ah, Qari’, Da’i, Guru (pengajar), Dosen, Motivator, Penyuluh Masyarakat, Public Relation, Duta, Sales, Marketing, Humas, Juru Bicara, Presenter, MC, Pengacara, Layanan Pelanggan, Penulis",
                "Pendidikan Agama, Konseling Keluarga, Keguruan, Ilmu Komunikasi, Hubungan Internasional, Marketing, Manajemen, Manajemen Bisnis",
                "‘Ujmah (العُجْمَة) (gagap) — kesalahan berbicara yang berupa pengulangan bunyi, suku kata, atau kata-kata, sehingga menjadikan tidak fasih berbicara",
                "Sifat tercela ‘ujmah (العُجْمَة) (gagap) yang ditimbulkan akibat meremehkan bakat fashaahah, dapat diperbaiki dengan menguatkan bakat fashaahah itu sendiri dan bakat lainnya, yaitu antara lain bakat syajaa’ah.",
                "Jidaal (الجِدَال) (berdebat) — berbantah-bantahan untuk saling menjatuhkan satu dengan lainnya dengan menyalahgunakan kefasihan berbahasa yang dimiliki",
                "sifat tercela jidaal (الجِدَال) (berdebat) yang ditimbulkan akibat berlebihan dalam bakat fashaahah, dapat diperbaiki dengan menguatkan bakat itsaar, hilm, rahmah, dan shabr.",
            ],
            [
                'firaasah', 'Firaasah', 'الفِرَاسَة', 'firasat', 'akal', 'introvert',
                "memiliki firasat terhadap sesuatu yang akan terjadi",
                "memiliki ketajaman ilmu yang mampu menyimpulkan perkara batiniyah dengan memperhatikan tanda-tanda lahiriyah yang dapat dilihat, didengar, atau dirasakan",
                "Penasehat, Konsultan, Perumus Visi Misi, Entrepreneur, Event Organizer (EO), Tour Leader, Manajer",
                "Ilmu Aqidah, Ilmu Ushuluddin, Ilmu Jiwa, Manajemen",
                "Safah (السَّفَه) (bodoh) — keyakinan terhadap sesuatu yang bertentangan dengan hakikatnya, sehingga dapat menjadikan seseorang tidak mampu memahami kebenaran yang sangat jelas tanda-tandanya",
                "Sifat tercela ‘ujmah safah (السَّفَه) (bodoh) yang ditimbulkan akibat meremehkan bakat firaasah, dapat diperbaiki dengan menguatkan bakat itu sendiri dan bakat lainnya, yaitu antara lain bakat hikmah dan dzakaa’.",
                "Kadzib (الكَذِب) (dusta) — mengabarkan sesuatu tidak sesuai dengan yang sebenarnya, baik sengaja atau tidak, baik masa lalu atau masa mendatang",
                "sifat tercela kadzib (الكَذِب) (dusta) yang ditimbulkan akibat berlebihan dalam bakat firaasah, dapat diperbaiki dengan menguatkan bakat shidq, shamt, dan iffah.",
            ],
            [
                'ghairah', 'Ghairah', 'الغَيْرَة', 'cemburu', 'hati', 'extrovert',
                "memiliki rasa cemburu",
                "memiliki rasa tidak senang jika syariat, aturan, hak, atau ketentuan lain yang telah ditetapkan dilanggar oleh orang lain",
                "Penegak Peraturan, Penegak Disiplin, Pengawas, Mandor, Evaluator, Auditor, Supervisor, Manajer.",
                "Administrasi, manajemen, manajemen bisnis, Sekolah Tinggi Pemerintahan Dalam Negeri, Akademi Militer, Akademi Kepolisian",
                "Diyaatsah (الدِّيَاثَة) (permisif) — tidak ada kepedulian pada diri seorang laki-laki (dan juga perempuan) terhadap kemungkaran yang terjadi dalam keluarganya, lembaga, lingkungan masyarakat, atau lainnya",
                "Sifat tercela diyaatsah (الدِّيَاثَة) (permisif) yang ditimbulkan akibat meremehkan bakat ghairah, dapat diperbaiki dengan menguatkan bakat ghairah itu sendiri dan bakat lainnya, yaitu antara lain bakat munaafasah dan syajaa’ah.",
                "Hasad (الحَسَد) (iri hati) — mengharap hilangnya nikmat pada orang yang diiri dan berpindah kepada orang yang iri",
                "sifat tercela hasad (الحَسَد) (iri hati) yang ditimbulkan akibat berlebihan dalam bakat ghairah, dapat diperbaiki dengan menguatkan bakat tawaadhu’, qanaa’ah, dan hayaa’.",
            ],
            [
                'hayaa', "Hayaa'", 'الحَيَاء', 'malu', 'bakat', 'introvert',
                "memiliki perasaan malu",
                "merasa betapa besar pemberian/hak yang diterimanya, namun merasa dirinya masih memiliki banyak keterbatasan dan kekurangan, sehingga merasa tidak pantas untuk mendapatkannya",
                "Ahli Hikmah, Sastrawan, Penulis, Designer Fashion, Designer Interior, Arsitek, Kaligrafer, dsb.",
                "Ilmu Jiwa, Sastra, Arsitektur, Tata Busana, Seni, Desain Komunikasi Visual, Animasi, Fotografi, dsb.",
                "Waqaahah/الوَقَاحَة (tidak tahu malu) — kadang disebut juga ber “muka tebal”, yaitu tidak malu ketika melanggar syariat Allah",
                "Sifat tercela waqaahah/الوَقَاحَة (tidak tahu malu) yang ditimbulkan akibat meremehkan bakat hayaa’, dapat diperbaiki dengan menguatkan bakat hayaa’ itu sendiri dan bakat lainnya, yaitu antara lain bakat ‘iffah dan shidq.",
                "Duuniyyah/الدُوْنِيَّة (rendah diri) atau minder — tekanan jiwa yang muncul karena adanya konflik dalam diri antara keinginan untuk menjadi unggul dan rasa takut karena merasa dirinya tidak memiliki kemampuan. Dengan kata lain dirinya merasa lebih rendah dari orang lain tetapi takut dianggap rendah",
                "sifat tercela duuniyyah/الدُوْنِيَّة (rendah diri) atau minder yang ditimbulkan akibat berlebihan dalam bakat hayaa’, dapat diperbaiki dengan menguatkan bakat himmah, aziimah, nasyaath, ihsaan, izzah, dan waqaar.",
            ],
            [
                'hikmah', 'Hikmah', 'الحِكْمَة', 'hikmah', 'akal', 'introvert',
                "berpikir sangat mendalam untuk mengambil hikmah darinya",
                "memiliki keilmuan yang mendalam tentang suatu pengetahuan hingga dapat menempatkan sesuatu pada kondisi yang sebenarnya",
                "Kosultan, Konselor, Mufti, Da’i/yah, Psikolog, Petugas Pembinaan Keluarga, Leader dalam membangun team yang berbeda kelompok, atau membantu orang agar merasa berguna",
                "Ilmu Aqidah, Ilmu Jiwa, Ilmu Adab, Sastra, Syariah, Psikologi, Manajemen",
                "Jahl/الجَل (kebodohan) — meyakini sesuatu yang tidak sesuai dengan keadaan sebenarnya, atau tidak mengetahui sesuatu yang sebenarnya",
                "Sifat tercela jahl/الجَل )kebodohan( yang ditimbulkan akibat meremehkan bakat hikmah, dapat diperbaiki dengan menguatkan bakat hikmah itu sendiri dan bakat lainnya, yaitu antara lain bakat dzakaa’, nubl, husnuzhan, dan firaasah.",
                "Kadzib (الكَذِب) (dusta) — mengabarkan tentang sesuatu peristiwa yang tidak sesuai dengan keadaan sebenarnya, hanya didasari prasangka saja",
                "sifat tercela kadzib (الكَذِب) (dusta) yang ditimbulkan akibat berlebihan dalam bakat hikmah, dapat diperbaiki dengan menguatkan bakat shidq dan ‘iffah.",
            ],
            [
                'hilm', 'Hilm', 'الحِلْم', 'santun', 'hati', 'introvert',
                "mampu untuk tidak membalas perbuatan orang lain",
                "memiliki kemampuan menahan diri untuk tidak memarahi, membalas, atau memberikan hukuman walaupun mampu melakukannya",
                "Pelayanan Masyarakat Umum, Rahabilitasi Kenakalan Remaja, Perawat Rumah Sakit, Guru (pengajar), Pelayanan Pelanggan (Customer Service), Sales, Manajer, Supervisor",
                "Keperawatan, Ilmu Komunikasi, Keguruan, Kepariwisataan, Marketing, Manajemen",
                "Ghadhab (الغَضَب) (marah) — mendidihnya darah dalam hati untuk menahan orang yang mengganggunya atau untuk membalas dendam setelah mengganggunya",
                "Sifat tercela ghadhab (الغَضَب) (marah) yang ditimbulkan akibat meremehkan bakat hilm, dapat diperbaiki dengan menguatkan bakat hilm itu sendiri dan bakat lainnya, yaitu antara lain bakat shabr dan anaah.",
                "Dzull (الذُّلّ) (lemah) — kelemahan untuk mempertahankan sesuatu yang seharusnya dipertahankan",
                "sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat berlebihan dalam bakat hilm, dapat diperbaiki dengan menguatkan bakat syajaa’ah, munaafasah, dan ghairah.",
            ],
            [
                'himmah', 'Himmah', 'الهِمَّة', 'cita-cita tinggi', 'hati', 'extrovert',
                "memiliki cita-cita yang tinggi",
                "memiliki rasa ingin tahu dan gairah yang sangat tinggi untuk mengetahui dan meraih cita-cita yang tertinggi",
                "Team Perumus Visi Misi, Team Litbang Pendidikan atau lembaga lainnya, Pemimpin, Entrepreneur, Perencana Jangka Panjang, Visioner, Pengembang produk baru, Pengembang Perusahaan",
                "Ekonomi Syariah, Manajemen, Marketing, Manajemen Lingkungan",
                "Futuur (الفُتُوْر) (lemah kemauan) — sikap malas, lesu, lamban dalam meraih sesuatu, padahal sebelumnya bersungguh-sungguh, semangat, dan bergairah",
                "Sifat tercela futuur (الفُتُوْر) (lemah kemauan) yang ditimbulkan akibat meremehkan bakat himmah, dapat diperbaiki dengan menguatkan bakat himmah itu sendiri dan bakat lainnya, yaitu antara lain bakat ‘aziimah dan nasyaath.",
                "Thuulul amal (طُوْلُ الأَمَلِ) (panjang angan-angan) — keinginan kuat terhadap sesuatu yang kemungkinan kecil untuk dapat diraih",
                "sifat tercela thuulul amal (طُوْلُ الأَمَلِ) (panjang angan-angan) yang ditimbulkan akibat berlebihan dalam bakat himmah, dapat diperbaiki dengan menguatkan bakat qanaa’ah, tawaadhu’, dan hayaa’.",
            ],
            [
                'husnuzhan', 'Husnuzhan', 'حُسْنُ الظَّن', 'prasangka baik', 'akal', 'introvert',
                "berpikir positif tentang segala sesuatu",
                "memiliki kecenderungan menduga yang baik dari pada yang buruk walaupun fakta yang dilihatnya mendukung kepada dugaan yang buruk",
                "Hakim, Pendidik, Guru, Pengajar, Juru Damai, Mediator, Relator, Pelayanan Publik",
                "Hukum Islam, Keguruan, Manajemen, Ilmu Komunikasi, Manajemen Bisnis",
                "Suu’uzhan (سُوْءُ الظَّن) (prasangka buruk) — memenuhi hati dengan prasangka buruk, sampai memenuhi juga lisannya dan anggota badannya",
                "Sifat tercela su’uzhan (سُوْءُ الظَّن) (prasangka buruk) yang ditimbulkan akibat meremehkan bakat husnuzhan, dapat diperbaiki dengan menguatkan bakat husnuzhan itu sendiri dan bakat lainnya, yaitu antara lain bakat hikmah, dan firaasah.",
                "Taqliid (التَّقْلِيْد) (fanatik buta) — mengikuti dan mempercayai perkataan dan perbuatan orang lain tanpa mempedulikan kebenarannya",
                "sifat tercela taqliid (التَّقْلِيْد) (fanatik buta) yang ditimbulkan akibat berlebihan dalam bakat husnuzhan, dapat diperbaiki dengan menguatkan bakat himmah, aziimah, ‘izzah, dan waqaar.",
            ],
            [
                'iffah', "'Iffah", 'العِفَّة', 'jaga diri', 'bakat', 'introvert',
                "mampu menahan diri untuk tidak menuruti hasrat",
                "mampu menahan diri terhadap dorongan hawa nafsu dalam dirinya sehingga tidak melakukan hal yang dilarang, baik dalam harta, ucapan, atau perbuatan",
                "Pengelola Keuangan Umat, Bendahara, Team Sarana Prasarana, Team Belanja, Pemimpin, Akuntan",
                "Syariah, Hukum Islam, Manajemen Keuangan, Akuntansi, Administrasi",
                "Fahsy (الفَحْش) (keji) — segala hal yang mengindikasikan pada wilayah keburukan, kemaksiatan, dan dosa yang keluar pada wilayah batas kewajaran, serta dipandang sangat hina oleh akal sehat manusia dan syariat Islam",
                "Sifat tercela fahsy (الفَحْش) (keji) yang ditimbulkan akibat meremehkan bakat ‘iffah, dapat diperbaiki dengan menguatkan bakat ‘iffah itu sendiri dan bakat lainnya, yaitu antara lain bakat hayaa’, tawaadhu’, qanaa’ah dan shidq.",
                "Dzull (الذُّلّ) (lemah) — ketundukan jiwa dikarenakan ketidakmampuan untuk mempertahankan sesuatu, sehingga merendahkan diri sendiri",
                "sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat berlebihan dalam bakat ‘iffah, dapat diperbaiki dengan menguatkan bakat himmah, aziimah, ‘izzah, dan waqaar.",
            ],
            [
                'ihsaan', 'Ihsaan', 'الاِحْسَان', 'perfeksionis', 'bakat', 'introvert',
                "perfeksionis",
                "mampu berbuat yang terbaik dalam amalan ibadah maupun dalam memberikan manfaat terhadap diri, orang lain, atau lainnya",
                "Team Penjamin Mutu Pendidikan, Quality Controller (QC), Peran untuk membantu orang hebat menjadi sukses seperti Pelatih, Manajer, Mentor, Guru, Transformational leader",
                "Tadriibud Du’aat, Hadits, Fiqih, Syariah, Manajemen, Manajemen Bisnis, Ilmu Sains dan Teknologi",
                "Isaa’ah (الإِسَاءة) (berbuat kerusakan) — perbuatan yang menjadikan sesuatu menjadi lebih buruk atau perbuatan yang merusak",
                "Sifat tercela isaa’ah (الإِسَاءة) (berbuat kerusakan) yang ditimbulkan akibat meremehkan bakat ihsaan, dapat diperbaiki dengan menguatkan bakat ihsaan itu sendiri dan bakat lainnya, yaitu antara lain bakat himmah, ‘aziimah, nasyaath, ‘izzah, dan waqaar.",
                "Tabdziir (التَّبْذِيْر) (boros, berlebihan) — membelanjakan atau melakukan sesuatu pada hal yang seharusnya tidak diperlukan, berlebihan dalam membelanjakan atau berperilaku",
                "sifat tercela tabdziir (التَّبْذِيْر) (boros, berlebihan) yang ditimbulkan akibat berlebihan dalam bakat ihsaan, dapat diperbaiki dengan menguatkan bakat qanaa’ah dan tawaadhu’.",
            ],
            [
                'itsaar', 'Itsaar', 'الاِيْثَار', 'melayani', 'hati', 'extrovert',
                "suka mendahulukan orang lain",
                "memiliki kecenderungan untuk lebih mendahulukan orang lain dari pada dirinya sendiri dan rela berkorban dalam memberikan manfaat atau dalam menghindari sesuatu, walaupun dirinya lebih membutuhkannya",
                "Da’i/da’iyah, Pengelola lembaga sosial seperti panti asuhan, Laziz, Baitul Maal; Konselor, Pelayanan Pelanggan, Maintenance, Perawat, Pekerja Sosial, Relawan, Team Palang Merah",
                "Tadriibud Du’aat, Tarbiyah, Penyuluh Masyarakat, Kesehatan Masyarakat, Keperawatan, Kebidanan, Kedokteran, Kepalangmerahan",
                "Bakhil (البُخْل) (bakhil) — menahan kepemilikan yang seharusnya tidak dicegah, menahan sesuatu yang seharusnya tidak ditahan, atau menahan sesuatu yang wajib",
                "Sifat tercela bukhl (البُخْل) (bakhil) yang ditimbulkan akibat meremehkan bakat itsaar, dapat diperbaiki dengan menguatkan bakat itsaar itu sendiri dan bakat lainnya, yaitu antara lain bakat juud, nushrah, dan nashiihah.",
                "Dzull (الذُّلّ) (lemah) — kelemahan jiwa dikarenakan ketidakmampuan untuk mempertahankan hak pribadinya",
                "sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat berlebihan dalam bakat itsaar, dapat diperbaiki dengan menguatkan bakat syajaa’ah, munaafasah, dan ghairah.",
            ],
            [
                'izzah', "'Izzah", 'العِزَّة', 'harga diri', 'bakat', 'extrovert',
                "mampu mempertahankan harga diri",
                "mampu bersikap kuat, tegas, dan tangguh dalam mempertahankan kemuliaan atau harga dirinya, sehingga tidak direndahkan dan dapat mengalahkan pihak lain",
                "Utusan, Duta Besar, Da’i, Da’iyah, Guru, Pengajar, Pembawa Misi, Leader",
                "Ilmu Aqidah, Syariah, Fiqih, Tadriibud Du’aat, Keguruan, Hubungan Internasional, Manajemen",
                "Dzull (الذُّلّ) (lemah) — lemahnya jiwa sehingga tidak mampu mempertahankan prinsipnya",
                "Sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat meremehkan bakat ‘izzah, dapat diperbaiki dengan menguatkan bakat ‘izzah itu sendiri dan bakat lainnya, yaitu antara lain bakat himmah, ‘aziimah, nasyaath, ihsaan, dan waqaar",
                "Kibr (الكِبْر) (sombong) — karena gengsi dan egonya, sehingga menolak kebenaran dan merendahkan orang lain",
                "sifat tercela kibr (الكِبْر) (sombong) yang ditimbulkan akibat berlebihan dalam bakat ‘izzah, dapat diperbaiki dengan menguatkan bakat tawaadhu’, qanaa’ah, dan hayaa’.",
            ],
            [
                'juud', 'Juud', 'الجُوْد', 'dermawan', 'hati', 'extrovert',
                "dermawan terhadap orang lain",
                "senang memberikan sesuatu tanpa diminta dan tidak mengharap ucapan terima kasih atau bentuk balasan lainnya",
                "Relawan, Petugas Sosial, Konselor, Perawat, Pengelola LAZIZ, Pengelola Baitul Maal, Panitia Bulan Amal, Entrepreneur, Leader, manajer",
                "Pendidikan Agama Islam, Ilmu Fiqih, Manajemen, Keperawatan, Sosiologi, Bimbingan Konseling, Manajemen Bisnis",
                "Bukhl (البُخْل) (kikir) — menahan sesuatu yang seharusnya tidak ditahan bahkan yang seharusnya wajib dikeluarkan",
                "Sifat tercela bukhl (البُخْل)(kikir) yang ditimbulkan akibat meremehkan bakat juud, dapat diperbaiki dengan menguatkan bakat juud itu sendiri dan bakat lainnya, yaitu antara lain bakat nushrah dan nashiihah.",
                "Tabdzir (التَّبْذِيْر) (boros) — mengeluarkan sesuatu pada hal-hal yang seharusnya tidak terlalu perlu dan berlebihan dalam membelanjakan sesuatu",
                "sifat tercela tabdzir (التَّبْذِيْر) (boros) yang ditimbulkan akibat berlebihan dalam bakat juud, dapat diperbaiki dengan menguatkan bakat tawaadhu’, qanaa’ah, dan hayaa’.",
            ],
            [
                'kitmaanus_sirr', 'Kitmaanus Sirr', 'كِتْمَانُ السِّرِّ', 'jaga rahasia', 'hati', 'introvert',
                "mampu menjaga rahasia meskipun bukan aib",
                "mampu menutupi suatu kabar (bukan aib) agar tidak tersebar untuk mencegah timbulnya keburukan jika tersebar",
                "Hakim, Pengacara, Konselor, Intelijen, Administrator, Pengelola Dokumen Negara, Psikolog, Kepolisian, Militer, Keuangan Negara",
                "Hukum Islam, Syariah, Sekolah Tinggi Pertahanan Nasional, Politeknik Keuangan Negara, Akademi Kepolisian, Akademi Militer, Administrasi, Akuntansi",
                "Ifsyaa’us sirr (إِفْشَاءُ السِّرّ) (membocorkan rahasia) — membocorkan rahasia orang yang memberikan kepercayaan kepadanya",
                "Sifat tercela ifsyaa’us sirr (إِفْشَاءُ السِّرّ) (membocorkan rahasia) yang ditimbulkan akibat meremehkan bakat kitmaanus sirr, dapat diperbaiki dengan menguatkan bakat kitmaanus sirr itu sendiri dan bakat lainnya, yaitu antara lain bakat amaanah dan satr.",
                "Bukhl (البُخْل) (bakhil) — menahan sesuatu yang seharusnya tidak ditahan untuk dikabarkan",
                "sifat tercela bukhl (البُخْل) (bakhil) yang ditimbulkan akibat berlebihan dalam bakat kitmaanus sirr, dapat diperbaiki dengan menguatkan bakat juud, nushrah, nashiihah dan fashaahah.",
            ],
            [
                'mahabbah', 'Mahabbah', 'المَحَبَّة', 'penuh cinta', 'hati', 'extrovert',
                "suka memperdalam hubungan yang telah terjalin",
                "memiliki kecintaan yang mendalam dan khusus terhadap sesuatu untuk memperkuat dan meperdalam hubungan",
                "Guru Privat, Terapist, Konselor, Psikolog, Guru PAUD, Peternak Hewan Rumahan, Perawat",
                "Tarbiyah, Pendidikan Guru TK (PGTK), Keguruan, Pendidikan Terapist, Keperawatan, Peternakan",
                "Karaahiyah (الكَرَاهِيَة) (kebencian) — ketidaksukaan menjalin kedekatan dalam suatu hubungan sehingga mengakibatkan kerenggangan hubungan",
                "Sifat tercela karaahiyah (الكَرَاهِيَة) (kebencian) yang ditimbulkan akibat meremehkan bakat mahabbah, dapat diperbaiki dengan menguatkan bakat mahabbah itu sendiri dan bakat lainnya, yaitu antara lain bakat ulfah, ta’aawun dan basyaasyah.",
                "Taqliid (التَّقْلِيْد) (fanatik buta) — mencintai, mengikuti dan mempercayai orang lain atau sesuatu tanpa mempedulikan kebenaran dan manfaatnya",
                "sifat tercela taqliid (التَّقْلِيْد) (fanatik buta) yang ditimbulkan akibat berlebihan dalam bakat mahabbah, dapat diperbaiki dengan menguatkan bakat izzah dan waqaar.",
            ],
            [
                'munaafasah', 'Munaafasah', 'المُنَافَسَة', 'kompetitif', 'hati', 'extrovert',
                "suka bersaing dengan orang lain",
                "senang bersaing dengan usaha semaksimal mungkin agar dirinya menjadi yang paling unggul dari yang lainnya",
                "Bidang Periklanan, Motivator, Competition Organizer, Team Litbang Perusahaan, Konsultan atau pengelola perusahaan dalam menghadapi persaingan, Sales, Pelatih Olahraga",
                "Manajemen, Manajemen Bisnis, Olahraga dan Kesehatan, Sekolah Pelatih",
                "Dzull (الذُّلّ) (lemah) — tidak mampu untuk bangkit atau ketakutan dalam menghadapi persaingan dalam kehidupan sehingga menjadi terpuruk",
                "Sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat meremehkan bakat munaafasah, dapat diperbaiki dengan menguatkan bakat munaafasah itu sendiri dan bakat lainnya, yaitu antara lain bakat ghairah, syajaa’ah, himmah, ihsaan, dan nasyaath.",
                "Zhulm (الظُّلْم)(zhalim) — berbuat aniaya dan curang kepada orang atau pihak lain hanya karena ingin unggul dari yang lain",
                "sifat tercela zhulm (الظُّلْم)(zhalim) yang ditimbulkan akibat berlebihan dalam bakat munaafasah, dapat diperbaiki dengan menguatkan bakat ‘adaalah, itsaar, rahmah, qanaa’ah, dan tawaadhu’.",
            ],
            [
                'muzaah', 'Muzaah', 'المُزَاح', 'humoris', 'hati', 'extrovert',
                "suka bercanda untuk mengakrabkan hubungan",
                "senang bercanda tapi tidak menyinggung orang lain, santai dalam bergaul",
                "MC, Pranata cara (Jawa), Penerima Tamu, Motivator, Comentator",
                "Ilmu Komunikasi, Marketing, Keguruan",
                "Jafaa’ (الجَفَاء) (kaku) — bersikap kaku dalam pergaulan sehingga dapat mengurangi keakraban",
                "Sifat tercela jafaa’ (الجَفَاء) (kaku) yang ditimbulkan akibat meremehkan bakat muzaah, dapat diperbaiki dengan menguatkan bakat muzaah itu sendiri dan bakat lainnya, yaitu antara lain bakat basyaasyah, rifq, dan mahabbah.",
                "Istihzaa’ (الاِسْتِهْزَاء) (mengejek) — berkata dan berbuat untuk tujuan bermain-main, bukan untuk keseriusan, dengan mengejek sifat dan perilaku orang yang diejek",
                "sifat tercela istihzaa’ (الاِسْتِهْزَاء) (mengejek) yang ditimbulkan akibat berlebihan dalam bakat muzaah, dapat diperbaiki dengan menguatkan bakat shamt, ‘iffah, dan shidq.",
            ],
            [
                'nashiihah', 'Nashiihah', 'النَّصِيْحَة', 'nasehat', 'akal', 'extrovert',
                "suka menasehati orang lain",
                "senang memberikan nasehat, saran, dorongan kepada orang lain agar menjadi lebih baik",
                "Penasehat, Da’i, Da’iyah, Guru, Konselor, Konsultan, Pemimpin, Manajer, Leader, Supervisor, Evaluator, Pengawas, Pelatih, Pelayanan Pelanggan (Customer Service)",
                "Ilmu Agama, Ilmu Komunikasi, Ilmu Manajemen, Ilmu Hukum, Psikologi, Bimbingan Konseling",
                "Ghisy (الغِشِّ)(menipu) — menjerumuskan dan membiarkan seseorang menjadi lebih buruk keadaannya. (Sebagaimana yang dilakukan iblis kepada Adam dan Hawa)",
                "Sifat tercela ghisy (الغِشِّ)(menipu) yang ditimbulkan akibat meremehkan bakat nashiihah, dapat diperbaiki dengan menguatkan bakat nashiihah itu sendiri dan bakat lainnya, yaitu antara lain bakat nushrah dan juud.",
                "Tanfiir (التَّنْفِير) (menakut-nakuti) — menakut-nakuti orang lain sehingga menjadi bosan, lari atau enggan terhadap sesuatu yang dinasehatkan",
                "sifat tercela tanfiir (التَّنْفِير) (menakut-nakuti) yang ditimbulkan akibat berlebihan dalam bakat nashiihah, dapat diperbaiki dengan menguatkan bakat rifq, mahabbah, rahmah, dan itsaar.",
            ],
            [
                'nasyaath', 'Nasyaath', 'النَّشَاط', 'semangat', 'bakat', 'extrovert',
                "bersemangat menyelesaikan pekerjaan",
                "memiliki semangat dan bekerja keras untuk menyelesaikan apa yang sedang dilakukan",
                "Penyelenggara Lembaga pendidikan, Pemborong Proyek, Tenaga Penjual/Sales, Teknisi Proyek, Teknisi Lapangan, Pekerja Lapangan, Relawan, Petugas SAR, Pemadam Kebakaran",
                "Teknik Sipil, Teknik Permesinan, Teknik Instalasi dan Tenaga Listrik, Teknik perkayuan, Teknik Otomotif, Keperawatan, Kebidanan, Marketing, dan jurusan lain yang banyak menumbuhkan sifat kerja keras",
                "Kasal (الكَسَل) (malas) — merasa berat dan lamban terhadap sesuatu, padahal memiliki kekuatan, karena tidak adanya keinginan untuk berbuat kebaikan",
                "Sifat tercela kasal (الكَسَل) (malas) yang ditimbulkan akibat meremehkan bakat nasyaath, dapat diperbaiki dengan menguatkan bakat nasyaath itu sendiri dan bakat lainnya, yaitu antara lain bakat himmah, ihsaan, dan ‘aziimah.",
                "Thama’ (الطَّمَع) (serakah) — keinginan kuat untuk mendapatkan sesuatu yang sebenarnya tidak dibutuhkannya",
                "sifat tercela thama’ (الطَّمَع) (serakah) yang ditimbulkan akibat berlebihan dalam bakat nasyaath, dapat diperbaiki dengan menguatkan bakat qanaa’ah dan tawaadhu’.",
            ],
            [
                'nubl', 'Nubl', 'النُّبْل', 'cerdik', 'akal', 'introvert',
                "banyak akal untuk menemukan solusi masalah",
                "cepat mengerti (tentang situasi dan sebagainya) dan pandai mencari pemecahannya, panjang atau banyak akalnya",
                "Konsultan, Konselor, Team Penanganan Masalah, Perencana Strategi, Manajer, Leader, Team Pencari Solusi Bagi Perusahaan/Lembaga Yang Bermasalah",
                "Ilmu Fiqih, Ekonomi Syariah, Manajemen, Sosiologi, Hubungan Internasional, atau jurusan lain yang banyak menumbuhkan kemampuan untuk menemukan solusi",
                "Jahl (الجَهْل) (kebodohan) — kebingungan dalam menghadapi masalah karena tidak mengetahui solusi atau jalan keluar yang harus ditempuhnya",
                "Sifat tercela jahl (الجَهْل) (kebodohan) yang ditimbulkan akibat meremehkan bakat nubl, dapat diperbaiki dengan menguatkan bakat nubl itu sendiri dan bakat lainnya, yaitu antara lain bakat firaasah dan husnuzhan.",
                "Ghisy (الغِشِّ)(licik) — menipu orang lain dengan kemampuannya bersiasat yang tidak diketahui oleh orang lain",
                "sifat tercela Ghisy (الغِشِّ)(licik) yang ditimbulkan akibat berlebihan dalam bakat nubl, dapat diperbaiki dengan menguatkan bakat shidq, shamt, ‘iffah, dan hayaa’.",
            ],
            [
                'nushrah', 'Nushrah', 'النُّصْرَة', 'menolong', 'hati', 'extrovert',
                "suka menolong orang dari kesulitan",
                "senang membantu orang lain yang mengalami kesusahan (atau terzhalimi) hingga terlepas dari kesusahan tersebut",
                "Polisi, Penegak Hukum, Relawan, Pengacara, Lembaga Bantuan Hukum (LBH), Hakim, atau profesi lain yang terkait dengan bantuan terhadap orang yang terzhalimi",
                "PAI, Syari’ah, Ilmu Hukum, Ilmu Sosial, Akademi Kepolisian, Akademi Militer",
                "Khidzlaan (الخِذْلَان) (menerlantarkan) — membiarkan, tidak peduli, tidak memelihara sesuatu yang seharusnya dipelihara",
                "Sifat tercela khidzlaan (الخِذْلَان) (menerlantarkan) yang ditimbulkan akibat meremehkan bakat nushrah, dapat diperbaiki dengan menguatkan bakat nushrah itu sendiri dan bakat lainnya, yaitu antara lain bakat juud dan nashiihah.",
                "Zhulm (الظُّلْم) (zhalim) — meletakkan sesuatu bukan pada tempatnya",
                "sifat tercela zhulm (الظُّلْم) (zhalim) yang ditimbulkan akibat berlebihan dalam bakat nushrah, dapat diperbaiki dengan menguatkan bakat ‘adaalah, itsaar, rahmah, qanaa’ah, dan tawaadhu’.",
            ],
            [
                'qanaa_ah', "Qanaa'ah", 'القَنَاعَة', 'sederhana', 'bakat', 'introvert',
                "merasa cukup dengan apa yang telah aku terima",
                "rela menerima apapun yang diberikan kepadanya dan merasa cukup terhadap apa yang telah diterimanya",
                "Team Perencana Anggaran Belanja Lembaga, Team Penghemat Anggaran Perusahaan, Petugas Lapangan, Explorer, Relawan",
                "Ilmu Ekonomi, Manajemen Bisnis, Ilmu Sosial, Ilmu Manajemen Sumber Daya Alam, Akuntansi, atau jurusan lain yang terkait dengan perhitungan nilai ekonomis",
                "Thama’ (الطَّمَع) (serakah, tamak) — berkeinginan kuat untuk mendapatkan sesuatu yang sebenarnya tidak dibutuhkannya",
                "Sifat tercela thama’ (الطَّمَع) (serakah, tamak) yang ditimbulkan akibat meremehkan bakat qanaa’ah, dapat diperbaiki dengan menguatkan bakat qanaa’ah itu sendiri dan bakat lainnya, yaitu antara lain bakat tawaadhu’.",
                "Dzull (الذُّلّ) (lemah) — ketidakmampuan untuk menuntut haknya, sehingga meyusahkan dirinya sendiri",
                "sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat berlebihan dalam bakat qanaa’ah, dapat diperbaiki dengan menguatkan bakat himmah, ihsaan, aziimah, nasyaath, ‘izzah, dan waqaar.",
            ],
            [
                'rahmah', 'Rahmah', 'الرَّحْمَة', 'belas kasih', 'hati', 'extrovert',
                "berbelas kasihan kepada orang lain",
                "berbelas kasihan dan bersimpati karena ingin memberikan yang terbaik bagi yang dibelas kasihi",
                "Konselor, Pembinaan Keluarga Sejahtera, Guru PAUD-TK, Psikolog, Sales, HRD, Perawat, Operator Telepon, Psikiater, Dispatcher, Layanan Pelanggan",
                "PAI, Keguruan, Tarbiyah, Ilmu Sosial, Manajemen, Manajemen Bisnis, Psikologi, atau jurusan lain yang terkait dengan penguatan empati atau perasaan",
                "Ghilzhah (الغِلْظَة) (kasar) — kejam, kurang belas kasih, tidak pengertian dan kurang hasrat untuk kebaikan",
                "Sifat tercela ghilzhah (الغِلْظَة) (kasar) yang ditimbulkan akibat meremehkan bakat rahmah, dapat diperbaiki dengan menguatkan bakat rahmah itu sendiri dan bakat lainnya, yaitu antara lain bakat itsaar dan hilm.",
                "Taqliid (التَّقْلِيْد) (fanatik buta) — mengikuti dan menuruti perkataan dan perbuatan orang lain tanpa mempedulikan kebenarannya",
                "sifat tercela taqliid (التَّقْلِيْد) (fanatik buta) yang ditimbulkan akibat berlebihan dalam bakat rahmah, dapat diperbaiki dengan menguatkan bakat ‘izzah dan waqaar.",
            ],
            [
                'rifq', 'Rifq', 'الرِّفْق', 'lemah lembut', 'hati', 'introvert',
                "lemah lembut dalam bergaul",
                "mampu mengambil sisi termudah dalam semua urusan, sehingga lemah-lembut dalam perkataan dan perbuatan",
                "Guru, Pendidik, Penasehat, Konsultan, Manajer, Supervisor, Pengawas, Marketing, Customer Service, Leader",
                "Syari’ah, Keguruan, Psikologi Islam, Manajemen, Manajemen Bisnis",
                "‘Anfu (العَنْفُ) (kasar) — bersikap kasar kepada orang lain yang melakukan kekeliruan",
                "Sifat tercela ‘anfu (العَنْفُ) (kasar) yang ditimbulkan akibat meremehkan bakat rifq, dapat diperbaiki dengan menguatkan bakat rifq itu sendiri dan bakat lainnya, yaitu antara lain bakat basyaasyah dan mahabbah.",
                "Dzull (الذُّلّ) (lemah) — kelemahan jiwa sehingga tidak mampu memperbaiki suatu kesalahan atau pelanggaran yang dilakukan oleh seseorang",
                "sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat berlebihan dalam bakat rifq, dapat diperbaiki dengan menguatkan bakat syajaa’ah, ghairah, dan munaafasah.",
            ],
            [
                'satr', 'Satr', 'السَّتْر', 'menutup aib', 'hati', 'introvert',
                "mampu menutupi aib diri sendiri atau orang lain",
                "mampu menutupi dan tidak menampakkan atau menceritakan aib diri sendiri atau orang lain",
                "Ajudan, Asisten Pribadi, Sekretaris, Agen Rahasia, Notaris",
                "Akademi Kepolisian, Akademi Militer, Ilmu Kenotariatan, Administrasi",
                "Ghiibah (الغِيْبَة) (menggunjing) — menyebarkan aib diri (mujaahir) dan orang lain yang seharusnya ditutupi",
                "Sifat tercela ghiibah (الغِيْبَة) (menggunjing), yaitu menyebarkan aib diri (mujaahir) yang ditimbulkan akibat meremehkan bakat satr, dapat diperbaiki dengan menguatkan bakat satr itu sendiri dan bakat lainnya, yaitu antara lain bakat kitmaanus sirr, amaanah, dan shabr.",
                "Dzull (الذُّلّ) (Lemah) — kelemahan untuk mengatakan sesuatu yang seharusnya dikatakan, sehingga menyembunyikan ilmu yang seharusnya disampaikan",
                "sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat berlebihan dalam bakat satr, dapat diperbaiki dengan menguatkan bakat fashaahah dan nashiihah.",
            ],
            [
                'shabr', 'Shabr', 'الصَّبْر', 'sabar', 'hati', 'introvert',
                "bersabar terhadap apa yang menimpa",
                "mampu menahan diri untuk tidak bereaksi (menolak/membalas) terhadap apa yang diterimanya, baik berupa tekanan dari luar, kesulitan hidup, maupun berupa hambatan dalam meraih apa yang diharapkan",
                "Da’i, Khadimul Ummah, Public Serving, Pengemban Misi, Pelayanan Pelanggan, Maintenance, Perawat, Pekerja Sosial, Relawan",
                "Syari’ah, Keperawatan, Kebidanan, Ilmu Sosial, Kepariwisataan, Manajemen",
                "Jaza’ (الجَزَع) (tidak sabar) — tampaknya kegelisahan yang menjadikan seseorang bereaksi (menolak/membalas) perlakukan terhadap dirinya sehingga menimbulkan kerusakan yang lebih besar",
                "Sifat tercela jaza’ (الجَزَع) (tidak sabar) yang ditimbulkan akibat meremehkan bakat shabr, dapat diperbaiki dengan menguatkan bakat shabr itu sendiri dan bakat lainnya, yaitu antara lain bakat anaah dan hilm.",
                "Dzull (الذُّلّ) (lemah) — kelemahan untuk mempertahankan dan mendapatkan hak yang seharusnya diambilnya",
                "sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat berlebihan dalam bakat shabr, dapat diperbaiki dengan menguatkan bakat syajaa’ah, ghairah, dan munaafasah.",
            ],
            [
                'shamt', 'Shamt', 'الصَّمْت', 'pendiam', 'akal', 'introvert',
                "mampu menjaga perkataan",
                "memiliki sikap banyak diam dan jika berbicara seperlunya atau yang bermanfaat saja",
                "Pelayan Umat, Ajudan, Pelaksana Lapangan, Penulis, Peneliti, Analis",
                "Teknik Kimia, Teknik Informatika (IT), Administrasi Perkantoran",
                "Ghiibah (الغِيْبَة) (menggunjing) — membicarakan suatu hal pada diri seseorang, baik dengan perkataan, isyarat, atau menirukan, yang apabila dia mengetahuinya, maka dia tidak menyukainya",
                "Sifat tercela ghiibah (الغِيْبَة) (menggunjing) yang ditimbulkan akibat meremehkan bakat shamt, dapat diperbaiki dengan menguatkan bakat shamt itu sendiri dan bakat lainnya, yaitu antara lain bakat shidq, ‘iffah dan hayaa’.",
                "Jubn (الجُبْن) (penakut) — takut untuk berbicara yang seharusnya tidak perlu ditakuti",
                "sifat tercela jubn (الجُبْن) (penakut) yang ditimbulkan akibat berlebihan dalam bakat shamt, dapat diperbaiki dengan menguatkan bakat syajaa’ah, ghairah, dan munaafasah.",
            ],
            [
                'shidq', 'Shidq', 'الصِّدْق', 'jujur', 'bakat', 'extrovert',
                "mampu berkata dan berbuat apa adanya",
                "memiliki sikap mudah menerima kebenaran serta berkata dan berbuat sesuai dengan apa yang sebenarnya",
                "Pengelola Dana Umat (Donasi), Kurir/Duta, Bidang Keuangan, Pengelola Baitul Mal, Team Belanja (Pengadaan Sarana Prasarana), Hakim, Jaksa",
                "Administrasi Keuangan, Hukum Islam, Ilmu Hukum, Manajemen",
                "Kadzib (الكَذِب) (dusta) — mengabarkan dan berperilaku sesuatu yang tidak sesuai dengan yang sebenarnya",
                "Sifat tercela kadzib (الكَذِب) (dusta) yang ditimbulkan akibat meremehkan bakat shidq, dapat diperbaiki dengan menguatkan bakat shidq itu sendiri dan bakat lainnya, yaitu antara lain bakat ‘iffah dan shamt.",
                "Ifsyaa’us sirr (إِفْشَاءُ السِّرّ) (membocorkan rahasia) — membocorkan rahasia orang yang memberikan kepercayaan kepadanya",
                "sifat tercela ifsyaa’us sirr (إِفْشَاءُ السِّرّ) (membocorkan rahasia) yang ditimbulkan akibat berlebihan dalam bakat shidq, dapat diperbaiki dengan menguatkan bakat ‘izzah, waqaar dan syajaa’ah.",
            ],
            [
                'syajaa_ah', "Syajaa'ah", 'الشَّجَاعَة', 'berani', 'hati', 'extrovert',
                "berani menghadapi orang secara langsung",
                "teguh dan berani dalam menghadapi orang untuk mengendalikannya, mengaturnya, dan menguasainya",
                "Tentara, TNI, Komandan, Pengusaha, Petugas Keamanan, Sales, Negosiator, Wartawan, Pengacara, HRD, Pembelian",
                "Ilmu Hukum Islam, Syariah, Akademi Militer, Akademi Kepolisian, Manajemen, Manajemen Bisnis",
                "Jubn (الجُبْن) (penakut) — takut terhadap sesuatu yang seharusnya tidak perlu ditakuti",
                "Sifat tercela jubn (الجُبْن) (penakut) yang ditimbulkan akibat meremehkan bakat syajaa’ah, dapat diperbaiki dengan menguatkan bakat syajaa’ah itu sendiri dan bakat lainnya, yaitu antara lain bakat ghairah, munaafasah, himmah, ihsaan dan ‘aziimah.",
                "Tahawwur (التَّهَوُّر) (ceroboh) — bertindak tanpa dipikirkan dan dinalar terlebih dahulu, dan tidak peduli dengan apa yang akan terjadi",
                "sifat tercela tahawwur (التَّهَوُّر) (ceroboh) yang ditimbulkan akibat berlebihan dalam bakat syajaa’ah, dapat diperbaiki dengan menguatkan bakat shabr, hilm dan anaah.",
            ],
            [
                'ta_aawun', "Ta'aawun", 'التَّعَاوُن', 'kerjasama', 'hati', 'extrovert',
                "mampu bekerjasama dengan orang lain",
                "senang bekerjasama dengan orang lain untuk mencapai tujuan bersama",
                "Penggerak dakwah, Guru, Pembangun jaringan antara orang-orang dengan cara pandang yang berbeda, Juru Damai, Penasehat, Wirausahawan, Manajer",
                "Dakwah, Manajemen, Ilmu Komunikasi, Ilmu Ekonomi, Keguruan",
                "‘Udwaan (العُدْوَان) (memusuhi) — berupa ketidakadilan dalam bermuamalah, seperti merampas hak orang lain, menindas, dll",
                "Sifat tercela ‘udwaan (العُدْوَان) (memusuhi) yang ditimbulkan akibat meremehkan bakat ta’aawun, dapat diperbaiki dengan menguatkan bakat ta’aawun itu sendiri dan bakat lainnya, yaitu antara lain bakat ‘adaalah dan ulfah.",
                "Dzull (الذُّلّ) (lemah) — ketundukan jiwa dikarenakan ketidakmampuan untuk mempertahankan sesuatu, sehingga menjadi tidak mandiri dan tergantung pada orang lain",
                "sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat berlebihan dalam bakat ta’aawun, dapat diperbaiki dengan menguatkan bakat syajaa’ah, ghairah dan munaafasah.",
            ],
            [
                'tawaadhu', "Tawaadhu'", 'التَّوَاضُع', 'rendah hati', 'bakat', 'introvert',
                "rendah hati terhadap kehebatan yang dimiliki",
                "memiliki rasa tidak suka menampakkan atau ditampakkan kelebihannya meskipun memiliki kemampuan dan kelebihan",
                "Guru TK, Konselor, Pemimpin, Relawan, Pengasuh Panti Asuhan, Penasehat, public serving",
                "Dakwah, Psikologi, Sastra, Sosiologi, Ilmu Komunikasi, Keguruan, Ilmu Kejiwaan",
                "Kibr (الكِبْر) (takabur atau sombong) — melihat diri lebih besar dari orang lain, sehingga menolak kebenaran dan merendahkan orang lain, tidak menerima nasehat dan suka mencela kelemahan orang lain",
                "Sifat tercela kibr (الكِبْر) (takabur atau sombong) yang ditimbulkan akibat meremehkan bakat tawaadhu’, dapat diperbaiki dengan menguatkan bakat tawaadhu’ itu sendiri dan bakat lainnya, yaitu antara lain bakat qanaa’ah dan hayaa’.",
                "Dzull (الذُّلّ) (lemah) — ketidakmampuan untuk mempertahankan dan menuntut sesuatu yang menjadi haknya, sehingga menjadi dihinakan",
                "sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat berlebihan dalam bakat tawaadhu’, dapat diperbaiki dengan menguatkan bakat syajaa’ah, ghairah dan munaafasah.",
            ],
            [
                'ulfah', 'Ulfah', 'الاُلْفَة', 'bersatu', 'hati', 'extrovert',
                "suka berkumpul dan mengumpulkan orang",
                "senang bersatu dan berkumpul karena adanya kesamaan",
                "Da’i, Marketing, Pengelola lembaga sosial, Motivator kelompok, Wakil suara minoritas, Pemimpin kelompok dengan latar budaya beragam, Mentor bagi mereka yang baru bergabung di dalam organisasi",
                "Dakwah, Manajemen Pendidikan, Manajemen Bisnis, Sosiologi",
                "Namiimah (النَّمِيْمَة) (adu domba) — menyampaikan perkataan seseorang kepada orang lain dengan tujuan merusak hubungan di antara mereka",
                "Sifat tercela namiimah (النَّمِيْمَة) (adu domba) yang ditimbulkan akibat meremehkan bakat ulfah, dapat diperbaiki dengan menguatkan bakat ulfah itu sendiri dan bakat lainnya, yaitu antara lain bakat ta’aawun dan ‘adaalah.",
                "Taqliid (التَّقْلِيْد) (fanatik buta) — mengikuti dan mempercayai perkataan dan perbuatan kebanyakan orang di kelompoknya tanpa mempedulikan kebenarannya",
                "sifat tercela taqliid (التَّقْلِيْد) (fanatik buta) yang ditimbulkan akibat berlebihan dalam bakat ulfah, dapat diperbaiki dengan menguatkan bakat ‘izzah dan waqaar.",
            ],
            [
                'wafaa', "Wafaa'", 'الوَفَاء', 'tepat janji', 'hati', 'introvert',
                "teguh dalam memenuhi janji",
                "teguh memegang janji yang telah disepakati, walaupun kondisinya sebagai pihak yang dicurangi",
                "Pengajar Aqidah, Pelayanan Masyarakat, Pelayanan Pelanggan, Birokrat, Relator, Account Sales, Manajer, Keuangan, Quality Control, Keamanan",
                "Ilmu Aqidah, Akuntansi, Administrasi, Manajemen, Sosiologi, Ilmu Hukum",
                "Ghadr (الغَدْر) (langar janji) — melanggar janji yang telah disepakati bersama",
                "Sifat tercela ghadr (الغَدْر) (langar janji) yang ditimbulkan akibat meremehkan bakat wafaa’, dapat diperbaiki dengan menguatkan bakat wafaa’ itu sendiri dan bakat lainnya, yaitu antara lain bakat shidq, hayaa’ dan ‘iffah.",
                "Taqliid (التَّقْلِيْد) (fanatik buta) — mengikuti, mempercayai, dan menyepakati perjanjian dengan pihak lain tanpa mempedulikan kerugian dan kebenarannya",
                "sifat tercela taqliid (التَّقْلِيْد) (fanatik buta) yang ditimbulkan akibat berlebihan dalam bakat wafaa’, dapat diperbaiki dengan menguatkan bakat ‘izzah dan waqaar.",
            ],
            [
                'waqaar', 'Waqaar', 'الوَقَار', 'wibawa', 'bakat', 'introvert',
                "berwibawa",
                "memiliki sikap tenang tapi kelihatan tangguh, tidak banyak bicara, tidak tergesa tapi mendahului dalam melakukan semua urusan",
                "Trainer, Pendamping pelatihan, Peningkatan SDM, Konsultan, Team penanganan masalah lembaga/perusahaan",
                "Teknik Sipil, Teknik Listrik, Teknik Mesin, Manajemen",
                "Dzull (الذُّلّ) (lemah) — tidak mampu untuk bangkit atau ketakutan dalam menghadapi persaingan dalam kehidupan sehingga menjadi terpuruk",
                "Sifat tercela dzull (الذُّلّ) (lemah) yang ditimbulkan akibat meremehkan bakat waqaar, dapat diperbaiki dengan menguatkan bakat waqaar itu sendiri dan bakat lainnya, yaitu antara lain bakat Izzah dan Aziimah.",
                "Ujb (العُجْب) (bangga diri) — persepsi tentang tingginya derajat pribadinya, yang sebenarnya tidak layak dinisbatkan kepadanya",
                "sifat tercela ujb (العُجْب) (bangga diri) yang ditimbulkan akibat berlebihan dalam bakat waqaar, dapat diperbaiki dengan menguatkan bakat tawaadhu’ dan qanaa’ah.",
            ],
        ];

        foreach ($karakter as $index => $row) {
            [
                $kode, $nama, $arab, $terjemahan, $dimensi, $tipe,
                $labelDiri, $definisi, $profesi, $jurusan,
                $tercelaMelalaikan, $caraMelalaikan,
                $tercelaBerlebihan, $caraBerlebihan,
            ] = $row;

            $id        = $index + 1;        // 1-40
            $nomorSoal = $id + 36;          // soal 37-76
            $dimensi   = $dimensiStrukturJiwa[$kode] ?? $dimensi; // otoritatif: prd_dimensi.md
            $kelompok  = $kelompokMap[$dimensi];

            Karakter::updateOrCreate(
                ['id' => $id],
                [
                    'kode'                         => $kode,
                    'nama_karakter'                => $nama,
                    'nama_arab'                    => $arab,
                    'terjemahan'                   => $terjemahan,
                    'label_diri'                   => $labelDiri,
                    'definisi'                     => $definisi,
                    'dimensi'                      => $dimensi,
                    'kelompok'                     => $kelompok,
                    'tipe'                         => $tipe,
                    'profesi'                      => $profesi,
                    'jurusan'                      => $jurusan,
                    'sifat_tercela_melalaikan'     => $tercelaMelalaikan,
                    'cara_memperbaiki_melalaikan'  => $caraMelalaikan,
                    'sifat_tercela_berlebihan'     => $tercelaBerlebihan,
                    'cara_memperbaiki_berlebihan'  => $caraBerlebihan,
                    'nomor_soal'                   => $nomorSoal,
                    'urut_abjad'                   => $id,
                    'urut_grafik'                  => $id,
                ]
            );
        }
    }
}
