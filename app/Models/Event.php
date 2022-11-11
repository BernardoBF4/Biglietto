<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  use HasFactory;

  protected $fillable = [
    'status',
    'title',
    'start_datetime',
    'end_datetime',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];
}
