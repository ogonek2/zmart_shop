<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'button_text',
        'button_url',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        $relative = ltrim($this->image_path, '/');
        $cdn = rtrim((string) (env('BUNNY_CDN_URL') ?: config('app.cdn_url')), '/');

        if (Storage::disk('public')->exists($relative)) {
            // Relative URL works regardless of APP_URL / current host.
            return '/storage/' . $relative;
        }

        if ($cdn !== '') {
            return $cdn . '/' . $relative;
        }

        return '/storage/' . $relative;
    }
}
