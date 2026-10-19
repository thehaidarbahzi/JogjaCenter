<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table("supersets")]
class Superset extends Model
{
    use LogsActivity;

    protected $fillable = ["host", "username", "password"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("supersets")
            ->logFillable()
            ->logExcept(["password"])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->tap(function($activity) {
                $activity->description = match($activity->event) {
                    'created' => 'Create Superset',
                    'updated' => 'Update Superset',
                    'deleted' => 'Delete Superset',
                    default => $activity->description,
                };
            });
    }
}
