<?php

namespace Tests\Feature;

use App\Models\Opportunity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_directory_filters_by_jenis_semester_ipk_and_biaya(): void
    {
        $matching = Opportunity::factory()->lomba()->gratis()->create([
            'judul' => 'Lomba UI/UX Nasional Gratis',
            'min_semester' => 3,
            'min_ipk' => 3.00,
        ]);

        $wrongJenis = Opportunity::factory()->beasiswa()->gratis()->create([
            'judul' => 'Beasiswa Prestasi Terbuka',
            'min_semester' => 1,
            'min_ipk' => 2.50,
        ]);

        $wrongBiaya = Opportunity::factory()->lomba()->berbayar(75000)->create([
            'judul' => 'Lomba Bisnis Berbayar',
            'min_semester' => 1,
            'min_ipk' => 2.50,
        ]);

        $tooHighSemester = Opportunity::factory()->lomba()->gratis()->create([
            'judul' => 'Lomba Riset Khusus Tingkat Akhir',
            'min_semester' => 6,
            'min_ipk' => 2.50,
        ]);

        $tooHighIpk = Opportunity::factory()->lomba()->gratis()->create([
            'judul' => 'Lomba Khusus IPK Tinggi',
            'min_semester' => 2,
            'min_ipk' => 3.75,
        ]);

        $response = $this->get(route('home', [
            'jenis' => 'lomba',
            'biaya' => 'gratis',
            'min_semester' => 4,
            'min_ipk' => 3.20,
        ]));

        $response->assertOk();
        $response->assertSee($matching->judul);
        $response->assertDontSee($wrongJenis->judul);
        $response->assertDontSee($wrongBiaya->judul);
        $response->assertDontSee($tooHighSemester->judul);
        $response->assertDontSee($tooHighIpk->judul);
    }

    public function test_smart_search_matches_across_title_organizer_and_requirements(): void
    {
        $target = Opportunity::factory()->beasiswa()->create([
            'judul' => 'Beasiswa Talenta Nusantara',
            'penyelenggara' => 'Bank Mandiri',
            'syarat' => 'Memiliki esai kontribusi energi terbarukan.',
        ]);

        $other = Opportunity::factory()->lomba()->create([
            'judul' => 'Hackathon Kota Pintar',
            'penyelenggara' => 'Komunitas Kode',
            'syarat' => 'Membuat aplikasi mobile.',
        ]);

        $response = $this->get(route('home', ['q' => 'mandiri terbarukan']));

        $response->assertOk();
        $response->assertSee($target->judul);
        $response->assertDontSee($other->judul);
    }

    public function test_deadline_badges_show_segera_berakhir_dibuka_and_ditutup(): void
    {
        Opportunity::factory()->closingSoon(1)->create([
            'judul' => 'Program Segera Berakhir Satu Hari',
        ]);

        Opportunity::factory()->create([
            'judul' => 'Program Masih Lama Dibuka',
            'deadline' => now()->addDays(10)->toDateString(),
        ]);

        Opportunity::factory()->closed(2)->create([
            'judul' => 'Program Sudah Lewat Tenggat',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Segera Berakhir');
        $response->assertSee('1 hari lagi');
        $response->assertSee('Dibuka');
        $response->assertSee('Ditutup');
    }

    public function test_detail_page_displays_requirements_copy_buttons_and_registration_redirect(): void
    {
        $opportunity = Opportunity::factory()->lomba()->berbayar(50000)->create([
            'judul' => 'Kompetisi Sains Data Mahasiswa',
            'url_pendaftaran' => 'https://example.org/daftar-sains-data',
            'syarat' => "Mahasiswa aktif S1.\nIPK minimal 3.00.",
        ]);

        $detail = $this->get(route('opportunities.show', $opportunity));
        $detail->assertOk();
        $detail->assertSee('Kompetisi Sains Data Mahasiswa');
        $detail->assertSee('Mahasiswa aktif S1.');
        $detail->assertSee('Salin Tautan Halaman');
        $detail->assertSee('Salin Format Share Grup');
        $detail->assertSee('Daftar di Situs Resmi');

        $redirect = $this->get(route('opportunities.redirect', $opportunity));
        $redirect->assertRedirect('https://example.org/daftar-sains-data');
    }
}
