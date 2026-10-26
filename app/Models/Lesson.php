<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_module_id',
        'title',
        'description',
        'duration_minutes',
        'video_provider',
        'video_id',
        'is_preview',
        'attachment_title',
        'attachment_url',
        'sort_order',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
        'duration_minutes' => 'integer',
        'sort_order' => 'integer',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function isCompletedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->completions()->where('user_id', $user->id)->exists();
    }
}
