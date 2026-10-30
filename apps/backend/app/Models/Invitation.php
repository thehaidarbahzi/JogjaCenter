<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table("invitations", keyType: "string", incrementing: false)]
class Invitation extends Model
{
    use LogsActivity;

    protected $fillable = [
        "id",
        "email",
        "invited_by",
        "role_id",
        "expire_at",
        "accepted_at",
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("invitations")
            ->logFillable()
            ->logExcept(["id"])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(function(string $eventName): string {
                return match($eventName) {
                    'created' => 'Create Invitation',
                    'updated' => 'Update Invitation',
                    'deleted' => 'Delete Invitation',
                    default => $eventName,
                };
            });
    }
}
