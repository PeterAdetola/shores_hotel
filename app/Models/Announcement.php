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
        'discount_weekday',
        'discount_weekend',
        'features',
        'cta_text',
        'cta_link',
        'border_color',
        'primary_emoji',
        'is_published',
        'start_date',
        'end_date',
        'order'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'features' => 'array'
    ];

    /**
     * Scope to get only published announcements
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get active announcements (within date range if set)
     */
    public function scopeActive($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Boot method to ensure only one published announcement
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($announcement) {
            if ($announcement->is_published) {
                // Unpublish all other announcements
                static::where('id', '!=', $announcement->id)
                    ->update(['is_published' => false]);
            }
        });
    }
}
