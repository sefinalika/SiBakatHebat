<?php

namespace App\Services;

use App\Models\HasilObservasi;
use App\Models\Karakter;
use App\Models\Observasi;
use App\Support\KategoriWarna;
use Illuminate\Support\Collection;

class KalkulasiTB40Service
{
    /**
     * Pembagian section soal (lihat PRD 2.2).
     */
    public const SECTION_AQIDAH = [1, 9];           // soal 1-9
    public const SECTION_IBADAH = [10, 18];         // soal 10-18
    public const SECTION_KARAKTER_BELAJAR = [19, 27]; // soal 19-27
    public const SECTION_BAKAT_UMUM = [28, 36];     // soal 28-36
    public const SECTION_TB40 = [37, 76];           // soal 37-76 (1 soal = 1 karakter)

    /**
     * Mapping gaya belajar -> nomor soal (PRD 3.2).
     */
    public const GAYA_BELAJAR = [
        'as_samu' => ['label' => "As Sam'u", 'arti' => 'mendengar', 'soal' => [19, 22, 25]],
        'al_fuad' => ['label' => 'Al Fuad', 'arti' => 'bergerak', 'soal' => [20, 23, 26]],
        'al_bashar' => ['label' => 'Al Bashar', 'arti' => 'melihat', 'soal' => [21, 24, 27]],
    ];

    /**
     * Mapping bahasa hati -> kode karakter TB-40 (PRD 3.3).
     */
    public const BAHASA_HATI = [
        'perlindungan' => ['label' => 'Perlindungan', 'kode' => ['syajaa_ah', 'ghairah', 'munaafasah', 'himmah', 'juud']],
        'pelayanan' => ['label' => 'Pelayanan', 'kode' => ['itsaar', 'rahmah', 'kitmaanus_sirr', 'satr', 'amaanah', 'hilm', 'shabr']],
        'kebersamaan' => ['label' => 'Kebersamaan', 'kode' => ['basyaasyah', 'rifq', 'muzaah', 'mahabbah', 'ta_aawun', 'ulfah', 'wafaa']],
    ];

    /**
     * Cara menyentuh hati anak per bahasa hati — dipakai sebagai "pemicu motivasi
     * efektif" pada infografis. Diringkas dari docs/prd/prd_dimensi.md.
     */
    public const PEMICU_BAHASA_HATI = [
        'perlindungan' => [
            'Pemberian hadiah',
            'Kata-kata penyemangat',
            'Ditolong saat kesulitan',
            'Diajak bersaing sehat',
        ],
        'pelayanan' => [
            'Dilayani kemauannya',
            'Dijaga rahasianya',
            'Dimaafkan kesalahannya',
            'Disikapi dengan sabar',
        ],
        'kebersamaan' => [
            'Ditemani dan didengarkan',
            'Diberi senyuman',
            'Ditepati janjinya',
            'Diajak bercanda',
        ],
    ];

    /**
     * Rata-rata nilai pada rentang soal [dari, sampai] (inklusif).
     *
     * @param  array<int, int|float>  $jawaban  [nomor_soal => nilai]
     */
    public function rataRataSection(array $jawaban, int $dari, int $sampai): float
    {
        $nilai = [];

        for ($soal = $dari; $soal <= $sampai; $soal++) {
            if (isset($jawaban[$soal])) {
                $nilai[] = (float) $jawaban[$soal];
            }
        }

        if (count($nilai) === 0) {
            return 0.0;
        }

        return round(array_sum($nilai) / count($nilai), 2);
    }

