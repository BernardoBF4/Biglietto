<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  use HasFactory;

  protected $primaryKey = 'eve_id';

  protected $fillable = [
    'eve_end_datetime',
    'eve_start_datetime',
    'eve_status',
    'eve_title',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];
}
