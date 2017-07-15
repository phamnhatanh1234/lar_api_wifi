<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;


use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
class Manager extends Model implements AuthenticatableContract, CanResetPasswordContract {

  public function bills(){
      return $this->hasMany('App\Bill');
  }

  public function role(){
      return $this->belongsTo('App\Role');
  }
  use Authenticatable, CanResetPassword;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'managers';
  public $timestamps = false;



  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   protected $fillable = ['Username', 'Name', 'IdRole', 'CanUse'];

   protected $hidden = [ 'Password','remember_token'];

}
