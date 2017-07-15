<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
class PriceUserType extends Model {

  public function userwifis(){
      return $this->hasMany('App\UserWifi');
  }

  public function usertype(){
    	return $this->belongsTo('App\UserType');
	}
  //use Authenticatable, CanResetPassword;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'priceusertypes';
  public $timestamps = false;


  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   protected $fillable = ['Id', 'Price', 'DateUpdate', 'IdUserType' ];

}
