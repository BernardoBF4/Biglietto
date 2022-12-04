<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
  use HasFactory;

  protected $primaryKey = 'tic_id';

  protected $fillable = [
    'tic_price',
    'tic_status',
    'tic_title',
    'fk_events_id',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function event()
  {
    return $this->belongsTo(Event::class, 'fk_events_id', 'eve_id');
  }

  public function lots()
  {
    return $this->hasMany(Lot::class, 'fk_tickets_id', 'tic_id');
  }
}
