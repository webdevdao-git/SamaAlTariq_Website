<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'location',
        'status',
        'progress',
        'start_date',
        'due_date',
        'project_type',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
            'progress' => 'integer',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ProjectUpdate::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(ProjectStage::class);
    }

    /**
     * The old Postgres `projects_select` policy, as a query scope: an admin sees
     * everything, a client sees only their own live projects.
     *
     * Policies gate access to a record you already loaded; this stops the wrong
     * records being loaded at all. Both are needed — a policy cannot filter an
     * index query, and a scope cannot guard a route-model-bound show page.
     */
    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if ($user?->isAdmin()) {
            return $query;
        }

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('client_id', $user->id);
    }
}
