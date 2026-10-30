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
            ->setDescriptionForEvent(function(string $eventName): string {
                return match($eventName) {
                    'created' => 'Create Superset',
                    'updated' => 'Update Superset',
                    'deleted' => 'Delete Superset',
                    default => $eventName,
                };
            });
    }
}
