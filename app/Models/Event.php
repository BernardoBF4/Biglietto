<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  use HasFactory;

  protected $fillable = [
    'end_datetime',
    'start_datetime',
    'status',
    'title',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];
}
