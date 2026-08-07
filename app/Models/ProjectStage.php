<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectStage extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectStageFactory> */
    use HasFactory;

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
