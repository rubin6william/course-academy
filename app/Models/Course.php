<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Course
 * @package App\Models
 */
class Course extends Model
{
    use HasFactory;

    const STATES = [
        'not_started' => 'Not Started',
        'in_progress' => 'In Progress',
        'completed' => 'Completed'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'description'
    ];

    /**
     * @return BelongsToMany
     */
    public function enrolments(): BelongsToMany {
        return $this
            ->belongsToMany(User::class, 'enrolments')
            ->withPivot(['completion_status']);
    }
}
