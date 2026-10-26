<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_attempt_id',
        'question_id',
        'selected_option',
        'is_correct',
        'marks_obtained',
        'is_marked_for_review',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'marks_obtained' => 'decimal:2',
        'is_marked_for_review' => 'boolean',
    ];

    public function testAttempt(): BelongsTo
    {
        return $this->belongsTo(TestAttempt::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