    /**
     * Skor 40 karakter TB-40 dari soal 37-76 (1 soal = 1 karakter).
     *
     * @param  array<int, int|float>  $jawaban  [nomor_soal => nilai]
     * @return array<int, float>  [karakter_id => skor]
     */
    public function hitungSkorKarakter(array $jawaban): array
    {
        [$dari, $sampai] = self::SECTION_TB40;
        $skor = [];

        for ($soal = $dari; $soal <= $sampai; $soal++) {
            if (! isset($jawaban[$soal])) {
                continue;
            }

            $karakterId = $soal - ($dari - 1); // soal 37 => karakter 1
            $skor[$karakterId] = round((float) $jawaban[$soal], 2);
        }

        return $skor;
    }

    /**
     * Nilai ringkas 4 section non-TB40 (PRD 2.3 langkah 1-4).
     *
     * @param  array<int, int|float>  $jawaban
     * @return array<string, float>
     */
    public function hitungSection(array $jawaban): array
    {
        return [
            'aqidah' => $this->rataRataSection($jawaban, ...self::SECTION_AQIDAH),
            'ibadah' => $this->rataRataSection($jawaban, ...self::SECTION_IBADAH),
            'karakter_belajar' => $this->rataRataSection($jawaban, ...self::SECTION_KARAKTER_BELAJAR),
            'bakat_umum' => $this->rataRataSection($jawaban, ...self::SECTION_BAKAT_UMUM),
        ];
    }

    /**
     * Label tingkat keislaman per kategori warna (skala sama dengan PRD 3.4).
     */
    private const LABEL_KEISLAMAN = [
        'hitam' => 'Perlu Bimbingan',
        'abu' => 'Cukup',
        'hijau' => 'Baik',
        'kuning' => 'Sangat Baik',
        'merah' => 'Istimewa',
    ];

    /**
     * Tiga poin penyusun nilai keislaman: prinsip (aqidah), kewajiban (ibadah),
     * dan perilaku (adab & akhlaq). Masing-masing berskala 1-10, urut sesuai
     * slot warna kategorikal pada laporan.
     *
     * Adab dan akhlaq sengaja satu poin: keduanya sama-sama dinilai lewat 40
     * karakter TB-40, sehingga tidak ada soal yang terhitung dua kali.
     *
     * @param  array<int, int|float>  $jawaban  [nomor_soal => nilai]
     * @return array<int, array{kunci:string, label:string, aspek:string, skor:float, sumber:string}>
     */
    public function komponenKeislaman(array $jawaban): array
    {
        return [
            [
                'kunci' => 'aqidah',
                'label' => 'Aqidah',
                'aspek' => 'prinsip',
                'skor' => $this->rataRataSection($jawaban, ...self::SECTION_AQIDAH),
                'sumber' => 'soal 1–9',
            ],
            [
                'kunci' => 'ibadah',
                'label' => 'Ibadah',
                'aspek' => 'kewajiban',
                'skor' => $this->rataRataSection($jawaban, ...self::SECTION_IBADAH),
                'sumber' => 'soal 10–18',
            ],
            [
                'kunci' => 'adab_akhlaq',
                'label' => 'Adab & Akhlaq',
                'aspek' => 'perilaku',
                'skor' => $this->rataRataSection($jawaban, ...self::SECTION_TB40),
                'sumber' => '40 karakter TB-40',
            ],
        ];
    }

    /**
     * Persentase keislaman peserta: rata-rata tiga poin — aqidah, ibadah, dan
     * adab & akhlaq — lalu dipetakan ke kategori keislaman.
     *
     * Skala jawaban 1-10 dipetakan langsung ke persen (skor 7.5 => 75%).
     *
     * @param  array<int, int|float>  $jawaban  [nomor_soal => nilai]
     * @return array{skor:float, persen:int, warna:string, label:string, komponen:array<int, array<string, mixed>>, maks_poin:float}
     */
    public function persentaseKeislaman(array $jawaban): array
    {
        $komponen = $this->komponenKeislaman($jawaban);

        // Rata-rata ketiga poin; setara total poin dibagi poin maksimal (3 x 10).
        $skor = round(array_sum(array_column($komponen, 'skor')) / count($komponen), 2);
        $warna = $this->tentukanWarna($skor);

        return [
            'skor' => $skor,
            'persen' => (int) round($skor * 10),
            'warna' => $warna,
            'label' => self::LABEL_KEISLAMAN[$warna],
            'komponen' => $komponen,
            'maks_poin' => count($komponen) * 10.0,
        ];
    }

