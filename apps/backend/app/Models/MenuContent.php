<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table("menu_contents")]
class MenuContent extends Model
{
    use LogsActivity;

    protected $fillable = ["name", "description", "superset_id", "visibility"];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("menu_contents")
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->tap(function($activity) {
                $activity->description = match($activity->event) {
                    'created' => 'Create Menu Content',
                    'updated' => 'Update Menu Content',
                    'deleted' => 'Delete Menu Content',
                    default => $activity->description,
                };
            });
    }
}
