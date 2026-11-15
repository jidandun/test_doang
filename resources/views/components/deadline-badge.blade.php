@props([
    'deadline',
    'showCountdown' => true,
    'size' => 'sm',
])

@php
    $today = \Carbon\Carbon::today();
    $deadlineDate = $deadline->copy()->startOfDay();
    $daysLeft = (int) $today->diffInDays($deadlineDate, false);

    $isClosed = $daysLeft < 0;
    $isUrgent = ! $isClosed && $daysLeft < 3;

    $sizeClasses = $size === 'md'
        ? 'px-3 py-1.5 text-xs sm:text-sm gap-1.5'
        : 'px-2.5 py-1 text-xs gap-1';
@endphp

@if($isClosed)
    <span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border border-slate-300 bg-slate-100 font-semibold text-slate-600 {$sizeClasses}"]) }}>
        <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>
        Ditutup
    </span>
@elseif($isUrgent)
    <span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border border-red-200 bg-red-600 font-bold text-white shadow-sm {$sizeClasses}"]) }}>
        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-white"></span>
        Segera Berakhir
        @if($showCountdown)
            <span class="font-medium text-red-100">
                &middot;
                @if($daysLeft === 0)
                    Hari Terakhir!
                @else
                    {{ $daysLeft }} hari lagi
                @endif
            </span>
        @endif
    </span>
@else
    <span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 font-semibold text-emerald-700 {$sizeClasses}"]) }}>
        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
        Dibuka
        @if($showCountdown)
            <span class="font-medium text-emerald-600">&middot; {{ $daysLeft }} hari lagi</span>
        @endif
    </span>
@endif
