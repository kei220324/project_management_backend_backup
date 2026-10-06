<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'status',
        'name',
        'description',
        'is_done',
        'due_date',
    ];

    // JSONに含めたい計算結果
    protected $appends = [
        'progress_percent',
    ];

    /**
     * このタスクが属しているプロジェクト
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

    /**
     * このタスクに属しているチェック項目
     */
    public function checkItems(): HasMany
    {
        return $this->hasMany(TaskCheckItem::class);
    }

    /**
     * チェック項目数と完了チェック項目数を取得
     */
    public function scopeWithCheckItemStats(Builder $taskQuery): Builder
    {
        return $taskQuery
            ->withCount('checkItems')
            ->withCount([
                'checkItems as completed_check_items_count' => fn (Builder $checkItemQuery) =>
                    $checkItemQuery->where('is_done', true),
            ]);
    }

    /**
     * 進捗率（0〜100）
     */
    public function getProgressPercentAttribute(): int
    {
        $checkItemsCount = (int) ($this->check_items_count ?? 0);
        $completedCount = (int) ($this->completed_check_items_count ?? 0);

        if ($checkItemsCount === 0) {
            return 0;
        }

        return (int) floor(($completedCount / $checkItemsCount) * 100);
    }
}