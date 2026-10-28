<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'content',
        'external_url',
        'attachment_path',
        'attachment_name',
        'attachment_size',
        'is_published',
        'published_at',
        'opens_in_new_tab',
        'is_pinned',
        'sort_order',
        'views_count',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'opens_in_new_tab' => 'boolean',
            'is_pinned' => 'boolean',
            'published_at' => 'datetime',
            'views_count' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Notification $notification) {
            if (empty($notification->slug)) {
                $baseSlug = Str::slug($notification->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $notification->slug = $slug;
            }
        });
    }

    /**
     * Scope to retrieve only publicly published notifications.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to retrieve pinned notifications.
     */
    public function scopePinned(Builder $query): Builder
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Dynamic server-side calculation for the 72-hour NEW badge.
     * The countdown starts strictly from the actual publication time (published_at),
     * not creation or editing time, and automatically expires after 72 hours.
     */
    public function isNew(): bool
    {
        if (! $this->is_published || ! $this->published_at) {
            return false;
        }

        $publishedAt = Carbon::parse($this->published_at);
        $now = now();

        return $publishedAt->lte($now) && $publishedAt->gte($now->copy()->subHours(72));
    }

    /**
     * Accessor for $notification->is_new
     */
    public function getIsNewAttribute(): bool
    {
        return $this->isNew();
    }

    /**
     * Compute remaining hours for the NEW badge (useful for admin overview).
     */
    public function getNewBadgeRemainingHoursAttribute(): ?int
    {
        if (! $this->isNew()) {
            return null;
        }

        $expiresAt = Carbon::parse($this->published_at)->addHours(72);
        return max(0, now()->diffInHours($expiresAt, false));
    }

    /**
     * Action URL when the notification title is clicked.
     */
    public function getActionUrlAttribute(): string
    {
        if ($this->attachment_path) {
            return route('notifications.download', $this->id);
        }

        if ($this->external_url) {
            return $this->external_url;
        }

        return route('announcements', ['notice' => $this->id]);
    }

    /**
     * Type of destination target ('attachment', 'link', or 'notice').
     */
    public function getTargetTypeAttribute(): string
    {
        if ($this->attachment_path) {
            return 'attachment';
        }

        if ($this->external_url) {
            return 'link';
        }

        return 'notice';
    }

    /**
     * Human readable publication date format.
     */
    public function getFormattedDateAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;
        return $date ? $date->format('M d, Y') : '';
    }

    /**
     * Relation to author/administrator who created the notification.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
