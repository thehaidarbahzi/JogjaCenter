<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table("supersets")]
class Superset extends Model
{
    protected $fillable = ["host", "username", "password"];
}
