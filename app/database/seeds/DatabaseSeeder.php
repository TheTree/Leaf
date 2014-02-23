<?php

use Illuminate\Support\Facades\Log;
use Jenssegers\Mongodb\Model as Eloquent;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// @todo investigate while Log:: methods won't show up in CLI
		if (App::environment() == "testing")
		{
			$this->call('GamertagMongoSeeder');
			$this->call('AdminsMongoSeeder');
		}
		else
		{
			$this->call('AdminsMongoSeeder');
		}
	}

}