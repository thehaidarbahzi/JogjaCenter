<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table("preferences")]
class Preference extends Model
{
    protected $fillable = ["key", "value"];
}
