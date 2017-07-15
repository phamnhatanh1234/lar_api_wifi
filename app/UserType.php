<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
class UserType extends Model {

  public function priceusertypes(){
    return $this->hasMany('App\PriceUserType');
  }
  use Authenticatable, CanResetPassword;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'usertypes';
  public $timestamps = true;


  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['Id', 'Name', 'Duration','DateUpdatePrice'];

}
