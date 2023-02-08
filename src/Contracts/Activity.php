<?php

namespace Spatie\Activitylog\Contracts;

use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\MorphTo;
use Illuminate\Support\Collection;

interface Activity
{
    public function subject(): MorphTo;

    public function causer(): MorphTo;

    public function getExtraProperty(string $propertyName, mixed $defaultValue): mixed;

    public function changes(): Collection;

    public function scopeInLog(Builder $query, ...$logNames): Builder;

    public function scopeCausedBy(Builder $query, Model $causer): Builder;

    public function scopeForEvent(Builder $query, string $event): Builder;

    public function scopeForSubject(Builder $query, Model $subject): Builder;
}
