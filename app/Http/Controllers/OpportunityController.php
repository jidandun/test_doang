<?php

namespace App\Http\Controllers;

use App\FeeType;
use App\Models\Opportunity;
use App\OpportunityType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpportunityController extends Controller
{
    /**
     * Display the public directory grid with multi-criteria filtering and smart search.
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'q',
            'jenis',
            'biaya',
            'min_semester',
            'min_ipk',
            'status',
            'sort',
        ]);

        $today = Carbon::today()->toDateString();
        $sort = $filters['sort'] ?? 'terdekat';

        $query = Opportunity::query()
            ->search($filters['q'] ?? null)
            ->filter($filters);

        if ($sort === 'terbaru') {
            $query->latest();
        } elseif ($sort === 'ipk_terendah') {
            $query->orderBy('min_ipk', 'asc')
                ->orderBy('min_semester', 'asc')
                ->orderBy('deadline', 'asc');
        } else {
            // Default: active opportunities sorted by nearest deadline first, then closed ones.
            $query->orderByRaw('CASE WHEN deadline >= ? THEN 0 ELSE 1 END ASC', [$today])
                ->orderBy('deadline', 'asc');
        }

        $opportunities = $query->paginate(9)->withQueryString();

        $soonLimit = Carbon::today()->addDays(2)->toDateString();
        $stats = [
            'total_aktif' => Opportunity::query()->whereDate('deadline', '>=', $today)->count(),
            'lomba_aktif' => Opportunity::query()
                ->where('jenis', OpportunityType::Lomba->value)
                ->whereDate('deadline', '>=', $today)
                ->count(),
            'beasiswa_aktif' => Opportunity::query()
                ->where('jenis', OpportunityType::Beasiswa->value)
                ->whereDate('deadline', '>=', $today)
                ->count(),
            'gratis_aktif' => Opportunity::query()
                ->where('biaya_tipe', FeeType::Gratis->value)
                ->whereDate('deadline', '>=', $today)
                ->count(),
            'segera_berakhir' => Opportunity::query()
                ->whereDate('deadline', '>=', $today)
                ->whereDate('deadline', '<=', $soonLimit)
                ->count(),
        ];

        return view('opportunities.index', [
            'opportunities' => $opportunities,
            'filters' => $filters,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the detailed page of an opportunity.
     */
    public function show(Opportunity $opportunity): View
    {
        $today = Carbon::today()->toDateString();

        $related = Opportunity::query()
            ->where('id', '!=', $opportunity->id)
            ->where('jenis', $opportunity->jenis->value)
            ->whereDate('deadline', '>=', $today)
            ->orderBy('deadline', 'asc')
            ->limit(3)
            ->get();

        return view('opportunities.show', [
            'opportunity' => $opportunity,
            'related' => $related,
        ]);
    }

    /**
     * Redirect students to the official organizer registration link.
     */
    public function redirect(Opportunity $opportunity): RedirectResponse
    {
        $url = $opportunity->url_pendaftaran;

        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            abort(404, 'Tautan pendaftaran resmi tidak tersedia.');
        }

        return redirect()->away($url);
    }
}
