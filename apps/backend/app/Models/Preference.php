<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table("preferences")]
class Preference extends Model
{
    use LogsActivity;

    protected $fillable = ["key", "value", "type"];

    protected $appends = ["url"];

    public function getUrlAttribute(): ?string
    {
        if ($this->type !== "image" || !$this->value) {
            return null;
        }

        if (Str::startsWith($this->value, ["http://", "https://", "/"])) {
            return $this->value;
        }

        return Storage::disk("public")->url($this->value);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("preferences")
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(function(string $eventName): string {

                return match($eventName) {
                    'created' => 'Create Preference',
                    'updated' => 'Update Preference',
                    'deleted' => 'Delete Preference',
                    default => $eventName,    };

            });
    }
}
