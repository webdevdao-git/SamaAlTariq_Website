<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDocument extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectDocumentFactory> */
    use HasFactory;

    protected $fillable = ['project_id', 'storage_path', 'name'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
