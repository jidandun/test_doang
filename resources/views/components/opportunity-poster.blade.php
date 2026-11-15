@props([
    'opportunity',
    'class' => 'aspect-[16/10] w-full',
])

@php
    $posterUrl = $opportunity->posterUrl();
    $isScholarship = $opportunity->jenis === \App\OpportunityType::Beasiswa;
@endphp

<div class="relative overflow-hidden bg-slate-100 {{ $class }}">
    @if($posterUrl)
        <img src="{{ $posterUrl }}"
             alt="Poster {{ $opportunity->judul }}"
             loading="lazy"
             class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
    @else
        <div class="flex h-full w-full flex-col justify-between p-5 text-white {{ $isScholarship ? 'bg-gradient-to-br from-emerald-800 via-emerald-600 to-teal-500' : 'bg-gradient-to-br from-indigo-900 via-indigo-700 to-sky-500' }}">
            <span class="inline-flex w-fit rounded-full bg-white/20 px-3 py-1 text-[11px] font-bold uppercase tracking-wider backdrop-blur-sm">
                {{ $opportunity->jenis->label() }}
            </span>
            <div>
                <p class="line-clamp-2 text-lg font-extrabold leading-snug">{{ $opportunity->judul }}</p>
                <p class="mt-1 truncate text-xs text-white/80">{{ $opportunity->penyelenggara }}</p>
            </div>
        </div>
    @endif
</div>
