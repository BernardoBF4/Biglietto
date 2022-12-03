<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
  use HasFactory;

  protected $primaryKey = 'lot_id';

  protected $fillable = [
    'lot_status',
    'lot_price',
    'fk_tickets_id',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function ticket()
  {
    return $this->belongsTo(Ticket::class, 'fk_tickets_id', 'tic_id');
  }
}
