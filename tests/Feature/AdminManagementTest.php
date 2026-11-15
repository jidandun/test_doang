<?php

namespace Tests\Feature;

use App\FeeType;
use App\Models\Opportunity;
use App\Models\User;
use App\OpportunityType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_and_non_admin_users_cannot_access_admin_panel(): void
    {
        $this->get(route('admin.opportunities.index'))
            ->assertRedirect(route('admin.login'));

        $regularUser = User::factory()->create(['is_admin' => false]);

        $this->actingAs($regularUser)
            ->get(route('admin.opportunities.index'))
            ->assertForbidden();
    }

    public function test_admin_can_login_and_logout(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@kampus.ac.id',
            'password' => 'rahasia123',
        ]);

        $this->post(route('admin.login.store'), [
            'email' => 'admin@kampus.ac.id',
            'password' => 'salah',
        ])->assertSessionHasErrors('email');

        $this->post(route('admin.login.store'), [
            'email' => 'admin@kampus.ac.id',
            'password' => 'rahasia123',
        ])->assertRedirect(route('admin.opportunities.index'));

        $this->assertAuthenticatedAs($admin);

        $this->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_admin_can_create_update_and_delete_opportunity_with_poster_upload(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $poster = UploadedFile::fake()->create('poster-lomba.png', 120, 'image/png');

        $storeResponse = $this->actingAs($admin)->post(route('admin.opportunities.store'), [
            'judul' => 'Hackathon Kampus Merdeka 2026',
            'jenis' => OpportunityType::Lomba->value,
            'penyelenggara' => 'Direktorat Kemahasiswaan',
            'kategori' => 'Teknologi & IT',
            'biaya_tipe' => FeeType::Berbayar->value,
            'nominal_biaya' => 50000,
            'min_semester' => 2,
            'min_ipk' => 2.75,
            'deadline' => now()->addDays(5)->toDateString(),
            'url_pendaftaran' => 'https://example.org/hackathon-daftar',
            'poster' => $poster,
            'deskripsi' => 'Kompetisi pembuatan aplikasi web dalam 48 jam.',
            'syarat' => 'Mahasiswa aktif minimal semester 2.',
            'hadiah_benefit' => 'Total hadiah Rp 15.000.000',
        ]);

        $storeResponse->assertRedirect(route('admin.opportunities.index'));

        $opportunity = Opportunity::query()->where('judul', 'Hackathon Kampus Merdeka 2026')->first();
        $this->assertNotNull($opportunity);
        $this->assertNotNull($opportunity->poster_path);
        Storage::disk('public')->assertExists($opportunity->poster_path);

        $oldPosterPath = $opportunity->poster_path;
        $newPoster = UploadedFile::fake()->create('poster-baru.jpg', 140, 'image/jpeg');

        $updateResponse = $this->actingAs($admin)->put(route('admin.opportunities.update', $opportunity), [
            'judul' => 'Hackathon Kampus Merdeka 2026 Edisi Revisi',
            'jenis' => OpportunityType::Lomba->value,
            'penyelenggara' => 'Direktorat Kemahasiswaan',
            'kategori' => 'Teknologi & IT',
            'biaya_tipe' => FeeType::Gratis->value,
            'nominal_biaya' => 50000,
            'min_semester' => 1,
            'min_ipk' => 0,
            'deadline' => now()->addDays(7)->toDateString(),
            'url_pendaftaran' => 'https://example.org/hackathon-daftar-v2',
            'poster' => $newPoster,
            'deskripsi' => 'Deskripsi diperbarui.',
            'syarat' => 'Terbuka untuk semua mahasiswa.',
            'hadiah_benefit' => 'Uang pembinaan dan sertifikat.',
        ]);

        $updateResponse->assertRedirect(route('admin.opportunities.index'));
        $opportunity->refresh();

        $this->assertSame('Hackathon Kampus Merdeka 2026 Edisi Revisi', $opportunity->judul);
        $this->assertSame(FeeType::Gratis, $opportunity->biaya_tipe);
        $this->assertNull($opportunity->nominal_biaya);
        Storage::disk('public')->assertMissing($oldPosterPath);
        Storage::disk('public')->assertExists($opportunity->poster_path);

        $currentPosterPath = $opportunity->poster_path;

        $this->actingAs($admin)
            ->delete(route('admin.opportunities.destroy', $opportunity))
            ->assertRedirect(route('admin.opportunities.index'));

        $this->assertDatabaseMissing('opportunities', ['id' => $opportunity->id]);
        Storage::disk('public')->assertMissing($currentPosterPath);
    }
}
