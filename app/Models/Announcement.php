<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'cta_text',
        'cta_link',
        'border_color',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Automatically unpublish other announcements when publishing this one
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($announcement) {
            if ($announcement->is_published) {
                // Unpublish all other announcements
                static::where('id', '!=', $announcement->id)
                    ->where('is_published', true)
                    ->update(['is_published' => false]);
            }
        });
    }

    // Scope for published announcements
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
