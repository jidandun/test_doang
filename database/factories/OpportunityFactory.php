<?php

namespace Database\Factories;

use App\FeeType;
use App\Models\Opportunity;
use App\OpportunityType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Opportunity>
 */
class OpportunityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $judul = fake()->randomElement([
            'Kompetisi Nasional UI/UX Design Challenge',
            'Lomba Karya Tulis Ilmiah Mahasiswa Nasional',
            'Hackathon Inovasi Digital Kampus',
            'Beasiswa Prestasi Akademik Nusantara',
            'Program Beasiswa Riset & Tugas Akhir',
            'Business Plan Competition Mahasiswa Indonesia',
        ]).' '.fake()->unique()->numberBetween(100, 999);

        $jenis = fake()->randomElement(OpportunityType::cases());
        $biaya = $jenis === OpportunityType::Beasiswa
            ? FeeType::Gratis
            : fake()->randomElement(FeeType::cases());

        return [
            'judul' => $judul,
            'slug' => Str::slug($judul),
            'jenis' => $jenis,
            'penyelenggara' => fake()->randomElement([
                'Kemendikbudristek',
                'Institut Teknologi Sepuluh Nopember',
                'Universitas Gadjah Mada',
                'Bank Indonesia',
                'Kementerian Komunikasi dan Digital',
                'Asosiasi Mahasiswa Informatika Indonesia',
            ]),
            'kategori' => fake()->randomElement([
                'Teknologi & IT',
                'Desain & Kreatif',
                'Karya Tulis Ilmiah',
                'Bisnis & Kewirausahaan',
                'Pendidikan S1/D4',
            ]),
            'biaya_tipe' => $biaya,
            'nominal_biaya' => $biaya === FeeType::Berbayar ? fake()->randomElement([35000, 50000, 75000, 100000]) : null,
            'min_semester' => fake()->randomElement([1, 2, 3, 4, 5]),
            'min_ipk' => fake()->randomElement([0.00, 2.75, 3.00, 3.25, 3.50]),
            'deadline' => Carbon::today()->addDays(fake()->numberBetween(1, 30)),
            'url_pendaftaran' => 'https://pendaftaran.contoh-penyelenggara.ac.id/daftar',
            'poster_path' => null,
            'deskripsi' => fake()->paragraphs(3, true),
            'syarat' => "Mahasiswa aktif jenjang D3/D4/S1 di perguruan tinggi terakreditasi.\nMengisi formulir pendaftaran melalui tautan resmi penyelenggara.\nMelampirkan KTM dan transkrip nilai terbaru.",
            'hadiah_benefit' => 'Sertifikat Nasional, Uang Pembinaan / Bantuan Dana Pendidikan.',
        ];
    }

    public function lomba(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis' => OpportunityType::Lomba,
        ]);
    }

    public function beasiswa(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis' => OpportunityType::Beasiswa,
            'biaya_tipe' => FeeType::Gratis,
            'nominal_biaya' => null,
        ]);
    }

    public function gratis(): static
    {
        return $this->state(fn (array $attributes) => [
            'biaya_tipe' => FeeType::Gratis,
            'nominal_biaya' => null,
        ]);
    }

    public function berbayar(int $nominal = 50000): static
    {
        return $this->state(fn (array $attributes) => [
            'biaya_tipe' => FeeType::Berbayar,
            'nominal_biaya' => $nominal,
        ]);
    }

    public function closingSoon(int $days = 2): static
    {
        return $this->state(fn (array $attributes) => [
            'deadline' => Carbon::today()->addDays($days),
        ]);
    }

    public function closed(int $daysAgo = 3): static
    {
        return $this->state(fn (array $attributes) => [
            'deadline' => Carbon::today()->subDays($daysAgo),
        ]);
    }
}
