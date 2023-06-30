<?php

namespace App\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Filters\FiltersExact;

class FiltersExactOrNotExact extends FiltersExact
{
    const DEFAULT_NEGATIVE_CHAR = '!';
    protected string $prefix = self::DEFAULT_NEGATIVE_CHAR;


    public function __construct(bool $addRelationConstraint = true, string $prefix = self::DEFAULT_NEGATIVE_CHAR)
    {
        $this->addRelationConstraint = $addRelationConstraint;
        $this->prefix = $prefix;
    }

    public function __invoke(Builder $rootQuery, $value, string $property)
    {
        $rootQuery->where(function ($query) use ($property, $value) {
            $properties = [];
            $values = [];

            if (strpos($property, "|") !== false) {
                $properties = explode("|", $property);
            } else {
                $properties[] = $property;
            }

            $propertyCount = count($properties);
            if($propertyCount > 1) {
                if (is_array($value)) {
                    return;
                } else {
                    if (strpos($value, "|") !== false) {
                        $values = explode("|", $value);
                    } else {
                        $values[] = $value;
                    }
                }
            } else {
                $values[] = $value;
            }

            if ($propertyCount == count($values)) {
                for($i = 0; $i < $propertyCount; $i++) {
                    $subProperty = $properties[$i];
                    $subValue = $values[$i];

                    if ($this->addRelationConstraint) {
                        if ($this->isRelationProperty($query, $subProperty)) {
                            $this->withRelationConstraint($query, $subValue, $subProperty);
                            continue;
                        }
                    }
            
                    if (is_array($subValue)) {
                        // If our first value is prefixed with a negative key, treat as Not Exact
                        if (count($subValue) > 0 && $this->isNegative($subValue[0])) {
                            // Sanitise the entire array, all of our values could be prefixed
                            $subValue = array_map(fn ($subValue) => $this->sanitise($subValue), $subValue);
                            if($propertyCount > 1 && $i > 0) {
                                $query->orWhere(function ($subQuery) use ($subProperty, $subValue) {
                                    $subQuery->whereNotIn($subQuery->qualifyColumn($subProperty), $subValue);
                                });
                            } else {
                                $query->whereNotIn($query->qualifyColumn($subProperty), $subValue);
                            }
                            continue;
                        }
            
                        if($propertyCount > 1 && $i > 0) {
                            $query->orWhere(function ($subQuery) use ($subProperty, $subValue) {
                                $subQuery->whereIn($subQuery->qualifyColumn($subProperty), $subValue);
                            });
                        } else {
                            $query->whereIn($query->qualifyColumn($subProperty), $subValue);
                        }
                        continue;
                    }
            
                    if ($this->isNegative($subValue)) {
                        if ("null" == strtolower($subValue)) {
                            if($propertyCount > 1 && $i > 0) {
                                $query->orWhere(function ($subQuery) use ($subProperty) {
                                    $subQuery->whereNotNull($subQuery->qualifyColumn($subProperty));
                                });
                            } else {
                                $query->whereNotNull($query->qualifyColumn($subProperty));
                            }
                        } else {
                            if($propertyCount > 1 && $i > 0) {
                                $query->orWhere(function ($subQuery) use ($subProperty, $subValue) {
                                    $subQuery->where($subQuery->qualifyColumn($subProperty), '!=', $this->sanitise($subValue));
                                });
                            } else {
                                $query->where($query->qualifyColumn($subProperty), '!=', $this->sanitise($subValue));
                            }
                        }
                        continue;
                    }
            
                    if ("null" == strtolower($subValue)) {
                        if($propertyCount > 1 && $i > 0) {
                            $query->orWhere(function ($subQuery) use ($subProperty) {
                                $subQuery->whereNull($subQuery->qualifyColumn($subProperty));
                            });
                        } else {
                            $query->whereNull($query->qualifyColumn($subProperty));
                        }
                    } else {
                        if($propertyCount > 1 && $i > 0) {
                            $query->orWhere(function ($subQuery) use ($subProperty, $subValue) {
                                $subQuery->where($subQuery->qualifyColumn($subProperty), '=', $subValue);
                            });
                        } else {
                            $query->where($query->qualifyColumn($subProperty), '=', $subValue);
                        }
                    }
                }
            }
        }); 
    }

    protected function isNegative(string $value): bool
    {
        return Str::startsWith($value, $this->prefix);
    }

    protected function sanitise(string $value): string
    {
        return Str::after($value, $this->prefix);
    }
}