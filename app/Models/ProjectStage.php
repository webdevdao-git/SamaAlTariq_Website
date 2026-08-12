<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectStage extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectStageFactory> */
    use HasFactory;

    /**
     * The states a stage can be in, in the order they progress.
     *
     * This mirrors the `status` enum on the table, and is what both the admin
     * form's options and its validation rule are built from — so the column,
     * the menu and the rule cannot drift apart. Changing it needs a migration
     * to match; rows already stored keep whatever they were saved with.
     */
    public const STATUSES = ['Pending', 'In Progress', 'Completed'];

    protected $fillable = ['project_id', 'name', 'status', 'target_date', 'sort_order'];

    protected function casts(): array
    {
        return ['target_date' => 'date', 'sort_order' => 'integer'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
