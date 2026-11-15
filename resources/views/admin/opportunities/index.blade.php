@extends('layouts.app')

@section('title', 'Manajemen Konten Admin — InfoPrestasi')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Manajemen Lomba &amp; Beasiswa</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Tambah, perbarui, hapus data informasi, dan unggah poster resmi penyelenggara.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Lihat Web Publik
                </a>
                <a href="{{ route('admin.opportunities.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Data
                </a>
            </div>
        </div>

        {{-- Summary KPIs --}}
        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold text-slate-500">Total Data</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-900">{{ $summary['total'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold text-slate-500">Lomba</p>
                <p class="mt-1 text-2xl font-extrabold text-indigo-600">{{ $summary['lomba'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold text-slate-500">Beasiswa</p>
                <p class="mt-1 text-2xl font-extrabold text-emerald-600">{{ $summary['beasiswa'] }}</p>
            </div>
            <div class="rounded-2xl border border-red-100 bg-red-50/50 p-4 shadow-sm">
                <p class="text-xs font-semibold text-red-600">Segera Berakhir</p>
                <p class="mt-1 text-2xl font-extrabold text-red-700">{{ $summary['segera_berakhir'] }}</p>
            </div>
            <div class="col-span-2 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:col-span-1">
                <p class="text-xs font-semibold text-slate-500">Ditutup</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-500">{{ $summary['ditutup'] }}</p>
            </div>
        </div>

        {{-- Admin Search & Filter --}}
        <form method="GET" action="{{ route('admin.opportunities.index') }}" class="mt-6 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
            <input type="text"
                   name="q"
                   value="{{ $filters['q'] ?? '' }}"
                   placeholder="Cari judul atau penyelenggara..."
                   class="flex-1 rounded-xl border border-slate-300 px-3.5 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">

            <select name="jenis" class="rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700">
                <option value="">Semua Jenis</option>
                <option value="lomba" @selected(($filters['jenis'] ?? '') === 'lomba')>Lomba</option>
                <option value="beasiswa" @selected(($filters['jenis'] ?? '') === 'beasiswa')>Beasiswa</option>
            </select>

            <select name="biaya" class="rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700">
                <option value="">Semua Biaya</option>
                <option value="gratis" @selected(($filters['biaya'] ?? '') === 'gratis')>Gratis</option>
                <option value="berbayar" @selected(($filters['biaya'] ?? '') === 'berbayar')>Berbayar</option>
            </select>

            <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(($filters['status'] ?? '') === 'aktif')>Masih Dibuka</option>
                <option value="segera" @selected(($filters['status'] ?? '') === 'segera')>Segera Berakhir</option>
                <option value="ditutup" @selected(($filters['status'] ?? '') === 'ditutup')>Ditutup</option>
            </select>

            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Filter
                </button>
                @if(collect($filters)->filter()->isNotEmpty())
                    <a href="{{ route('admin.opportunities.index') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">Program</th>
                            <th class="px-4 py-3.5">Jenis &amp; Biaya</th>
                            <th class="px-4 py-3.5">Syarat Min.</th>
                            <th class="px-4 py-3.5">Deadline</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($opportunities as $opportunity)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="h-12 w-16 shrink-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                                            <x-opportunity-poster :opportunity="$opportunity" class="h-full w-full" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="max-w-xs truncate font-bold text-slate-900">{{ $opportunity->judul }}</p>
                                            <p class="max-w-xs truncate text-xs text-slate-500">{{ $opportunity->penyelenggara }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex w-fit rounded-full px-2.5 py-0.5 text-xs font-bold {{ $opportunity->jenis === \App\OpportunityType::Beasiswa ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                            {{ $opportunity->jenis->label() }}
                                        </span>
                                        <span class="text-xs font-semibold {{ $opportunity->biaya_tipe === \App\FeeType::Gratis ? 'text-teal-700' : 'text-amber-700' }}">
                                            {{ $opportunity->formattedFee() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-xs font-medium text-slate-700">
                                    <div>Sem {{ $opportunity->min_semester }}</div>
                                    <div>IPK {{ $opportunity->min_ipk > 0 ? number_format($opportunity->min_ipk, 2) : 'Bebas' }}</div>
                                </td>
                                <td class="px-4 py-4 text-xs font-semibold text-slate-700">
                                    {{ $opportunity->deadline->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-4">
                                    <x-deadline-badge :deadline="$opportunity->deadline" />
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('opportunities.show', $opportunity) }}"
                                           target="_blank"
                                           class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100">
                                            Lihat
                                        </a>
                                        <a href="{{ route('admin.opportunities.edit', $opportunity) }}"
                                           class="rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">
                                            Edit
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.opportunities.destroy', $opportunity) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus data \"{{ addslashes($opportunity->judul) }}\"?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-lg border border-red-200 bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">
                                    Belum ada data lomba atau beasiswa yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($opportunities->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $opportunities->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
