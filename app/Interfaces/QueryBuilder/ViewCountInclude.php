<?php

namespace App\Interfaces\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Includes\IncludeInterface;

class ViewCountInclude implements IncludeInterface
{
    /**
     * Invoke the query builder.
     *
     * @param Builder $query
     * @param string $include
     * @return Builder
     */
    public function __invoke(Builder $query, string $include)
    {
        return $query->withCount('visits');
    }
}
