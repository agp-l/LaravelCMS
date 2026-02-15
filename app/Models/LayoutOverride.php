<?php

// app/Models/LayoutOverride.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayoutOverride extends Model
{
    protected $fillable = ['path_pattern', 'layout'];
}
