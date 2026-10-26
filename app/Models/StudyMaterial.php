<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_category_id',
        'title',
        'slug',
        'type',
        'description',
        'file_path',
        'file_size',
        'is_free',
        'downloads_count',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'downloads_count' => 'integer',
    ];

    public function examCategory(): BelongsTo
    {
        return $this->belongsTo(ExamCategory::class);
    }
}
