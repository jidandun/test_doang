@php
    $isEdit = isset($opportunity);
    $selectedJenis = old('jenis', $isEdit ? $opportunity->jenis->value : 'lomba');
    $selectedBiaya = old('biaya_tipe', $isEdit ? $opportunity->biaya_tipe->value : 'gratis');
@endphp

@if($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
        <p class="font-bold">Periksa kembali isian formulir Anda:</p>
        <ul class="mt-1.5 list-disc pl-5 text-xs space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    {{-- Judul --}}
    <div class="md:col-span-2">
        <label for="judul" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Judul Lomba / Beasiswa <span class="text-red-500">*</span>
        </label>
        <input id="judul"
               type="text"
               name="judul"
               value="{{ old('judul', $isEdit ? $opportunity->judul : '') }}"
               required
               placeholder="Contoh: GEMASTIK XVIII — Divisi Desain Pengalaman Pengguna"
               class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
    </div>

    {{-- Jenis --}}
    <div>
        <label for="jenis" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Jenis Program <span class="text-red-500">*</span>
        </label>
        <select id="jenis"
                name="jenis"
                required
                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            <option value="lomba" @selected($selectedJenis === 'lomba')>Lomba</option>
            <option value="beasiswa" @selected($selectedJenis === 'beasiswa')>Beasiswa</option>
        </select>
    </div>

    {{-- Kategori --}}
    <div>
        <label for="kategori" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Kategori / Bidang (Opsional)
        </label>
        <input id="kategori"
               type="text"
               name="kategori"
               value="{{ old('kategori', $isEdit ? $opportunity->kategori : '') }}"
               placeholder="Misal: Teknologi & IT, UI/UX, Pendidikan S1"
               class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
    </div>

    {{-- Penyelenggara --}}
    <div>
        <label for="penyelenggara" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Penyelenggara <span class="text-red-500">*</span>
        </label>
        <input id="penyelenggara"
               type="text"
               name="penyelenggara"
               value="{{ old('penyelenggara', $isEdit ? $opportunity->penyelenggara : '') }}"
               required
               placeholder="Misal: Kemendikbudristek, Bank Indonesia, UI"
               class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
    </div>

    {{-- Deadline --}}
    <div>
        <label for="deadline" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Tenggat Pendaftaran (Deadline) <span class="text-red-500">*</span>
        </label>
        <input id="deadline"
               type="date"
               name="deadline"
               value="{{ old('deadline', $isEdit ? $opportunity->deadline->format('Y-m-d') : '') }}"
               required
               class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
    </div>

    {{-- Biaya Tipe --}}
    <div>
        <label for="biaya_tipe" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Status Biaya <span class="text-red-500">*</span>
        </label>
        <select id="biaya_tipe"
                name="biaya_tipe"
                required
                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            <option value="gratis" @selected($selectedBiaya === 'gratis')>Gratis (Rp 0)</option>
            <option value="berbayar" @selected($selectedBiaya === 'berbayar')>Berbayar</option>
        </select>
    </div>

    {{-- Nominal Biaya --}}
    <div id="nominal-wrapper">
        <label for="nominal_biaya" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Nominal Biaya (Rp)
        </label>
        <input id="nominal_biaya"
               type="number"
               name="nominal_biaya"
               min="0"
               step="1000"
               value="{{ old('nominal_biaya', $isEdit ? $opportunity->nominal_biaya : '') }}"
               placeholder="Misal: 50000"
               class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 disabled:bg-slate-100 disabled:text-slate-400">
        <p class="mt-1 text-[11px] text-slate-500">Opsional. Diisi jika biaya pendaftaran berbayar.</p>
    </div>

    {{-- Min Semester --}}
    <div>
        <label for="min_semester" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Batas Minimal Semester <span class="text-red-500">*</span>
        </label>
        <select id="min_semester"
                name="min_semester"
                required
                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            @for($sem = 1; $sem <= 8; $sem++)
                <option value="{{ $sem }}" @selected((int) old('min_semester', $isEdit ? $opportunity->min_semester : 1) === $sem)>
                    Minimal Semester {{ $sem }} {{ $sem === 1 ? '(Semua Semester)' : '' }}
                </option>
            @endfor
        </select>
    </div>

    {{-- Min IPK --}}
    <div>
        <label for="min_ipk" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Batas Minimal IPK (0.00 – 4.00) <span class="text-red-500">*</span>
        </label>
        <input id="min_ipk"
               type="number"
               name="min_ipk"
               step="0.01"
               min="0"
               max="4"
               value="{{ old('min_ipk', $isEdit ? number_format($opportunity->min_ipk, 2, '.', '') : '0.00') }}"
               required
               class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
        <p class="mt-1 text-[11px] text-slate-500">Isi 0.00 jika tidak mensyaratkan batas IPK.</p>
    </div>

    {{-- URL Pendaftaran --}}
    <div class="md:col-span-2">
        <label for="url_pendaftaran" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Tautan Resmi Pendaftaran (Redirect URL) <span class="text-red-500">*</span>
        </label>
        <input id="url_pendaftaran"
               type="url"
               name="url_pendaftaran"
               value="{{ old('url_pendaftaran', $isEdit ? $opportunity->url_pendaftaran : '') }}"
               required
               placeholder="https://pendaftaran.penyelenggara.ac.id/"
               class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
    </div>

    {{-- Poster Upload --}}
    <div class="md:col-span-2">
        <label for="poster" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Unggah Poster (JPG, PNG, WEBP, Maks 2MB)
        </label>
        <div class="mt-1.5 flex flex-col gap-4 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 sm:flex-row sm:items-center">
            <div class="h-24 w-36 shrink-0 overflow-hidden rounded-lg border border-slate-200 bg-white">
                <img id="poster-preview"
                     src="{{ $isEdit && $opportunity->posterUrl() ? $opportunity->posterUrl() : '' }}"
                     alt="Pratinjau Poster"
                     class="h-full w-full object-cover {{ $isEdit && $opportunity->posterUrl() ? '' : 'hidden' }}">
                <div id="poster-placeholder" class="flex h-full w-full items-center justify-center text-xs font-medium text-slate-400 {{ $isEdit && $opportunity->posterUrl() ? 'hidden' : '' }}">
                    Belum ada poster
                </div>
            </div>
            <div class="flex-1 space-y-2">
                <input id="poster"
                       type="file"
                       name="poster"
                       accept="image/jpeg,image/png,image/webp"
                       class="block w-full text-xs text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3.5 file:py-2 file:text-xs file:font-bold file:text-indigo-700 hover:file:bg-indigo-100">
                @if($isEdit && $opportunity->poster_path)
                    <label class="inline-flex items-center gap-2 text-xs font-medium text-red-600">
                        <input type="checkbox" name="remove_poster" value="1" class="h-4 w-4 rounded border-slate-300 text-red-600">
                        Hapus poster saat ini
                    </label>
                @endif
            </div>
        </div>
    </div>

    {{-- Deskripsi --}}
    <div class="md:col-span-2">
        <label for="deskripsi" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Deskripsi Lengkap <span class="text-red-500">*</span>
        </label>
        <textarea id="deskripsi"
                  name="deskripsi"
                  rows="4"
                  required
                  placeholder="Jelaskan gambaran umum, tujuan, dan tahapan lomba atau beasiswa..."
                  class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">{{ old('deskripsi', $isEdit ? $opportunity->deskripsi : '') }}</textarea>
    </div>

    {{-- Syarat Lengkap --}}
    <div class="md:col-span-2">
        <label for="syarat" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Syarat &amp; Ketentuan Lengkap (1 poin per baris) <span class="text-red-500">*</span>
        </label>
        <textarea id="syarat"
                  name="syarat"
                  rows="5"
                  required
                  placeholder="Mahasiswa aktif S1/D4 seluruh Indonesia.&#10;Melampirkan KTM dan transkrip nilai terbaru.&#10;Mengisi formulir pada situs resmi penyelenggara."
                  class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">{{ old('syarat', $isEdit ? $opportunity->syarat : '') }}</textarea>
    </div>

    {{-- Hadiah / Benefit --}}
    <div class="md:col-span-2">
        <label for="hadiah_benefit" class="block text-xs font-bold uppercase tracking-wide text-slate-600">
            Hadiah / Benefit Beasiswa (Opsional, 1 poin per baris)
        </label>
        <textarea id="hadiah_benefit"
                  name="hadiah_benefit"
                  rows="3"
                  placeholder="Uang pembinaan &amp; trofi nasional&#10;Sertifikat resmi untuk seluruh peserta"
                  class="mt-1.5 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">{{ old('hadiah_benefit', $isEdit ? $opportunity->hadiah_benefit : '') }}</textarea>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const biayaSelect = document.getElementById('biaya_tipe');
        const nominalInput = document.getElementById('nominal_biaya');

        function toggleNominal() {
            if (!biayaSelect || !nominalInput) return;
            const isPaid = biayaSelect.value === 'berbayar';
            nominalInput.disabled = !isPaid;
            if (!isPaid) {
                nominalInput.value = '';
            }
        }

        if (biayaSelect) {
            toggleNominal();
            biayaSelect.addEventListener('change', toggleNominal);
        }

        const posterInput = document.getElementById('poster');
        const preview = document.getElementById('poster-preview');
        const placeholder = document.getElementById('poster-placeholder');

        if (posterInput && preview && placeholder) {
            posterInput.addEventListener('change', function (e) {
                const file = e.target.files && e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function (ev) {
                    preview.src = ev.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });
        }
    })();
</script>
@endpush
