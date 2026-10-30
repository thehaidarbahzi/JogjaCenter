<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table("user_roles")]
class UserRole extends Model
{
    use LogsActivity;

    protected $fillable = ["user_id", "role_id"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("user_roles")
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(function(string $eventName): string {

                return match($eventName) {
                    'created' => 'Create User Role',
                    'updated' => 'Update User Role',
                    'deleted' => 'Delete User Role',
                    default => $eventName,    };

            });
    }
}
