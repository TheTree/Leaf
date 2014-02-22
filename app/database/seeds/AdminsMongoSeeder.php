<?php

use Users\Admins as Admins;

class AdminsMongoSeeder extends Seeder {

	public function run()
	{
		DB::collection('admins')->delete();

		Admins::create([
				'Name' => 'Connor Tumbleson',
				'Email' => 'connor.tumbleson@gmail.com',
				'PasswordHash' => Hash::make('password')
			]);
	}
}