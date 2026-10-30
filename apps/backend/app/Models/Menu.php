<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table("menus")]
class Menu extends Model
{
    use LogsActivity;

    protected $fillable = ["name", "description", "visibility"];

    public function contents(): HasMany
    {
        return $this->hasMany(MenuContent::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("menus")
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(function(string $eventName): string {

                return match($eventName) {
                    'created' => 'Create Menu',
                    'updated' => 'Update Menu',
                    'deleted' => 'Delete Menu',
                    default => $eventName,    };

            });
    }
}
