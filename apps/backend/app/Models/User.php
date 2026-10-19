<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table("users")]
class User extends Authenticatable
{
    use HasApiTokens, LogsActivity, SoftDeletes;

    protected $fillable = ["email", "fullname", "password"];

    protected $hidden = ["password"];

    protected $casts = [
        "password" => "hashed",
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, "user_roles");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName("users")
            ->logFillable()
            ->logExcept(["password"])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->tap(function($activity) {
                $activity->description = match($activity->event) {
                    'created' => 'Create User',
                    'updated' => 'Update User',
                    'deleted' => 'Delete User',
                    default => $activity->description,
                };
            });
    }
}
