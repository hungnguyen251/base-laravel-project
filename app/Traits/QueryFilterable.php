<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\QueryBuilder\Filters\FiltersExactOrNotExact;

trait QueryFilterable
{
    /**
     * Scope a query for query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, Request $request): Builder
    {
        $filters = [];

        if ($this->filterable != null && count($this->filterable) > 0) {
            $filters = array_merge($this->filterable, $filters);
        }

        if ($this->exactFilterable != null && count($this->exactFilterable) > 0) {
            $exactFilters = [];
            foreach($this->exactFilterable as $ef) {
                $exactFilters[] = AllowedFilter::custom($ef,  new FiltersExactOrNotExact(true));
            }

            $filters = array_merge($exactFilters, $filters);
        }

        if ($this->scopeFilterable != null && count($this->scopeFilterable) > 0) {
            $scopeFilters = [];
            foreach($this->scopeFilterable as $sf) {
                $scopeFilters[] = AllowedFilter::scope($sf);
            }

            $filters = array_merge($scopeFilters, $filters);
        }

        (new QueryBuilder($query, $request))
            ->allowedFilters($filters)
            ->allowedSorts($this->sortable ?? [])
            ->allowedFields($this->visible ?? [])
            ->allowedIncludes($this->includable ?? [])
            ->allowedAppends($this->appendable ?? []);

        return $query;
    }
}
