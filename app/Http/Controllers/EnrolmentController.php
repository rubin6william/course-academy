<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\ReportBuilders\EnrolmentReportBuilder;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrolmentController
{
    /**
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function list(Request $request): JsonResponse
    {
        $page = $request->get('current_page',1);
        $perPage = $request->get('per_page', 10);
        $sortBy = $request->get('sort_by', 'user');
        $sortOrder = $request->get('sort_order', 'desc');
        $filters = $request->get('filters', []);

        $reportBuilder = (new EnrolmentReportBuilder())
            ->filters($filters)
            ->orderBy($sortBy, $sortOrder);

        $counts = $reportBuilder
            ->counts();

        $results = $reportBuilder
            ->paginate($page, $perPage);

        tap($results, function($paginatedInstance) {
            $paginatedInstance
                ->getCollection()
                ->transform(function ($value) {
                    $value->completion_status_label = Course::STATES[$value->completion_status];
                    return $value;
                });
        });

        return response()->json($results->toArray() + ['counts' => $counts]);
    }
}
