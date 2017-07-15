<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model {

  public function student(){
      return $this->belongsTo('App\Student');
  }

  public function userwifi(){
      return $this->belongsTo('App\UserWifi');
  }

  public function manager(){
      return $this->belongsTo('App\Manager');
  }
  //use Authenticatable, CanResetPassword;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'bills';
  public $timestamps = false;



  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   protected $fillable = ['idstudent', 'userwifiname', 'managername', 'datebuy', 'dateexpired', 'expired', 'datecanrefund', 'refund', 'daterefund'];



}
