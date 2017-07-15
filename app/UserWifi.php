<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Auth\Authenticatable;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
class UserWifi extends Model {


  use Authenticatable, CanResetPassword;
  public function bills(){
    return $this->hasMany('App\Bill');
  }
  public function usertype(){
    	return $this->belongsTo('App\PriceUserType');
	}


  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'userwifis';
  public $timestamps = true;


  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['username','Password', 'idpricetype'];

}
