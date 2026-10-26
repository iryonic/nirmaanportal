<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MockTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_category_id',
        'title',
        'slug',
        'description',
        'duration_minutes',
        'total_marks',
        'pass_marks',
        'negative_marking_ratio',
        'is_free',
        'is_published',
        'instructions',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'total_marks' => 'decimal:2',
        'pass_marks' => 'decimal:2',
        'negative_marking_ratio' => 'decimal:2',
        'is_free' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function examCategory(): BelongsTo
    {
        return $this->belongsTo(ExamCategory::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'mock_test_questions')
            ->withPivot('sort_order')
            ->orderBy('mock_test_questions.sort_order')
            ->withTimestamps();
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(TestAttempt::class);
    }
}