    /**
     * Tentukan kategori warna berdasarkan skor (PRD 3.4).
     *
     * 1.0-2.9 => hitam, 3.0-4.9 => abu, 5.0-6.9 => hijau,
     * 7.0-8.9 => kuning, 9.0-10 => merah
     */
    public function tentukanWarna(float $skor): string
    {
        return match (true) {
            $skor < 3 => 'hitam',
            $skor < 5 => 'abu',
            $skor < 7 => 'hijau',
            $skor < 9 => 'kuning',
            default => 'merah',
        };
    }

    /**
     * Urutan gaya belajar dominan (tertinggi -> terendah). PRD 3.2.
     *
     * @param  array<int, int|float>  $jawaban
     * @return array<int, array{key:string, label:string, arti:string, skor:float}>
     */
    public function hitungGayaBelajar(array $jawaban): array
    {
        $hasil = [];

        foreach (self::GAYA_BELAJAR as $key => $cfg) {
            $nilai = array_map(fn ($s) => (float) ($jawaban[$s] ?? 0), $cfg['soal']);
            $hasil[] = [
                'key' => $key,
                'label' => $cfg['label'],
                'arti' => $cfg['arti'],
                'skor' => round(array_sum($nilai) / count($nilai), 2),
            ];
        }

        usort($hasil, fn ($a, $b) => $b['skor'] <=> $a['skor']);

        return $hasil;
    }

    /**
     * Urutan bahasa hati dominan (tertinggi -> terendah). PRD 3.3.
     *
     * @param  array<int, float>  $skorKarakter  [karakter_id => skor]
     * @return array<int, array{key:string, label:string, skor:float}>
     */
    public function hitungBahasaHati(array $skorKarakter): array
    {
        // Petakan kode -> id agar bisa mengambil skor per karakter.
        $idPerKode = Karakter::pluck('id', 'kode')->all();

        $hasil = [];

        foreach (self::BAHASA_HATI as $key => $cfg) {
            $nilai = [];

            foreach ($cfg['kode'] as $kode) {
                $id = $idPerKode[$kode] ?? null;
                if ($id !== null && isset($skorKarakter[$id])) {
                    $nilai[] = (float) $skorKarakter[$id];
                }
            }

            $hasil[] = [
                'key' => $key,
                'label' => $cfg['label'],
                'skor' => count($nilai) ? round(array_sum($nilai) / count($nilai), 2) : 0.0,
            ];
        }

        usort($hasil, fn ($a, $b) => $b['skor'] <=> $a['skor']);

        return $hasil;
    }

    /**
     * Hitung 40 skor karakter sebuah observasi & simpan ke hasil_observasi.
     * Idempotent: hasil lama dihapus sebelum disimpan ulang.
     *
     * @return Collection<int, HasilObservasi>
     */
    public function prosesHasil(Observasi $observasi): Collection
    {
        $jawaban = $observasi->jawaban()->pluck('nilai', 'nomor_soal')->all();
        $skorKarakter = $this->hitungSkorKarakter($jawaban);

        $observasi->hasilObservasi()->delete();

        $tersimpan = collect();

        foreach ($skorKarakter as $karakterId => $skor) {
            $tersimpan->push(HasilObservasi::create([
                'observasi_id' => $observasi->id,
                'karakter_id' => $karakterId,
                'skor' => $skor,
                'kategori' => $this->tentukanWarna($skor),
            ]));
        }

        return $tersimpan;
    }

