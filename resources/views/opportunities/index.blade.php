@extends('layouts.app')

@section('title', 'Direktori Informasi Lomba & Beasiswa Mahasiswa — InfoPrestasi')

@section('content')
    {{-- Hero & Smart Search Section --}}
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-br from-indigo-950 via-indigo-900 to-slate-900 text-white">
        <div class="pointer-events-none absolute inset-0 opacity-20">
            <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-indigo-400 blur-3xl"></div>
            <div class="absolute -bottom-24 right-10 h-80 w-80 rounded-full bg-emerald-400 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-1 text-xs font-semibold text-indigo-100 backdrop-blur-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Portal Kurasi Lomba &amp; Beasiswa Mahasiswa
                </span>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Temukan Peluang Lomba &amp; Beasiswa Sesuai Kriteriamu
                </h1>
                <p class="mt-3 text-base text-indigo-100/90 sm:text-lg">
                    Saring informasi berdasarkan jenis program, batas minimal semester, batas minimal IPK, dan biaya pendaftaran. Daftar langsung melalui tautan resmi penyelenggara.
                </p>
            </div>

            {{-- Live Stats --}}
            <div class="mt-7 flex flex-wrap gap-3 text-xs sm:text-sm">
                <div class="rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 backdrop-blur-sm">
                    <span class="font-bold text-white">{{ $stats['total_aktif'] }}</span>
                    <span class="text-indigo-200">Program Aktif</span>
                </div>
                <div class="rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 backdrop-blur-sm">
                    <span class="font-bold text-white">{{ $stats['lomba_aktif'] }}</span>
                    <span class="text-indigo-200">Lomba Dibuka</span>
                </div>
                <div class="rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 backdrop-blur-sm">
                    <span class="font-bold text-white">{{ $stats['beasiswa_aktif'] }}</span>
                    <span class="text-indigo-200">Beasiswa Dibuka</span>
                </div>
                <div class="rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 backdrop-blur-sm">
                    <span class="font-bold text-emerald-300">{{ $stats['gratis_aktif'] }}</span>
                    <span class="text-indigo-200">Gratis</span>
                </div>
                @if($stats['segera_berakhir'] > 0)
                    <a href="{{ route('home', ['status' => 'segera']) }}"
                       class="rounded-xl border border-red-400/40 bg-red-500/20 px-3.5 py-2 font-semibold text-red-100 backdrop-blur-sm transition hover:bg-red-500/30">
                        <span class="font-bold text-white">{{ $stats['segera_berakhir'] }}</span>
                        Segera Berakhir (&lt; 3 Hari)
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Main Search, Filter & Grid Section --}}
    <section class="mx-auto -mt-7 max-w-7xl px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('home') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-lg shadow-slate-200/60 sm:p-6">
            {{-- Smart Search Bar --}}
            <div class="flex flex-col gap-3 sm:flex-row">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input type="text"
                           name="q"
                           value="{{ $filters['q'] ?? '' }}"
                           placeholder="Cari nama lomba, beasiswa, penyelenggara, atau kata kunci (misal: UX Design, Bank Indonesia, gratis)..."
                           class="w-full rounded-xl border border-slate-300 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                </div>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    Cari &amp; Saring
                </button>
            </div>

            {{-- Multi-Criteria Filters --}}
            <div class="mt-4 grid grid-cols-1 gap-3.5 border-t border-slate-100 pt-4 sm:grid-cols-2 lg:grid-cols-5">
                {{-- 1. Jenis (Lomba / Beasiswa) --}}
                <div>
                    <label for="filter-jenis" class="block text-xs font-bold uppercase tracking-wide text-slate-500">
                        Jenis Program
                    </label>
                    <select id="filter-jenis"
                            name="jenis"
                            class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">Semua Jenis</option>
                        <option value="lomba" @selected(($filters['jenis'] ?? '') === 'lomba')>Lomba</option>
                        <option value="beasiswa" @selected(($filters['jenis'] ?? '') === 'beasiswa')>Beasiswa</option>
                    </select>
                </div>

                {{-- 2. Biaya (Gratis / Berbayar) --}}
                <div>
                    <label for="filter-biaya" class="block text-xs font-bold uppercase tracking-wide text-slate-500">
                        Biaya Pendaftaran
                    </label>
                    <select id="filter-biaya"
                            name="biaya"
                            class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">Semua Biaya</option>
                        <option value="gratis" @selected(($filters['biaya'] ?? '') === 'gratis')>Gratis</option>
                        <option value="berbayar" @selected(($filters['biaya'] ?? '') === 'berbayar')>Berbayar</option>
                    </select>
                </div>

                {{-- 3. Batas Minimal Semester --}}
                <div>
                    <label for="filter-semester" class="block text-xs font-bold uppercase tracking-wide text-slate-500">
                        Semester Kamu
                    </label>
                    <select id="filter-semester"
                            name="min_semester"
                            class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">Semua Semester</option>
                        @for($sem = 1; $sem <= 8; $sem++)
                            <option value="{{ $sem }}" @selected((string) ($filters['min_semester'] ?? '') === (string) $sem)>
                                Semester {{ $sem }} (Min. Sem &le; {{ $sem }})
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- 4. Batas Minimal IPK --}}
                <div>
                    <label for="filter-ipk" class="block text-xs font-bold uppercase tracking-wide text-slate-500">
                        IPK Kamu (0.00 – 4.00)
                    </label>
                    <input id="filter-ipk"
                           type="number"
                           name="min_ipk"
                           step="0.05"
                           min="0"
                           max="4"
                           value="{{ $filters['min_ipk'] ?? '' }}"
                           placeholder="Misal: 3.25"
                           class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                </div>

                {{-- 5. Status & Urutkan --}}
                <div class="grid grid-cols-2 gap-2 lg:grid-cols-1">
                    <div>
                        <label for="filter-status" class="block text-xs font-bold uppercase tracking-wide text-slate-500">
                            Status Tenggat
                        </label>
                        <select id="filter-status"
                                name="status"
                                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                            <option value="">Semua Status</option>
                            <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Masih Dibuka</option>
                            <option value="segera" @selected(($filters['status'] ?? '') === 'segera')>Segera Berakhir (&lt; 3 Hari)</option>
                            <option value="ditutup" @selected(($filters['status'] ?? '') === 'ditutup')>Sudah Ditutup</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Footer of Filter Panel: Sort + Reset --}}
            @php
                $hasActiveFilters = collect($filters)->filter(fn ($v) => $v !== null && $v !== '')->isNotEmpty();
            @endphp
            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3.5 text-xs">
                <p class="text-slate-500">
                    Menampilkan <span class="font-bold text-slate-800">{{ $opportunities->total() }}</span> informasi yang sesuai kriteria.
                    <span class="hidden sm:inline">Filter Semester &amp; IPK mencocokkan syarat minimal &le; profil Anda.</span>
                </p>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label for="filter-sort" class="font-semibold text-slate-600">Urutkan:</label>
                        <select id="filter-sort"
                                name="sort"
                                onchange="this.form.submit()"
                                class="rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 focus:border-indigo-500 focus:outline-none">
                            <option value="terdekat" @selected(($filters['sort'] ?? 'terdekat') === 'terdekat')>Deadline Terdekat</option>
                            <option value="terbaru" @selected(($filters['sort'] ?? '') === 'terbaru')>Terbaru Ditambahkan</option>
                            <option value="ipk_terendah" @selected(($filters['sort'] ?? '') === 'ipk_terendah')>Min. IPK Terendah</option>
                        </select>
                    </div>

                    @if($hasActiveFilters)
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-slate-100 px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-200">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Reset Filter
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Grid Layout --}}
        <div class="mt-8">
            @if($opportunities->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <h2 class="mt-4 text-lg font-bold text-slate-900">Tidak ada lomba atau beasiswa yang cocok</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Coba ubah kata kunci pencarian atau longgarkan filter semester, IPK, maupun biaya.
                    </p>
                    <a href="{{ route('home') }}"
                       class="mt-5 inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        Tampilkan Semua Informasi
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($opportunities as $opportunity)
                        @php
                            $isScholarship = $opportunity->jenis === \App\OpportunityType::Beasiswa;
                            $isFree = $opportunity->biaya_tipe === \App\FeeType::Gratis;
                            $isClosed = $opportunity->isClosed();
                        @endphp
                        <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md {{ $isClosed ? 'opacity-85' : '' }}">
                            <a href="{{ route('opportunities.show', $opportunity) }}" class="relative block">
                                <x-opportunity-poster :opportunity="$opportunity" />

                                {{-- Top Overlay Badges --}}
                                <div class="absolute inset-x-3 top-3 flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold shadow-sm backdrop-blur-sm {{ $isScholarship ? 'bg-emerald-600/95 text-white' : 'bg-indigo-600/95 text-white' }}">
                                        {{ $opportunity->jenis->label() }}
                                    </span>

                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold shadow-sm backdrop-blur-sm {{ $isFree ? 'bg-white/95 text-teal-700' : 'bg-amber-400/95 text-amber-950' }}">
                                        {{ $opportunity->formattedFee() }}
                                    </span>
                                </div>
                            </a>

                            <div class="flex flex-1 flex-col p-5">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <x-deadline-badge :deadline="$opportunity->deadline" />

                                    @if($opportunity->kategori)
                                        <span class="truncate text-xs font-medium text-slate-500">
                                            {{ $opportunity->kategori }}
                                        </span>
                                    @endif
                                </div>

                                <h2 class="mt-3 line-clamp-2 text-lg font-extrabold leading-snug text-slate-900 group-hover:text-indigo-700">
                                    <a href="{{ route('opportunities.show', $opportunity) }}">
                                        {{ $opportunity->judul }}
                                    </a>
                                </h2>

                                <p class="mt-1.5 flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                                    </svg>
                                    <span class="truncate">{{ $opportunity->penyelenggara }}</span>
                                </p>

                                <p class="mt-2.5 line-clamp-2 text-sm text-slate-600">
                                    {{ $opportunity->deskripsi }}
                                </p>

                                {{-- Academic Requirement Pills --}}
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                        Min. Sem {{ $opportunity->min_semester }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                        Min. IPK {{ $opportunity->min_ipk > 0 ? number_format($opportunity->min_ipk, 2) : 'Bebas' }}
                                    </span>
                                </div>

                                <div class="mt-auto pt-4">
                                    <div class="flex items-center justify-between border-t border-slate-100 pt-3.5">
                                        <div class="text-xs text-slate-500">
                                            <span class="block text-[11px] font-medium uppercase tracking-wide text-slate-400">Tenggat</span>
                                            <span class="font-bold text-slate-700">{{ $opportunity->deadline->translatedFormat('d M Y') }}</span>
                                        </div>

                                        <a href="{{ route('opportunities.show', $opportunity) }}"
                                           class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 px-3.5 py-2 text-xs font-bold text-indigo-700 transition hover:bg-indigo-600 hover:text-white">
                                            Lihat Detail
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($opportunities->hasPages())
                    <div class="mt-8">
                        {{ $opportunities->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
