<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'key',
        'value',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function scopeSearch($query, $term)
    {
        $term = '%' . $term . '%';
        return $query->where('key', 'like', $term)
            ->orWhere('value', 'like', $term)
            ->orWhereJsonContains('tags', $term);
    }
}
