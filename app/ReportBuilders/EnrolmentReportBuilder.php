<?php

namespace App\ReportBuilders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class EnrolmentReportBuilder
 * @package App\ReportBuilders
 */
class EnrolmentReportBuilder extends BaseReportBuilder
{
    /**
     * @var array
     */
    protected array $columns = [
        'user' => [
            'key' => 'concat(u.first_name, \' \', u.last_name)',
            'searchable' => true,
        ],
        'course' => [
            'key' => 'c.description',
            'searchable' => true,
        ],
        'completion_status' => [
            'key' => 'e.completion_status',
            'searchable' => true,
        ]
    ];

    /**
     * @var array
     */
    protected array $columnsToReplace = [
        'user',
        'course'
    ];

    public function __construct()
    {
        $this->queryBuilder = DB::table('enrolments', 'e')
            ->join('users as u', 'e.user_id', '=', 'u.id')
            ->join('courses as c', 'e.course_id', '=', 'c.id');
    }

    /**
     * @return array
     */
    public function counts(): array
    {
        $totalUsers = 'count(distinct(e.user_id)) as users';
        $totalCourses = 'count(distinct(e.course_id)) as courses';

        $totalInProgress = 'sum(if(e.completion_status = \'in_progress\', 1, 0)) as in_progress';
        $totalNotStarted = 'sum(if(e.completion_status = \'not_started\', 1, 0)) as not_started';
        $totalCompleted = 'sum(if(e.completion_status = \'completed\', 1, 0)) as completed';

        $cloned = clone $this->queryBuilder;
        $cloned->orders = [];

        return (array) $cloned
            ->selectRaw("$totalUsers, $totalCourses, $totalInProgress, $totalNotStarted, $totalCompleted")
            ->first();
    }
}
