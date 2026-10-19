<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table("roles")]
class Role extends Model
{
    use LogsActivity;

    protected $fillable = ["name"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("roles")
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->tap(function($activity) {
                $activity->description = match($activity->event) {
                    'created' => 'Create Role',
                    'updated' => 'Update Role',
                    'deleted' => 'Delete Role',
                    default => $activity->description,
                };
            });
    }
}
