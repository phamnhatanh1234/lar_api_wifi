<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model {

  public function usertypes(){
    return $this->hasMany('App\UserType');
  }
//  use Authenticatable, CanResetPassword;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'devices';
  public $timestamps = true;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['Id', 'Name'];

}
