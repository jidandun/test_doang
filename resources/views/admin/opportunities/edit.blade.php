@extends('layouts.app')

@section('title', 'Edit Informasi — Admin InfoPrestasi')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Edit Informasi</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $opportunity->judul }}</p>
            </div>
            <a href="{{ route('admin.opportunities.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">
                &larr; Kembali
            </a>
        </div>

        <form method="POST"
              action="{{ route('admin.opportunities.update', $opportunity) }}"
              enctype="multipart/form-data"
              class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            @method('PUT')

            @include('admin.opportunities._form', ['opportunity' => $opportunity])

            <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                <a href="{{ route('admin.opportunities.index') }}"
                   class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">
                    Perbarui Informasi
                </button>
            </div>
        </form>
    </div>
@endsection
