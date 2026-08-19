<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmApplicant extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jobPost()
    {
        return $this->belongsTo(HrmJobPost::class, 'job_post_id');
    }
}
