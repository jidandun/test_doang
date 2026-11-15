@extends('layouts.app')

@section('title', $opportunity->judul . ' — InfoPrestasi')

@section('content')
    @php
        $isScholarship = $opportunity->jenis === \App\OpportunityType::Beasiswa;
        $isFree = $opportunity->biaya_tipe === \App\FeeType::Gratis;
        $isClosed = $opportunity->isClosed();
        $requirements = collect(preg_split('/\r\n|\r|\n/', (string) $opportunity->syarat))
            ->map(fn ($line) => trim(ltrim($line, "-•* \t")))
            ->filter();
        $benefits = collect(preg_split('/\r\n|\r|\n/', (string) $opportunity->hadiah_benefit))
            ->map(fn ($line) => trim(ltrim($line, "-•* \t")))
            ->filter();

        $shareText = sprintf(
            "[%s] %s\nPenyelenggara: %s\nBiaya: %s\nSyarat Min.: Semester %d | IPK %s\nTenggat: %s\n\nInfo Lengkap: %s\nDaftar Resmi: %s",
            strtoupper($opportunity->jenis->label()),
            $opportunity->judul,
            $opportunity->penyelenggara,
            $opportunity->formattedFee(),
            $opportunity->min_semester,
            $opportunity->min_ipk > 0 ? number_format($opportunity->min_ipk, 2) : 'Tanpa batas',
            $opportunity->deadline->translatedFormat('d F Y'),
            route('opportunities.show', $opportunity),
            $opportunity->url_pendaftaran
        );
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('home', ['jenis' => $opportunity->jenis->value]) }}" class="hover:text-indigo-600">
                {{ $opportunity->jenis->label() }}
            </a>
            <span>/</span>
            <span class="truncate text-slate-800">{{ $opportunity->judul }}</span>
        </nav>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            {{-- Left Column: Poster & Detailed Information --}}
            <div class="space-y-6 lg:col-span-8">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <x-opportunity-poster :opportunity="$opportunity" class="aspect-[16/9] w-full" />
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ $isScholarship ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                            {{ $opportunity->jenis->label() }}
                        </span>

                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ $isFree ? 'bg-teal-100 text-teal-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $isFree ? 'Gratis' : 'Berbayar (' . $opportunity->formattedFee() . ')' }}
                        </span>

                        @if($opportunity->kategori)
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                {{ $opportunity->kategori }}
                            </span>
                        @endif

                        <x-deadline-badge :deadline="$opportunity->deadline" size="md" />
                    </div>

                    <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                        {{ $opportunity->judul }}
                    </h1>

                    <p class="mt-2 flex items-center gap-2 text-sm font-semibold text-slate-600">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                        </svg>
                        Diselenggarakan oleh <span class="text-slate-900">{{ $opportunity->penyelenggara }}</span>
                    </p>

                    {{-- Description --}}
                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <h2 class="text-lg font-bold text-slate-900">Deskripsi Program</h2>
                        <div class="mt-3 space-y-3 text-sm leading-relaxed text-slate-700 sm:text-base">
                            {!! nl2br(e($opportunity->deskripsi)) !!}
                        </div>
                    </div>

                    {{-- Requirements --}}
                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <h2 class="text-lg font-bold text-slate-900">Syarat &amp; Ketentuan Lengkap</h2>
                        <ul class="mt-4 space-y-2.5">
                            @foreach($requirements as $item)
                                <li class="flex items-start gap-3 rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Benefits / Prizes --}}
                    @if($benefits->isNotEmpty())
                        <div class="mt-8 border-t border-slate-100 pt-6">
                            <h2 class="text-lg font-bold text-slate-900">
                                {{ $isScholarship ? 'Cakupan & Benefit Beasiswa' : 'Hadiah & Penghargaan' }}
                            </h2>
                            <ul class="mt-4 space-y-2.5">
                                @foreach($benefits as $benefit)
                                    <li class="flex items-start gap-3 rounded-xl bg-emerald-50/70 px-4 py-3 text-sm text-emerald-950">
                                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                                        </svg>
                                        <span>{{ $benefit }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: Sticky Action & Summary Card --}}
            <aside class="lg:col-span-4">
                <div class="sticky top-24 space-y-5">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-base font-extrabold text-slate-900">Ringkasan Informasi</h2>

                        <dl class="mt-4 divide-y divide-slate-100 text-sm">
                            <div class="flex items-center justify-between py-3">
                                <dt class="text-slate-500">Jenis</dt>
                                <dd class="font-bold text-slate-900">{{ $opportunity->jenis->label() }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <dt class="text-slate-500">Biaya</dt>
                                <dd class="font-bold {{ $isFree ? 'text-teal-700' : 'text-amber-700' }}">
                                    {{ $opportunity->formattedFee() }}
                                </dd>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <dt class="text-slate-500">Min. Semester</dt>
                                <dd class="font-bold text-slate-900">Semester {{ $opportunity->min_semester }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <dt class="text-slate-500">Min. IPK</dt>
                                <dd class="font-bold text-slate-900">
                                    {{ $opportunity->min_ipk > 0 ? number_format($opportunity->min_ipk, 2) : 'Tanpa Batas' }}
                                </dd>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <dt class="text-slate-500">Batas Pendaftaran</dt>
                                <dd class="font-bold text-slate-900">{{ $opportunity->deadline->translatedFormat('d F Y') }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <dt class="text-slate-500">Status</dt>
                                <dd><x-deadline-badge :deadline="$opportunity->deadline" /></dd>
                            </div>
                        </dl>

                        {{-- Official Registration Redirect CTA --}}
                        <div class="mt-6 space-y-3">
                            @if($isClosed)
                                <div class="w-full cursor-not-allowed rounded-xl bg-slate-200 px-5 py-3.5 text-center text-sm font-bold text-slate-500">
                                    Pendaftaran Telah Ditutup
                                </div>
                                <a href="{{ route('opportunities.redirect', $opportunity) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="block w-full rounded-xl border border-slate-200 px-4 py-2.5 text-center text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                                    Kunjungi Tautan Penyelenggara &nearr;
                                </a>
                            @else
                                <a href="{{ route('opportunities.redirect', $opportunity) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3.5 text-sm font-bold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <span>Daftar di Situs Resmi</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                </a>
                                <p class="text-center text-[11px] text-slate-500">
                                    Anda akan dialihkan ke halaman resmi {{ $opportunity->penyelenggara }}.
                                </p>
                            @endif
                        </div>

                        {{-- Copy Link & Share to Class Group (Feature 7) --}}
                        <div class="mt-6 border-t border-slate-100 pt-5">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                Bagikan ke Grup Angkatan
                            </p>
                            <div class="mt-2.5 space-y-2">
                                <button type="button"
                                        id="copy-link-btn"
                                        data-copy="{{ route('opportunities.show', $opportunity) }}"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                    </svg>
                                    <span>Salin Tautan Halaman</span>
                                </button>

                                <button type="button"
                                        id="copy-summary-btn"
                                        data-copy="{{ $shareText }}"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50/70 px-4 py-2.5 text-xs font-bold text-indigo-700 transition hover:bg-indigo-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <span>Salin Format Share Grup</span>
                                </button>
                            </div>
                            <p id="copy-feedback" class="mt-2 hidden text-center text-xs font-semibold text-emerald-700"></p>
                        </div>
                    </div>

                    <a href="{{ route('home') }}"
                       class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-bold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                        &larr; Kembali ke Direktori
                    </a>
                </div>
            </aside>
        </div>

        {{-- Related Opportunities --}}
        @if($related->isNotEmpty())
            <section class="mt-14 border-t border-slate-200 pt-10">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-extrabold text-slate-900">
                        {{ $opportunity->jenis->label() }} Lainnya yang Masih Dibuka
                    </h2>
                    <a href="{{ route('home', ['jenis' => $opportunity->jenis->value]) }}"
                       class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    @foreach($related as $item)
                        <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <a href="{{ route('opportunities.show', $item) }}" class="relative block">
                                <x-opportunity-poster :opportunity="$item" />
                            </a>
                            <div class="flex flex-1 flex-col p-5">
                                <div class="flex items-center justify-between gap-2">
                                    <x-deadline-badge :deadline="$item->deadline" />
                                    <span class="text-xs font-bold text-slate-600">{{ $item->formattedFee() }}</span>
                                </div>
                                <h3 class="mt-3 line-clamp-2 text-base font-bold text-slate-900 group-hover:text-indigo-700">
                                    <a href="{{ route('opportunities.show', $item) }}">{{ $item->judul }}</a>
                                </h3>
                                <p class="mt-1 truncate text-xs text-slate-500">{{ $item->penyelenggara }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const feedbackEl = document.getElementById('copy-feedback');

        async function copyText(text) {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                return;
            }
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        }

        function attachCopyHandler(buttonId, successLabel, feedbackMessage) {
            const btn = document.getElementById(buttonId);
            if (!btn) return;
            const labelSpan = btn.querySelector('span');
            const originalLabel = labelSpan.textContent;

            btn.addEventListener('click', async function () {
                try {
                    await copyText(btn.dataset.copy);
                    labelSpan.textContent = successLabel;
                    btn.classList.add('border-emerald-400', 'bg-emerald-50', 'text-emerald-700');
                    if (feedbackEl) {
                        feedbackEl.textContent = feedbackMessage;
                        feedbackEl.classList.remove('hidden');
                    }
                    setTimeout(() => {
                        labelSpan.textContent = originalLabel;
                        btn.classList.remove('border-emerald-400', 'bg-emerald-50', 'text-emerald-700');
                        if (feedbackEl) {
                            feedbackEl.classList.add('hidden');
                        }
                    }, 2500);
                } catch (err) {
                    alert('Gagal menyalin otomatis. Silakan salin tautan dari address bar.');
                }
            });
        }

        attachCopyHandler('copy-link-btn', 'Tautan Berhasil Disalin!', 'Tautan halaman detail sudah disalin ke clipboard.');
        attachCopyHandler('copy-summary-btn', 'Format Grup Tersalin!', 'Info terstruktur siap ditempel ke grup WhatsApp/Telegram angkatan.');
    })();
</script>
@endpush
