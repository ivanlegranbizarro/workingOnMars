<?php

namespace App\Models;

use App\Models\Job;
use App\Models\Candidate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the job that owns the Application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the candidate that owns the Application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
