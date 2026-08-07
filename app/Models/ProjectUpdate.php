<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectUpdate extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectUpdateFactory> */
    use HasFactory;

    protected $fillable = ['project_id', 'note'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
