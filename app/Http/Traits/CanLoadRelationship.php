<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentModel;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

trait CanLoadRelationship
{
    public function loadRelationship(
        Model|QueryBuilder|EloquentModel $for,
        ?array $relations = null,
    ): Model|QueryBuilder|EloquentModel
    {
        $relations ??= $this->relations ?? [];
        foreach($relations as $relation) {
            $for->when(
              $this->shouldIncludeRelation($relation),
              fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }
        return $for;
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');
        if(!$include) {
            return false;
        }
        $relations = array_map('trim',explode(',', $include));
        return in_array($relation, $relations);
    }
}
