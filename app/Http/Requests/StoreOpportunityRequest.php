<?php

namespace App\Http\Requests;

use App\FeeType;
use App\OpportunityType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOpportunityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'jenis' => ['required', Rule::enum(OpportunityType::class)],
            'penyelenggara' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'biaya_tipe' => ['required', Rule::enum(FeeType::class)],
            'nominal_biaya' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'min_semester' => ['required', 'integer', 'min:1', 'max:14'],
            'min_ipk' => ['required', 'numeric', 'min:0', 'max:4'],
            'deadline' => ['required', 'date'],
            'url_pendaftaran' => ['required', 'url:http,https', 'max:500'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'deskripsi' => ['required', 'string'],
            'syarat' => ['required', 'string'],
            'hadiah_benefit' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul lomba atau beasiswa wajib diisi.',
            'jenis.required' => 'Jenis program wajib dipilih.',
            'penyelenggara.required' => 'Nama penyelenggara wajib diisi.',
            'biaya_tipe.required' => 'Tipe biaya wajib dipilih.',
            'min_semester.required' => 'Batas minimal semester wajib diisi.',
            'min_ipk.required' => 'Batas minimal IPK wajib diisi.',
            'min_ipk.max' => 'Batas minimal IPK maksimal 4.00.',
            'deadline.required' => 'Tanggal tenggat pendaftaran wajib diisi.',
            'url_pendaftaran.required' => 'Tautan pendaftaran resmi wajib diisi.',
            'url_pendaftaran.url' => 'Tautan pendaftaran harus berupa URL yang valid (http:// atau https://).',
            'poster.image' => 'File poster harus berupa gambar.',
            'poster.mimes' => 'Poster harus berformat JPG, JPEG, PNG, atau WEBP.',
            'poster.max' => 'Ukuran file poster maksimal 2 MB.',
            'deskripsi.required' => 'Deskripsi lengkap wajib diisi.',
            'syarat.required' => 'Syarat dan ketentuan wajib diisi.',
        ];
    }
}
