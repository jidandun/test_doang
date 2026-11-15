<?php

namespace App\Http\Controllers\Admin;

use App\FeeType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOpportunityRequest;
use App\Http\Requests\UpdateOpportunityRequest;
use App\Models\Opportunity;
use App\OpportunityType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OpportunityController extends Controller
{
    /**
     * Display a listing of opportunities in the admin dashboard.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'jenis', 'biaya', 'status']);
        $today = Carbon::today()->toDateString();
        $soonLimit = Carbon::today()->addDays(2)->toDateString();

        $opportunities = Opportunity::query()
            ->search($filters['q'] ?? null)
            ->filter($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'total' => Opportunity::query()->count(),
            'lomba' => Opportunity::query()->where('jenis', OpportunityType::Lomba->value)->count(),
            'beasiswa' => Opportunity::query()->where('jenis', OpportunityType::Beasiswa->value)->count(),
            'segera_berakhir' => Opportunity::query()
                ->whereDate('deadline', '>=', $today)
                ->whereDate('deadline', '<=', $soonLimit)
                ->count(),
            'ditutup' => Opportunity::query()->whereDate('deadline', '<', $today)->count(),
        ];

        return view('admin.opportunities.index', [
            'opportunities' => $opportunities,
            'filters' => $filters,
            'summary' => $summary,
        ]);
    }

    /**
     * Show the form for creating a new opportunity.
     */
    public function create(): View
    {
        return view('admin.opportunities.create');
    }

    /**
     * Store a newly created opportunity in storage.
     */
    public function store(StoreOpportunityRequest $request): RedirectResponse
    {
        $data = $this->prepareAttributes($request);
        $data['slug'] = Opportunity::generateUniqueSlug($data['judul']);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        Opportunity::query()->create($data);

        return redirect()
            ->route('admin.opportunities.index')
            ->with('success', 'Informasi lomba/beasiswa berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified opportunity.
     */
    public function edit(Opportunity $opportunity): View
    {
        return view('admin.opportunities.edit', [
            'opportunity' => $opportunity,
        ]);
    }

    /**
     * Update the specified opportunity in storage.
     */
    public function update(UpdateOpportunityRequest $request, Opportunity $opportunity): RedirectResponse
    {
        $data = $this->prepareAttributes($request);

        if ($data['judul'] !== $opportunity->judul) {
            $data['slug'] = Opportunity::generateUniqueSlug($data['judul'], $opportunity->id);
        }

        if ($request->hasFile('poster')) {
            $this->deleteStoredPoster($opportunity->poster_path);
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        } elseif ($request->boolean('remove_poster')) {
            $this->deleteStoredPoster($opportunity->poster_path);
            $data['poster_path'] = null;
        }

        $opportunity->update($data);

        return redirect()
            ->route('admin.opportunities.index')
            ->with('success', 'Informasi berhasil diperbarui.');
    }

    /**
     * Remove the specified opportunity from storage.
     */
    public function destroy(Opportunity $opportunity): RedirectResponse
    {
        $this->deleteStoredPoster($opportunity->poster_path);
        $opportunity->delete();

        return redirect()
            ->route('admin.opportunities.index')
            ->with('success', 'Informasi berhasil dihapus.');
    }

    /**
     * Normalize validated attributes for storing or updating an opportunity.
     *
     * @return array<string, mixed>
     */
    private function prepareAttributes(StoreOpportunityRequest $request): array
    {
        $validated = $request->validated();
        unset($validated['poster'], $validated['remove_poster']);

        if (($validated['biaya_tipe'] ?? '') === FeeType::Gratis->value) {
            $validated['nominal_biaya'] = null;
        }

        return $validated;
    }

    private function deleteStoredPoster(?string $posterPath): void
    {
        if (! $posterPath || str_starts_with($posterPath, 'images/') || str_starts_with($posterPath, 'http')) {
            return;
        }

        Storage::disk('public')->delete($posterPath);
    }
}
