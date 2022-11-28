<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  use HasFactory;

  protected $primaryKey = 'gro_id';

  protected $fillable = [
    'gro_name',
    'gro_status'
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function modules()
  {
    return $this->belongsToMany(Modules::class, 'group_modules', 'fk_groups_id', 'fk_modules_id');
  }

  public function users()
  {
    return $this->hasMany(User::class, 'fk_groups_id', 'gro_id');
  }
}
