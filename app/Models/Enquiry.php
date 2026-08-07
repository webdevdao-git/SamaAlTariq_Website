<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    /** @use HasFactory<\Database\Factories\EnquiryFactory> */
    use HasFactory;

    protected $table = 'enquiries';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'location',
        'project_type',
        'project_brief',
        'ip',
        'user_agent',
        'status',
    ];
}
