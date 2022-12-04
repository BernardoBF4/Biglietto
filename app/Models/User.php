<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  protected $primaryKey = 'usu_id';

  protected $fillable = [
    'usu_email',
    'usu_name',
    'usu_password',
    'usu_token',
    'fk_groups_id',
  ];

  protected $hidden = [
    'usu_password',
    'usu_token',
  ];

  public function group()
  {
    return $this->belongsTo(Group::class, 'fk_groups_id', 'gro_id');
  }
}
