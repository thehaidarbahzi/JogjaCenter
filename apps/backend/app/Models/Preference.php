<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table("preferences")]
class Preference extends Model
{
    use LogsActivity;

    protected $fillable = ["key", "value"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("preferences")
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->tap(function($activity) {
                $activity->description = match($activity->event) {
                    'created' => 'Create Preference',
                    'updated' => 'Update Preference',
                    'deleted' => 'Delete Preference',
                    default => $activity->description,
                };
            });
    }
}
