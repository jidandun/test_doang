<?php

namespace App\Models;

use App\FeeType;
use App\OpportunityType;
use Carbon\Carbon;
use Database\Factories\OpportunityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'judul',
    'slug',
    'jenis',
    'penyelenggara',
    'kategori',
    'biaya_tipe',
    'nominal_biaya',
    'min_semester',
    'min_ipk',
    'deadline',
    'url_pendaftaran',
    'poster_path',
    'deskripsi',
    'syarat',
    'hadiah_benefit',
])]
class Opportunity extends Model
{
    /** @use HasFactory<OpportunityFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jenis' => OpportunityType::class,
            'biaya_tipe' => FeeType::class,
            'deadline' => 'date',
            'min_semester' => 'integer',
            'min_ipk' => 'float',
            'nominal_biaya' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Filter opportunities using public directory criteria.
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $query->when($filters['jenis'] ?? null, function (Builder $builder, string $jenis): void {
            if (in_array($jenis, array_column(OpportunityType::cases(), 'value'), true)) {
                $builder->where('jenis', $jenis);
            }
        });

        $query->when($filters['biaya'] ?? null, function (Builder $builder, string $biaya): void {
            if (in_array($biaya, array_column(FeeType::cases(), 'value'), true)) {
                $builder->where('biaya_tipe', $biaya);
            }
        });

        $query->when($filters['min_semester'] ?? null, function (Builder $builder, mixed $semester): void {
            $semesterInt = (int) $semester;
            if ($semesterInt >= 1 && $semesterInt <= 14) {
                $builder->where('min_semester', '<=', $semesterInt);
            }
        });

        $query->when(
            isset($filters['min_ipk']) && $filters['min_ipk'] !== '' && $filters['min_ipk'] !== null,
            function (Builder $builder) use ($filters): void {
                $ipkFloat = (float) $filters['min_ipk'];
                if ($ipkFloat >= 0 && $ipkFloat <= 4.0) {
                    $builder->where('min_ipk', '<=', $ipkFloat);
                }
            }
        );

        $query->when($filters['status'] ?? null, function (Builder $builder, string $status): void {
            $today = Carbon::today()->toDateString();

            if ($status === 'aktif') {
                $builder->whereDate('deadline', '>=', $today);
            } elseif ($status === 'segera') {
                $limit = Carbon::today()->addDays(2)->toDateString();
                $builder->whereDate('deadline', '>=', $today)
                    ->whereDate('deadline', '<=', $limit);
            } elseif ($status === 'ditutup') {
                $builder->whereDate('deadline', '<', $today);
            }
        });

        return $query;
    }

    /**
     * Smart multi-term search across title, organizer, category, description, and requirements.
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        $trimmed = trim((string) $keyword);

        if ($trimmed === '') {
            return $query;
        }

        $terms = preg_split('/\s+/', mb_strtolower($trimmed)) ?: [];

        foreach ($terms as $term) {
            if ($term === '') {
                continue;
            }

            $like = '%'.$term.'%';
            $query->where(function (Builder $sub) use ($like): void {
                $sub->whereRaw('LOWER(judul) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(penyelenggara) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(COALESCE(kategori, \'\')) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(deskripsi) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(syarat) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(jenis) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(biaya_tipe) LIKE ?', [$like]);
            });
        }

        return $query;
    }

    public function isClosed(): bool
    {
        return $this->deadline->copy()->startOfDay()->lt(Carbon::today());
    }

    public function daysRemaining(): int
    {
        return (int) Carbon::today()->diffInDays($this->deadline->copy()->startOfDay(), false);
    }

    public function formattedFee(): string
    {
        if ($this->biaya_tipe === FeeType::Gratis) {
            return 'Gratis';
        }

        if ($this->nominal_biaya !== null && $this->nominal_biaya > 0) {
            return 'Rp '.number_format($this->nominal_biaya, 0, ',', '.');
        }

        return 'Berbayar';
    }

    public function posterUrl(): ?string
    {
        if (! $this->poster_path) {
            return null;
        }

        if (str_starts_with($this->poster_path, 'http://') || str_starts_with($this->poster_path, 'https://')) {
            return $this->poster_path;
        }

        if (str_starts_with($this->poster_path, 'images/')) {
            return asset($this->poster_path);
        }

        return Storage::disk('public')->url($this->poster_path);
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = 'informasi';
        }

        $slug = $base;
        $counter = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
