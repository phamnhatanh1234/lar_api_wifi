<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

	//  $this->call('DeviceTableSeeder');
	// 	$this->call('UserTypeTableSeeder');
	 //
	// 	$this->call('StudentTableSeeder');
	// 	 $this->call('RoleTableSeeder');
	// 	$this->call('PriceUserTypeTableSeeder');
	// 	$this->call('ManagerTableSeeder');
	// 	$this->call('UserWifiTableSeeder');
		$this->call('BillTableSeeder');


	}

}


class DeviceTableSeeder extends Seeder {

	public function run()
	{
		DB::table('devices')->insert([
			array('name' => 'Mobile'),
			array('name' => 'Laptop')
		]);
	}

}


class UserTypeTableSeeder extends Seeder {

	public function run()
	{
		DB::table('usertypes')->insert([
			array('name' => '7 ngày', 'duration' => 7, 'iddevice'=> 1, 'dateupdateprice' => '2017-03-25'),
			array('name' => '14 ngày', 'duration' => 14,  'iddevice'=> 1,'dateupdateprice' => '2017-03-25'),
			array('name' => '1 tháng', 'duration' => 30,  'iddevice'=> 1,'dateupdateprice' => '2017-03-23'),
			array('name' => '2 tháng', 'duration' => 60,  'iddevice'=> 1,'dateupdateprice' => '2017-03-27'),
			array('name' => '3 tháng', 'duration' => 90,  'iddevice'=> 1,'dateupdateprice' => '2017-03-23'),
			array('name' => '4 tháng', 'duration' => 120,  'iddevice'=> 1,'dateupdateprice' => '2017-03-23'),
			array('name' => '5 tháng', 'duration' => 150,  'iddevice'=> 1,'dateupdateprice' => '2017-03-23'),
			array('name' => '6 tháng', 'duration' => 180,  'iddevice'=> 1,'dateupdateprice' => '2017-03-23'),
				array('name' => '7 ngày', 'duration' => 7,  'iddevice'=> 2,'dateupdateprice' => '2017-03-23'),
				array('name' => '14 ngày', 'duration' => 14, 'iddevice'=> 2,'dateupdateprice' => '2017-03-23'),
				array('name' => '1 tháng', 'duration' => 30,  'iddevice'=> 2,'dateupdateprice' => '2017-03-23'),
				array('name' => '2 tháng', 'duration' => 60,  'iddevice'=> 2,'dateupdateprice' => '2017-03-23'),
				array('name' => '3 tháng', 'duration' => 90,  'iddevice'=> 2,'dateupdateprice' => '2017-03-23'),
				array('name' => '4 tháng', 'duration' => 120, 'iddevice'=> 2,'dateupdateprice' => '2017-03-23'),
				array('name' => '5 tháng', 'duration' => 150,  'iddevice'=> 2,'dateupdateprice' => '2017-03-23'),
				array('name' => '6 tháng', 'duration' => 180,  'iddevice'=> 2,'dateupdateprice' => '2017-03-23')
		]);
	}
}

class PriceUserTypeTableSeeder extends Seeder {

	public function run()
	{
		DB::table('priceusertypes')->insert([
			array('price' => 10000, 'dateupdate' => '2017-03-23', 'idusertype'=> 1),
			array('price' => 20000, 'dateupdate' => '2017-03-25', 'idusertype'=> 1),
			array('price' => 20000, 'dateupdate' => '2017-03-23', 'idusertype'=> 2),
			array('price' => 30000, 'dateupdate' => '2017-03-27', 'idusertype'=> 2),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 3),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 4),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 5),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 6),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 7),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 8),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 9),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 10),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 11),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 12),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 13),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 14),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 15),
			array('price' => 50000, 'dateupdate' => '2017-03-23', 'idusertype'=> 16)

		]);
	}
}






 class StudentTableSeeder extends Seeder {

 	public function run()
 	{
 		DB::table('students')->insert([
 			array('masv' => '312022141103','name' => 'Phạm Nguyễn Nhật Anh', 'quequan' => 'Huế', 'phong'=> '203', 'nha'=>'1'),
			array('masv' => '312022141103','name' => 'Đỗ Lê Tuấn Thanh', 'quequan' => 'Cần Thơ', 'phong'=> '203', 'nha'=>'1'),
			array('masv' => '312022141103','name' => 'Nguyễn Duy Bão', 'quequan' => 'Cùng quê Master Yi', 'phong'=> '203', 'nha'=>'1'),
			array('masv' => '312022141103','name' => 'Nguyễn Đỗ Quang Linh', 'quequan' => 'Đà Nẵng', 'phong'=> '203', 'nha'=>'1'),
			array('masv' => '312022141103','name' => 'Sử Minh Thành', 'quequan' => 'Huế', 'phong'=> '204', 'nha'=>'2'),
			array('masv' => '312022141103','name' => 'Huỳnh Thể Quyên', 'quequan' => 'Tam Kỳ', 'phong'=> '205', 'nha'=>'1')

 		]);
 	}
 }


 class RoleTableSeeder extends Seeder {

	 public function run()
	 {
		 DB::table('roles')->insert([
			 array('name' => 'Moderator'),
			 array('name' => 'Admin')

		 ]);
	 }
 }


 class ManagerTableSeeder extends Seeder {

	public function run()
	{
		DB::table('managers')->insert([
			array('username' => 'kabi1234','password' => '123456','name' => 'Phạm Nguyễn Nhật Anh', 'idrole' => 2, 'canuse' => 1),
			array('username' => 'smthanh','password' => '123456','name' => 'Sử Minh Thành', 'idrole' => 2, 'canuse' => 1),
			array('username' => 'nntuyen','password' => '123456','name' => 'Nguyễn Ngọc Tuyên', 'idrole' => 1, 'canuse' => 1),
			array('username' => 'quangtri','password' => '123456','name' => 'Nguyễn Quang Trí', 'idrole' => 1, 'canuse' => 1),
			array('username' => 'photo','password' => '123456','name' => 'Photo KTX', 'idrole' => 1, 'canuse' => 1)
		]);
	}
 }


 class UserWifiTableSeeder extends Seeder {

	public function run()
	{
		DB::table('userwifis')->insert([
			array('username' => 'abc123','password' => '123456','idpricetype' => 1  ),
			array('username' => 'abc124','password' => '123456','idpricetype' => 2  ),
			array('username' => 'abc125','password' => '123456','idpricetype' => 3  ),
			array('username' => 'abc126','password' => '123456','idpricetype' => 4 ),
			array('username' => 'abc127','password' => '123456','idpricetype' => 5),
			array('username' => 'abc128','password' => '123456','idpricetype' => 6)
		]);
	}
 }





class BillTableSeeder extends Seeder {

 public function run()
 {
	 DB::table('bills')->insert([
		array('idstudent' => '1','userwifiname' => 'abc123','managername' => 'kabi1234',  'datebuy' => '2017-06-23', 'dateexpired' => '2017-07-23', 'expired' => 0),
		array('idstudent' => '2','userwifiname' => 'abc124','managername' => 'kabi1234',  'datebuy' => '2017-06-23', 'dateexpired' =>'2017-07-23','expired' => 0),
		array('idstudent' => '3','userwifiname' => 'abc125','managername' => 'smthanh',  'datebuy' => '2017-06-23', 'dateexpired' =>'2017-07-23','expired' => 0),
		array('idstudent' => '4','userwifiname' => 'abc126','managername' => 'nntuyen',  'datebuy' => '2017-06-23', 'dateexpired' =>'2017-07-23','expired' => 0)
	 ]);
 }
}
