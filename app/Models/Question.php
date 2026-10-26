<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_category_id',
        'subject',
        'topic',
        'difficulty',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'explanation',
        'marks',
        'negative_marks',
        'is_active',
    ];

    protected $casts = [
        'marks' => 'decimal:2',
        'negative_marks' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function examCategory(): BelongsTo
    {
        return $this->belongsTo(ExamCategory::class);
    }

    public function mockTests(): BelongsToMany
    {
        return $this->belongsToMany(MockTest::class, 'mock_test_questions')
            ->withPivot('sort_order')
            ->withTimestamps();
    }
}
