<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table("user_resets", keyType: "string", incrementing: false)]
class UserReset extends Model
{
    use LogsActivity;

    protected $fillable = ["id", "user_id", "token", "code", "expire_at"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("user_resets")
            ->logFillable()
            ->logExcept(["id", "token", "code"])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->tap(function($activity) {
                $activity->description = match($activity->event) {
                    'created' => 'Create User Reset',
                    'updated' => 'Update User Reset',
                    'deleted' => 'Delete User Reset',
                    default => $activity->description,
                };
            });
    }
}
