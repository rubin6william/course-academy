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
        return $this->belongsToMany(Course::class, 'enrolments');
    }
}
