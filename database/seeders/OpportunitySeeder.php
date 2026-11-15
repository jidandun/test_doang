<?php

namespace Database\Seeders;

use App\FeeType;
use App\Models\Opportunity;
use App\OpportunityType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OpportunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();

        $items = [
            [
                'judul' => 'GEMASTIK — Divisi Desain Pengalaman Pengguna (UX Design)',
                'jenis' => OpportunityType::Lomba->value,
                'penyelenggara' => 'Balai Pengembangan Talenta Indonesia (Kemendikbudristek)',
                'kategori' => 'UI/UX & Desain',
                'biaya_tipe' => FeeType::Gratis->value,
                'nominal_biaya' => null,
                'min_semester' => 3,
                'min_ipk' => 3.00,
                'deadline' => $today->copy()->addDays(1),
                'url_pendaftaran' => 'https://gemastik.kemdikbud.go.id/',
                'poster_path' => 'images/posters/lomba-design.svg',
                'deskripsi' => "Pagelaran Mahasiswa Nasional Bidang Teknologi Informasi dan Komunikasi (GEMASTIK) Divisi Desain Pengalaman Pengguna mengajak mahasiswa merancang solusi produk digital yang berpusat pada pengguna.\n\nPeserta bekerja dalam tim beranggotakan maksimal 3 mahasiswa dari perguruan tinggi yang sama untuk menyusun riset pengguna, wireframe, prototipe interaktif, serta evaluasi usabilitas.",
                'syarat' => "Mahasiswa aktif jenjang D3/D4/S1 di Indonesia (terdaftar di PDDikti).\nMinimal telah menempuh semester 3 dan memiliki IPK minimal 3.00.\nSatu tim terdiri dari 2–3 mahasiswa dari perguruan tinggi yang sama.\nMelampirkan KTM aktif, surat pengantar kemahasiswaan, dan proposal konsep UX.",
                'hadiah_benefit' => "Medali Emas/Perak/Perunggu Nasional\nUang pembinaan & sertifikat prestasi resmi BPTI\nRekognisi SKPI / konversi SKS sesuai kebijakan kampus",
            ],
            [
                'judul' => 'Beasiswa Bank Indonesia (Generasi Baru Indonesia / GenBI)',
                'jenis' => OpportunityType::Beasiswa->value,
                'penyelenggara' => 'Bank Indonesia',
                'kategori' => 'Pendidikan S1/D4',
                'biaya_tipe' => FeeType::Gratis->value,
                'nominal_biaya' => null,
                'min_semester' => 3,
                'min_ipk' => 3.25,
                'deadline' => $today->copy()->addDays(2),
                'url_pendaftaran' => 'https://www.bi.go.id/',
                'poster_path' => 'images/posters/beasiswa-nasional.svg',
                'deskripsi' => "Program Beasiswa Bank Indonesia ditujukan bagi mahasiswa S1/D4 berprestasi di perguruan tinggi mitra Bank Indonesia yang aktif berkontribusi dalam kegiatan sosial dan kepemimpinan melalui komunitas GenBI.\n\nSelain bantuan biaya pendidikan bulanan, penerima beasiswa akan mendapatkan pembinaan soft skill, kepemimpinan, serta jejaring nasional.",
                'syarat' => "Mahasiswa aktif S1/D4 minimal semester 3 dan maksimal semester 6.\nIndeks Prestasi Kumulatif (IPK) minimal 3.25 dari skala 4.00.\nTidak sedang menerima beasiswa dari instansi lain.\nAktif dalam organisasi kemahasiswaan atau kegiatan sosial masyarakat.\nMembuat motivation letter dan melampirkan surat rekomendasi dosen.",
                'hadiah_benefit' => "Bantuan dana pendidikan Rp 1.000.000 – Rp 1.500.000 per bulan\nPelatihan kepemimpinan & seminar nasional Bank Indonesia\nAkses jejaring komunitas GenBI se-Indonesia",
            ],
            [
                'judul' => 'National Business Plan Competition — InnovateID',
                'jenis' => OpportunityType::Lomba->value,
                'penyelenggara' => 'Himpunan Mahasiswa Manajemen Bisnis',
                'kategori' => 'Bisnis & Kewirausahaan',
                'biaya_tipe' => FeeType::Berbayar->value,
                'nominal_biaya' => 75000,
                'min_semester' => 1,
                'min_ipk' => 0.00,
                'deadline' => $today->copy(),
                'url_pendaftaran' => 'https://example.org/innovateid-daftar',
                'poster_path' => 'images/posters/lomba-bisnis.svg',
                'deskripsi' => "Kompetisi rencana bisnis tingkat nasional untuk mendorong lahirnya wirausaha muda berbasis inovasi teknologi, ekonomi kreatif, dan keberlanjutan.\n\nTahap seleksi meliputi pengumpulan Business Model Canvas (BMC), proposal bisnis lengkap, serta pitching langsung di hadapan praktisi startup dan investor.",
                'syarat' => "Terbuka untuk seluruh mahasiswa aktif D3/D4/S1 semua semester (tanpa minimal IPK).\nTim beranggotakan 2–4 orang (diperbolehkan lintas jurusan dalam satu kampus).\nIde bisnis bersifat orisinal dan belum pernah memenangkan kompetisi sejenis.\nMembayar biaya registrasi tim sebesar Rp 75.000 pada gelombang reguler.",
                'hadiah_benefit' => "Total hadiah Rp 25.000.000 + trofi juara\nSesi mentoring bersama founder startup nasional\nE-sertifikat nasional untuk seluruh peserta",
            ],
            [
                'judul' => 'Beasiswa Unggulan Masyarakat Berprestasi Kemendikbudristek',
                'jenis' => OpportunityType::Beasiswa->value,
                'penyelenggara' => 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi',
                'kategori' => 'Pendidikan S1/D4',
                'biaya_tipe' => FeeType::Gratis->value,
                'nominal_biaya' => null,
                'min_semester' => 1,
                'min_ipk' => 3.25,
                'deadline' => $today->copy()->addDays(14),
                'url_pendaftaran' => 'https://beasiswaunggulan.kemdikbud.go.id/',
                'poster_path' => 'images/posters/beasiswa-nasional.svg',
                'deskripsi' => "Beasiswa Unggulan adalah pemberian biaya pendidikan oleh pemerintah Indonesia kepada putra-putri terbaik bangsa yang menempuh pendidikan tinggi di perguruan tinggi terakreditasi.\n\nProgram ini mencakup biaya UKT/SPP penuh, biaya hidup bulanan, dan tunjangan buku bagi mahasiswa baru maupun mahasiswa ongoing.",
                'syarat' => "Warga Negara Indonesia yang terdaftar sebagai mahasiswa aktif S1 (maksimal semester 3).\nMemiliki IPK minimal 3.25 bagi mahasiswa ongoing.\nMemiliki sertifikat prestasi akademik atau non-akademik tingkat nasional/internasional.\nMemiliki sertifikat kemampuan bahasa Indonesia (UKBI) dan esai kontribusi.",
                'hadiah_benefit' => "Pembebasan biaya kuliah (UKT/SPP) penuh\nTunjangan biaya hidup bulanan\nTunjangan pembelian buku akademik setiap semester",
            ],
            [
                'judul' => 'COMPFEST — Software Engineering & Competitive Programming',
                'jenis' => OpportunityType::Lomba->value,
                'penyelenggara' => 'Fakultas Ilmu Komputer Universitas Indonesia',
                'kategori' => 'Teknologi & IT',
                'biaya_tipe' => FeeType::Gratis->value,
                'nominal_biaya' => null,
                'min_semester' => 1,
                'min_ipk' => 2.75,
                'deadline' => $today->copy()->addDays(10),
                'url_pendaftaran' => 'https://compfest.id/',
                'poster_path' => 'images/posters/lomba-tech.svg',
                'deskripsi' => "COMPFEST merupakan acara IT tahunan terbesar yang diselenggarakan oleh mahasiswa untuk mengasah kemampuan algoritma, pemrograman kompetitif, dan rekayasa perangkat lunak.\n\nPeserta akan melalui babak penyisihan daring dan babak final luring dengan studi kasus industri nyata.",
                'syarat' => "Mahasiswa aktif D3/D4/S1 di seluruh Indonesia (terbuka mulai semester 1).\nMinimal IPK 2.75 (dibuktikan dengan transkrip nilai atau KHS terakhir bagi semester > 1).\nSatu tim terdiri dari maksimal 3 peserta.\nMenguasai bahasa pemrograman C++, Java, Python, atau Go.",
                'hadiah_benefit' => "Total hadiah Rp 30.000.000\nKesempatan fast-track wawancara magang di perusahaan teknologi mitra\nSertifikat finalis dan pemenang tingkat nasional",
            ],
            [
                'judul' => 'Lomba Karya Tulis Ilmiah Nasional (LKTIN) Energi Terbarukan',
                'jenis' => OpportunityType::Lomba->value,
                'penyelenggara' => 'Fakultas Teknik Universitas Brawijaya',
                'kategori' => 'Karya Tulis Ilmiah',
                'biaya_tipe' => FeeType::Berbayar->value,
                'nominal_biaya' => 50000,
                'min_semester' => 2,
                'min_ipk' => 2.50,
                'deadline' => $today->copy()->addDays(18),
                'url_pendaftaran' => 'https://example.org/lktin-energi',
                'poster_path' => 'images/posters/lomba-kti.svg',
                'deskripsi' => "Kompetisi penulisan gagasan ilmiah tingkat nasional yang berfokus pada inovasi energi bersih, efisiensi daya, dan teknologi ramah lingkungan.\n\nPengumpulan abstrak tahap awal tidak dipungut biaya; biaya registrasi Rp 50.000 berlaku bagi tim yang lolos ke tahap pengumpulan full paper.",
                'syarat' => "Mahasiswa aktif D3/D4/S1 minimal semester 2.\nIPK minimal 2.50.\nTim terdiri dari 2–3 orang dengan 1 dosen pembimbing.\nKarya tulis belum pernah dipublikasikan di jurnal manapun.",
                'hadiah_benefit' => "Uang pembinaan Juara 1, 2, 3, dan Best Presentation\nProsiding ber-ISBN bagi finalis terpilih\nSertifikat tingkat nasional",
            ],
            [
                'judul' => 'Beasiswa Riset Tugas Akhir & Skripsi Inovatif',
                'jenis' => OpportunityType::Beasiswa->value,
                'penyelenggara' => 'Lembaga Riset & Inovasi Nasional Kampus',
                'kategori' => 'Riset & Inovasi',
                'biaya_tipe' => FeeType::Gratis->value,
                'nominal_biaya' => null,
                'min_semester' => 5,
                'min_ipk' => 3.50,
                'deadline' => $today->copy()->addDays(25),
                'url_pendaftaran' => 'https://example.org/beasiswa-riset-skripsi',
                'poster_path' => 'images/posters/beasiswa-riset.svg',
                'deskripsi' => 'Program hibah dan beasiswa penelitian bagi mahasiswa tingkat akhir yang sedang atau akan menyusun tugas akhir/skripsi di bidang sains data, kecerdasan buatan, kesehatan masyarakat, atau rekayasa berkelanjutan.',
                'syarat' => "Mahasiswa aktif S1/D4 minimal semester 5.\nMemiliki IPK minimal 3.50.\nMemiliki proposal penelitian tugas akhir yang telah disetujui dosen pembimbing.\nBersedia mempublikasikan luaran riset pada seminar nasional atau jurnal terakreditasi.",
                'hadiah_benefit' => "Dana hibah riset hingga Rp 10.000.000 per mahasiswa\nBantuan biaya publikasi jurnal ilmiah\nPendampingan peneliti senior",
            ],
            [
                'judul' => 'UI/UX & Product Design Olympiad Mahasiswa Nasional',
                'jenis' => OpportunityType::Lomba->value,
                'penyelenggara' => 'Asosiasi Desain Produk Digital Nusantara',
                'kategori' => 'UI/UX & Desain',
                'biaya_tipe' => FeeType::Berbayar->value,
                'nominal_biaya' => 100000,
                'min_semester' => 1,
                'min_ipk' => 0.00,
                'deadline' => $today->copy()->addDays(7),
                'url_pendaftaran' => 'https://example.org/ux-olympiad',
                'poster_path' => 'images/posters/lomba-design.svg',
                'deskripsi' => "Olimpiade desain produk digital yang menantang mahasiswa untuk merancang aplikasi web atau mobile bertema aksesibilitas layanan publik dan pendidikan.\n\nPeserta akan mempresentasikan case study, design system, dan prototipe Figma di babak final.",
                'syarat' => "Mahasiswa aktif jenjang D3/D4/S1 semua semester dan semua jurusan.\nTidak ada batas minimal IPK.\nTim terdiri dari 1–3 orang.\nMembayar biaya pendaftaran sebesar Rp 100.000 per tim.",
                'hadiah_benefit' => "Hadiah uang tunai total Rp 18.000.000\nPortofolio review langsung oleh Lead Product Designer\nLisensi tools desain dan sertifikat resmi",
            ],
            [
                'judul' => 'Beasiswa Pelatihan Talenta Digital & Cloud Computing (Batch I)',
                'jenis' => OpportunityType::Beasiswa->value,
                'penyelenggara' => 'Kementerian Komunikasi dan Digital',
                'kategori' => 'Teknologi & IT',
                'biaya_tipe' => FeeType::Gratis->value,
                'nominal_biaya' => null,
                'min_semester' => 3,
                'min_ipk' => 3.00,
                'deadline' => $today->copy()->subDays(4),
                'url_pendaftaran' => 'https://digitalent.komdigi.go.id/',
                'poster_path' => 'images/posters/lomba-tech.svg',
                'deskripsi' => 'Program beasiswa pelatihan intensif dan sertifikasi global di bidang Cloud Computing, DevOps, dan Keamanan Siber bagi mahasiswa tingkat sarjana dan diploma.',
                'syarat' => "Mahasiswa aktif minimal semester 3 jurusan rumpun informatika atau teknik.\nMemiliki IPK minimal 3.00.\nLulus tes substansi dasar jaringan dan pemrograman.\nBerkomitmen menyelesaikan seluruh modul hingga ujian sertifikasi.",
                'hadiah_benefit' => "Voucher sertifikasi industri global gratis\nPelatihan intensif selama 8 minggu\nAkses bursa kerja talenta digital",
            ],
        ];

        foreach ($items as $item) {
            $slug = Str::slug($item['judul']);
            Opportunity::query()->updateOrCreate(
                ['slug' => $slug],
                array_merge($item, ['slug' => $slug])
            );
        }
    }
}
