@extends('layouts.app')

@section('title', 'Login Admin — InfoPrestasi')

@section('content')
    <div class="mx-auto flex max-w-md flex-col justify-center px-4 py-14 sm:px-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-lg shadow-slate-200/50">
            <div class="text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-sm">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <h1 class="mt-4 text-xl font-extrabold text-slate-900">Login Panel Admin</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Masuk untuk mengelola data lomba, beasiswa, dan unggah poster.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
                        Email Admin
                    </label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           placeholder="admin@direktori.ac.id"
                           class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
                        Kata Sandi
                    </label>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           placeholder="••••••••"
                           class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 @error('password') border-red-400 @enderror">
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                    <input type="checkbox"
                           name="remember"
                           value="1"
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    Ingat sesi login saya
                </label>

                <button type="submit"
                        class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="mt-6 border-t border-slate-100 pt-4 text-center">
                <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-500 hover:text-indigo-600">
                    &larr; Kembali ke Halaman Publik
                </a>
            </div>
        </div>
    </div>
@endsection
