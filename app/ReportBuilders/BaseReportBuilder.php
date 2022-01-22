<?php

namespace App\ReportBuilders;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseReportBuilder
 * @package App\ReportBuilders
 */
abstract class BaseReportBuilder
{
    /**
     * @var Builder
     */
    protected Builder $queryBuilder;

    /**
     * @var array
     */
    protected array $columns = [];

    /**
     * @var array
     */
    protected array $columnsToReplace = [];

    /**
     * @return $this
     * @throws Exception
     */
    public function filters(array $values): static
    {
        foreach($values as $column => $value) {
            $searchable = $this->columns[$column]['searchable'] ?? false;

            if (!array_key_exists($column, $this->columns) || !$searchable) {
                throw new Exception('Filter column ' . $column . ' not allowed');
            }

            $this->queryBuilder->where(DB::raw($this->columns[$column]['key']), 'like', "%$value%");
        }

        return $this;
    }

    /**
     * @param string $sortBy
     * @param string $sortOrder
     * @return $this
     */
    public function orderBy(string $sortBy, string $sortOrder): static
    {
        $this->queryBuilder->orderBy($sortBy, $sortOrder);

        return $this;
    }

    /**
     * @param  int  $page
     * @param  int  $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $page, int $perPage, array $columns = []): LengthAwarePaginator
    {
        $columns = count($columns) ?
            $columns :
            $this->defaultColumns();

        $cloned = clone $this->queryBuilder;

        return $cloned
            ->selectRaw(implode(',', $columns))
            ->paginate($perPage, [], 'page', $page);
    }

    /**
     * @return array
     */
    protected function defaultColumns(): array
    {
        $formatted = [];

        foreach($this->columns as $column => $properties) {
            $formatted[] = in_array($column, $this->columnsToReplace) ?
                "{$properties['key']} as $column" :
                $column;
        }

        return $formatted;
    }
}
