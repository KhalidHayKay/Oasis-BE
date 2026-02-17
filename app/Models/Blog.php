<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'body',
        'hashtags',
    ];

    protected $casts = [
        'hashtags' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Blog $blog) {
            // Auto-generate slug from title only when not explicitly provided
            if (empty($blog->slug)) {
                $blog->slug = static::generateUniqueSlug($blog->title);
            }
        });

        static::updating(function (Blog $blog) {
            // Re-generate only when title changed and slug was NOT manually touched
            if ($blog->isDirty('title') && ! $blog->isDirty('slug')) {
                $blog->slug = static::generateUniqueSlug($blog->title, $blog->id);
            }
        });
    }

    /**
     * Generate a unique slug from the given title.
     * Appends an incrementing counter on collisions: "my-title", "my-title-2", etc.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base    = Str::slug($title);
        $slug    = $base;
        $counter = 2;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Return hashtags stripped of their # prefix, ready for UI display labels.
     * e.g. ["#interior-design", "#minimalism"] → ["interior-design", "minimalism"]
     *
     * The raw `hashtags` attribute (with #) is always available via $blog->hashtags.
     */
    public function getDisplayTagsAttribute(): array
    {
        return array_map(
            fn (string $tag) => ltrim($tag, '#'),
            $this->hashtags ?? []
        );
    }
}
