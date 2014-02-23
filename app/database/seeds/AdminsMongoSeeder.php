<?php

use Users\Admin as Admin;

class AdminsMongoSeeder extends Seeder {

	public function run()
	{
		DB::collection('admins')->delete();

		Admin::create([
				'mame' => 'Connor Tumbleson',
				'email' => 'connor.tumbleson@gmail.com',
				'password' => Hash::make('password')
			]);
	}
}