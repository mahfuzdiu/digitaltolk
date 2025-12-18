<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PostgresArray implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (!$value || $value === '{}') return [];
        // Removes { } and quotes, then explodes into array
        return explode(',', str_replace(['{', '}', '"'], '', $value));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (is_array($value)) {
            $values = array_map(fn($v) => '"' . str_replace('"', '\"', $v) . '"', $value);
            return '{' . implode(',', $values) . '}';
        }
        return '{}';
    }
}
