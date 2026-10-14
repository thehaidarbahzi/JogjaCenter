<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table("menus")]
class Menu extends Model
{
    protected $fillable = ["name", "description", "visibility"];

    public function contents(): HasMany
    {
        return $this->hasMany(MenuContent::class);
    }
}
