<?php

namespace App\Models;

use App\Casts\PostgresArray;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = ['translation_key', 'locale', 'translated_value', 'context_tag'];

    protected $casts = [
        'context_tag' => PostgresArray::class,
    ];


    /**
     * locale search
     * @param Builder $query
     * @param string $locale
     * @return Builder
     */
    public function scopeByLocale(Builder $query, string $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Partial key matching (e.g., "auth." finds "auth.login" and "auth.logout")
     */
    public function scopeByKey(Builder $query, string $key)
    {
        return $query->where('translation_key', 'ILIKE', "%{$key}%");
    }

    /**
     * Search within the JSONB array (High Performance)
     * Search by Tags (Postgres Array Overlap)
     */
    public function scopeByTags(Builder $query, string|array $tags): Builder
    {
        // 1. Convert "mobile,ios" string into PHP array ["mobile", "ios"]
        $tagArray = is_array($tags) ? $tags : explode(',', $tags);

        // 2. Use the Postgres ARRAY syntax
        // This finds rows that contain ALL of these tags
        return $query->whereRaw('context_tag @> ?::text[]', [
            $this->toPostgresArray($tagArray)
        ]);
    }

    /**
     * // Full-Text Search (using the GIN index)
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeByContent(Builder $query, string $search): Builder
    {
        return $query->whereRaw("content_vector @@ plainto_tsquery('simple', ?)", [$search]);
    }

    protected function contextTag(): Attribute
    {
        return Attribute::make(
            // When getting from DB: Convert Postgres '{a,b}' to PHP ['a', 'b']
            get: function ($value) {
                if (!$value || $value === '{}') return [];
                return explode(',', str_replace(['{', '}', '"'], '', $value));
            },

            // When saving to DB: Convert PHP ['a', 'b'] to Postgres '{a,b}'
            set: function ($value) {
                if (is_array($value)) {
                    $values = array_map(fn($v) => '"' . str_replace('"', '\"', $v) . '"', $value);
                    return '{' . implode(',', $values) . '}';
                }
                return '{}';
            }
        );
    }

    private function toPostgresArray(array $array): string
    {
        return '{' . implode(',', $array) . '}';
    }
}
