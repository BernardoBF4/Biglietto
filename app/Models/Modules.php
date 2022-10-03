<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'status',
    'father_id',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];
}