    /**
     * Top N karakter (kekuatan) dari hasil tersimpan. PRD 3.5 C.
     *
     * @return Collection<int, HasilObservasi>
     */
    public function topKarakter(Observasi $observasi, int $n = 6): Collection
    {
        return $observasi->hasilObservasi()
            ->with('karakter')
            ->orderByDesc('skor')
            ->orderBy('karakter_id')
            ->limit($n)
            ->get();
    }

    /**
     * Bottom N karakter (kelemahan) dari hasil tersimpan. PRD 3.5 D.
     *
     * @return Collection<int, HasilObservasi>
     */
    public function bottomKarakter(Observasi $observasi, int $n = 6): Collection
    {
        return $observasi->hasilObservasi()
            ->with('karakter')
            ->orderBy('skor')
            ->orderBy('karakter_id')
            ->limit($n)
            ->get();
    }

    /**
     * Rakit seluruh data hasil untuk view/PDF/email (dipakai bersama).
     *
     * @return array<string, mixed>
     */
    public function rakitData(Observasi $observasi): array
    {
        $observasi->loadMissing(['peserta.user', 'hasilObservasi.karakter']);

        $jawaban = $observasi->jawaban()->pluck('nilai', 'nomor_soal')->all();
        $skorKarakter = $observasi->hasilObservasi
            ->pluck('skor', 'karakter_id')->map(fn ($s) => (float) $s)->all();

        $grupDimensi = $observasi->hasilObservasi
            ->sortBy('karakter_id')
            ->groupBy(fn ($h) => KategoriWarna::labelDimensi($h->karakter?->dimensi));

        return [
            'observasi' => $observasi,
            'section' => $this->hitungSection($jawaban),
            'keislaman' => $this->persentaseKeislaman($jawaban),
            'gaya_belajar' => $this->hitungGayaBelajar($jawaban),
            'bahasa_hati' => $this->hitungBahasaHati($skorKarakter),
            'top' => $this->topKarakter($observasi),
            'bottom' => $this->bottomKarakter($observasi),
            'grupDimensi' => $grupDimensi,
            'kesimpulan' => $this->buatKesimpulan($observasi),
        ];
    }

    /**
     * Rata-rata skor per dimensi (bakat/akal/hati), urut tertinggi.
     *
     * @return array<int, array{dimensi:string, label:string, rata:float}>
     */
    public function dimensiDominan(Observasi $observasi): array
    {
        $observasi->loadMissing('hasilObservasi.karakter');
        $grup = $observasi->hasilObservasi->groupBy(fn ($h) => $h->karakter?->dimensi);

        $label = ['bakat' => 'Bakat (Karsa)', 'akal' => 'Akal (Cipta)', 'hati' => 'Hati (Rasa)'];
        $hasil = [];

        foreach (['bakat', 'akal', 'hati'] as $d) {
            $items = $grup->get($d);
            if ($items && $items->count()) {
                $hasil[] = [
                    'dimensi' => $d,
                    'label' => $label[$d],
                    'rata' => round($items->avg(fn ($h) => (float) $h->skor), 2),
                ];
            }
        }

        usort($hasil, fn ($a, $b) => $b['rata'] <=> $a['rata']);

        return $hasil;
    }

