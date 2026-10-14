<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table("menu_contents")]
class MenuContent extends Model
{
    protected $fillable = ["name", "description", "superset_id", "visibility"];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
