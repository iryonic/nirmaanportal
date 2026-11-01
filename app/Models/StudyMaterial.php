<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class StudyMaterial extends Model
{
    use HasFactory;

    public const TYPE_SYLLABUS = 'syllabus';
    public const TYPE_PDF = 'pdf';
    public const TYPE_PREVIOUS_PAPER = 'previous_paper';
    public const TYPE_FORMULA_SHEET = 'formula_sheet';
    public const TYPE_CURRENT_AFFAIRS = 'current_affairs';

    public static function getTypes(): array
    {
        return [
            self::TYPE_SYLLABUS => 'Official Syllabus',
            self::TYPE_PDF => 'Revision Notes / PDF',
            self::TYPE_PREVIOUS_PAPER => 'Previous Year Solved Paper',
            self::TYPE_FORMULA_SHEET => 'Formula & Quick Revision Sheet',
            self::TYPE_CURRENT_AFFAIRS => 'Defence Current Affairs & Digest',
        ];
    }

    protected $fillable = [
        'exam_category_id',
        'title',
        'slug',
        'type',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'external_url',
        'is_free',
        'is_published',
        'is_featured',
        'downloads_count',
        'created_by',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'downloads_count' => 'integer',
    ];

    public function examCategory(): BelongsTo
    {
        return $this->belongsTo(ExamCategory::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeNameAttribute(): string
    {
        $types = self::getTypes();
        return $types[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }

    public function hasFile(): bool
    {
        return !empty($this->file_path) && Storage::disk('public')->exists($this->file_path);
    }

    public function getFileUrl(): ?string
    {
        if ($this->hasFile()) {
            return Storage::disk('public')->url($this->file_path);
        }

        return $this->external_url;
    }

    public function getFileExtensionAttribute(): string
    {
        if ($this->file_name) {
            return strtoupper(pathinfo($this->file_name, PATHINFO_EXTENSION) ?: 'PDF');
        }

        if ($this->file_path) {
            return strtoupper(pathinfo($this->file_path, PATHINFO_EXTENSION) ?: 'PDF');
        }

        if ($this->external_url) {
            return 'LINK';
        }

        return 'PDF';
    }
}