    /**
     * Kesimpulan naratif karakter peserta dari hasil tes.
     *
     * @return array<string, mixed>
     */
    public function buatKesimpulan(Observasi $observasi): array
    {
        $observasi->loadMissing(['peserta.user', 'hasilObservasi.karakter']);

        // Nama anak yang dites, bukan pemilik akun yang mengisi tes.
        $nama = $observasi->peserta?->nama ?? 'Peserta';
        $jawaban = $observasi->jawaban()->pluck('nilai', 'nomor_soal')->all();
        $skorKarakter = $observasi->hasilObservasi
            ->pluck('skor', 'karakter_id')->map(fn ($s) => (float) $s)->all();

        $top = $this->topKarakter($observasi, 3);
        $bottom = $this->bottomKarakter($observasi, 2);
        $gaya = $this->hitungGayaBelajar($jawaban);
        $hati = $this->hitungBahasaHati($skorKarakter);
        $dim = $this->dimensiDominan($observasi);

        $topNames = $top->map(fn ($h) => $h->karakter?->terjemahan)->filter()->implode(', ');
        $bottomNames = $bottom->map(fn ($h) => $h->karakter?->terjemahan)->filter()->implode(' dan ');
        $dimLabel = $dim[0]['label'] ?? '-';
        $gayaLabel = isset($gaya[0]) ? $gaya[0]['label'].' ('.$gaya[0]['arti'].')' : '-';
        $hatiLabel = $hati[0]['label'] ?? '-';

        $narasi = "Berdasarkan hasil tes, {$nama} menunjukkan kecenderungan karakter yang paling kuat "
            ."pada dimensi Kinerja {$dimLabel}. Kekuatan utama yang menonjol adalah {$topNames}. "
            ."Dalam belajar, {$nama} paling nyaman dengan gaya {$gayaLabel}, sehingga akan lebih optimal "
            ."bila belajar dengan cara tersebut. Untuk menyentuh hatinya, pendekatan {$hatiLabel} paling efektif. "
            ."Sementara itu, {$bottomNames} merupakan bagian yang masih perlu dikembangkan.";

        return [
            'nama' => $nama,
            'narasi' => $narasi,
            'dimensi_dominan' => $dim,
            'gaya_dominan' => $gaya[0] ?? null,
            'hati_dominan' => $hati[0] ?? null,
            'kekuatan_utama' => $topNames,
            'perlu_dikembangkan' => $bottomNames,
        ];
    }

    /**
     * Deskripsi kepribadian per dimensi dominan (5 aspek).
     */
    private const ANALISIS = [
        'bakat' => [
            'berpikir' => 'Cenderung berpikir praktis dan berorientasi pada tindakan. Anda lebih suka langsung mencoba dan belajar dari pengalaman daripada berteori panjang.',
            'bekerja' => 'Energik, gigih, dan berorientasi pada hasil. Anda bersemangat memulai hal baru dan pantang menyerah menyelesaikan target.',
            'berinteraksi' => 'Aktif dan berani mengambil inisiatif dalam kelompok. Anda tidak ragu memimpin dan mendorong orang lain untuk bergerak.',
            'keputusan' => 'Cepat dan tegas. Anda berani mengambil risiko yang terukur dan tidak suka menunda-nunda keputusan.',
            'potensi' => 'Berpotensi menjadi penggerak, pemimpin, atau wirausahawan yang mampu mewujudkan ide menjadi kenyataan.',
        ],
        'akal' => [
            'berpikir' => 'Analitis, kritis, dan mendalam. Anda senang memahami sesuatu hingga ke akar persoalan dan menemukan pola di baliknya.',
            'bekerja' => 'Teliti, sistematis, dan mengutamakan kualitas. Anda bekerja paling baik saat diberi ruang untuk berpikir dan menganalisis.',
            'berinteraksi' => 'Reflektif dan selektif. Anda lebih nyaman dalam diskusi yang bermakna daripada obrolan ringan.',
            'keputusan' => 'Berdasarkan data dan pertimbangan yang matang. Anda menimbang berbagai kemungkinan sebelum memutuskan.',
            'potensi' => 'Berpotensi menjadi ahli, peneliti, analis, atau perancang yang mengandalkan ketajaman berpikir.',
        ],
        'hati' => [
            'berpikir' => 'Empatik dan mempertimbangkan perasaan. Anda memandang persoalan dari sisi kemanusiaan dan nilai-nilai.',
            'bekerja' => 'Sabar, tekun, dan penuh perhatian pada orang lain. Anda bekerja optimal dalam suasana yang harmonis.',
            'berinteraksi' => 'Hangat, ramah, dan mudah membangun kedekatan. Anda peka terhadap kebutuhan orang di sekitar Anda.',
            'keputusan' => 'Mempertimbangkan dampak pada orang lain dan menjaga hubungan. Anda mengutamakan keharmonisan.',
            'potensi' => 'Berpotensi menjadi pendidik, pembimbing, atau pelayan masyarakat yang mampu menyentuh hati orang lain.',
        ],
    ];

