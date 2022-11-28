<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
  use HasFactory;

  protected $primaryKey = 'tic_id';

  protected $fillable = [
    'fk_events_id',
    'tic_price',
    'tic_status',
    'tic_title',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function event()
  {
    return $this->belongsTo(Event::class, 'fk_events_id', 'eve_id');
  }
}
