<?php

namespace Spatie\Activitylog;

use Jenssegers\Mongodb\Eloquent\Model;

class EventLogBag
{
    public function __construct(
        public string $event,
        public Model $model,
        public array $changes,
        public ?LogOptions $options = null
    ) {
        $this->options ??= $model->getActivitylogOptions();
    }
}