    /**
     * Kode hasil tes yang ringkas.
     */
    public function kodeHasil(Observasi $observasi): string
    {
        return 'TB40-'.str_pad((string) $observasi->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Tipe kepribadian dominan (Introvert / Ekstrovert) dari bobot skor karakter.
     */
    public function tipeKepribadian(Observasi $observasi): string
    {
        $observasi->loadMissing('hasilObservasi.karakter');
        $skor = ['introvert' => 0.0, 'extrovert' => 0.0];

        foreach ($observasi->hasilObservasi as $h) {
            $t = $h->karakter?->tipe;
            if (isset($skor[$t])) {
                $skor[$t] += (float) $h->skor;
            }
        }

        return $skor['introvert'] >= $skor['extrovert'] ? 'Introvert' : 'Ekstrovert';
    }

    /**
     * Gaya belajar dipetakan ke Visual / Auditori / Kinestetik + tips, urut dominan.
     *
     * @param  array<int, int|float>  $jawaban
     * @return array<int, array<string, mixed>>
     */
    public function gayaBelajarLengkap(array $jawaban): array
    {
        $meta = [
            'as_samu' => ['tipe' => 'Auditori', 'arti' => 'belajar dengan mendengar', 'tips' => [
                'Dengarkan penjelasan guru, audio, atau rekaman materi',
                'Belajar sambil membaca dengan suara nyaring',
                'Perbanyak diskusi dan tanya-jawab',
            ], 'lingkungan' => 'Suasananya cukup hening, tidak berisik, dan tidak ada suara gaduh di sekitar.'],
            'al_fuad' => ['tipe' => 'Kinestetik', 'arti' => 'belajar dengan bergerak & praktik', 'tips' => [
                'Praktik atau peragakan langsung materinya',
                'Gunakan alat/benda nyata sebagai media belajar',
                'Belajar sambil bergerak, hindari terlalu lama diam',
            ], 'lingkungan' => 'Di alam terbuka, lapangan, bengkel, atau tempat lain yang memungkinkan banyak gerakan.'],
            'al_bashar' => ['tipe' => 'Visual', 'arti' => 'belajar dengan melihat', 'tips' => [
                'Gunakan gambar, diagram, atau mind map',
                'Tonton video pembelajaran',
                'Buat catatan berwarna dan sorot poin penting',
            ], 'lingkungan' => 'Penerangan cukup dan pemandangan di sekitar tempat belajar tampak menarik.'],
        ];

        $out = [];
        foreach ($this->hitungGayaBelajar($jawaban) as $g) {
            $m = $meta[$g['key']] ?? null;
            $out[] = [
                'tipe' => $m['tipe'] ?? $g['label'],
                'label_arab' => $g['label'],
                'arti' => $m['arti'] ?? $g['arti'],
                'skor' => $g['skor'],
                'persen' => (int) round($g['skor'] * 10),
                'tips' => $m['tips'] ?? [],
                'lingkungan' => $m['lingkungan'] ?? '',
            ];
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    public function analisisKepribadian(Observasi $observasi): array
    {
        $dim = $this->dimensiDominan($observasi);
        $d = $dim[0]['dimensi'] ?? 'akal';

        return self::ANALISIS[$d] ?? self::ANALISIS['akal'];
    }

    /**
     * Rekomendasi jurusan dari kolom jurusan karakter terkuat.
     *
     * @return array<int, array{nama:string, kecocokan:int, alasan:string}>
     */
    public function rekomendasiJurusan(Observasi $observasi): array
    {
        return $this->rekomendasiDari($this->topKarakter($observasi, 6), 'jurusan');
    }

    /**
     * Rekomendasi profesi dari kolom profesi karakter terkuat.
     *
     * @return array<int, array{nama:string, kecocokan:int, alasan:string}>
     */
    public function rekomendasiProfesi(Observasi $observasi): array
    {
        return $this->rekomendasiDari($this->topKarakter($observasi, 6), 'profesi');
    }

    /**
     * @param  Collection<int, HasilObservasi>  $top
     * @return array<int, array{nama:string, kecocokan:int, alasan:string}>
     */
    private function rekomendasiDari(Collection $top, string $field): array
    {
        $seen = [];
        $out = [];

        foreach ($top as $h) {
            $raw = $h->karakter?->{$field};
            if (! $raw) {
                continue;
            }
            $nama = trim(explode(',', $raw)[0]);
            if ($nama === '') {
                continue;
            }
            $key = mb_strtolower($nama);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $out[] = [
                'nama' => $nama,
                'kecocokan' => min(100, (int) round((float) $h->skor * 10)),
                'alasan' => 'Sesuai bakat '.$h->karakter?->terjemahan.' ('.$h->karakter?->nama_karakter.') yang menonjol.',
            ];
            if (count($out) >= 6) {
                break;
            }
        }

        return $out;
    }

    /**
     * @return array<string, mixed>
     */
    public function saranPengembangan(Observasi $observasi): array
    {
        $bottom = $this->bottomKarakter($observasi, 4);

        return [
            'soft_skill' => $bottom->map(fn ($h) => $h->karakter?->terjemahan)->filter()->values()->all(),
            'aktivitas' => [
                'Ikut kegiatan yang melatih kelemahan (organisasi, klub, atau proyek kelompok)',
                'Minta umpan balik dari guru/teman, lalu perbaiki secara bertahap',
                'Tetapkan target kecil mingguan dan lakukan evaluasi rutin',
            ],
            'cara' => $bottom->map(fn ($h) => [
                'karakter' => $h->karakter?->terjemahan,
                'saran' => $h->karakter?->cara_memperbaiki_melalaikan,
            ])->filter(fn ($x) => $x['saran'])->values()->all(),
        ];
    }

    /**
     * Rakit seluruh data untuk halaman laporan hasil (web putih/biru).
     *
     * @return array<string, mixed>
     */
    public function laporan(Observasi $observasi): array
    {
        $data = $this->rakitData($observasi);
        $jawaban = $observasi->jawaban()->pluck('nilai', 'nomor_soal')->all();
        $gayaLengkap = $this->gayaBelajarLengkap($jawaban);
        $dim = $data['kesimpulan']['dimensi_dominan'];

        $ringkasan = [
            'bakat_utama' => $dim[0]['label'] ?? '-',
            'tipe_kepribadian' => $this->tipeKepribadian($observasi),
            'gaya_belajar' => $gayaLengkap[0]['tipe'] ?? '-',
            'karakter_dominan' => $data['top']->first()?->karakter?->terjemahan ?? '-',
        ];

        // Pemicu motivasi mengikuti bahasa hati yang paling dominan.
        $hatiDominan = $data['bahasa_hati'][0] ?? null;

        return array_merge($data, [
            'kode' => $this->kodeHasil($observasi),
            'ringkasan' => $ringkasan,
            'gaya_lengkap' => $gayaLengkap,
            'pemicu_motivasi' => $hatiDominan
                ? (self::PEMICU_BAHASA_HATI[$hatiDominan['key']] ?? [])
                : [],
            'analisis' => $this->analisisKepribadian($observasi),
            'rek_jurusan' => $this->rekomendasiJurusan($observasi),
            'rek_profesi' => $this->rekomendasiProfesi($observasi),
            'saran' => $this->saranPengembangan($observasi),
        ]);
    }
}
