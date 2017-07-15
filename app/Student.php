<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
class Student extends Model {

  public function bills(){
    return $this->hasMany('App\Bill');
  }
  //use Authenticatable, CanResetPassword;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'students';
  public $timestamps = true;


  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['Id','MaSV', 'Name', 'Que Quan', 'Phong', 'Nha', 'Sdt'];

}
