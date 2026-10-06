<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'summary',
        'due_date',
    ];

    // JSONに含めたい計算結果（progress_percent / status）
    protected $appends = [
        'progress_percent',
        'status',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

     public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * tasks_count と done_tasks_count を付与するスコープ
     */
    public function scopeWithTaskStats(Builder $projectQuery): Builder
    {
        return $projectQuery
            ->withCount('tasks')
            ->withCount([
                'tasks as done_tasks_count' => fn (Builder $taskQuery) =>
                    $taskQuery->where('is_done', true),
            ]);
    }

    /**
     * 進捗率（0〜100）
     */
    public function getProgressPercentAttribute(): int
    {
        $tasksCount = (int) ($this->tasks_count ?? 0);
        $doneCount  = (int) ($this->done_tasks_count ?? 0);

        if ($tasksCount === 0) {
            return 0;
        }

        return (int) floor(($doneCount / $tasksCount) * 100);
    }

    /**
     * 状態（todo / doing / done）
     */
    public function getStatusAttribute(): string
    {
        $tasksCount = (int) ($this->tasks_count ?? 0);
        $progress   = $this->progress_percent;

        return match (true) {
            $tasksCount === 0 => 'todo',
            $progress >= 100  => 'done',
            default           => 'doing',
        };
    }
}