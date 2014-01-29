<?php

use Illuminate\Support\Facades\Log;

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
			Log::info('Gamertag collection seeded!');
		}
		else
		{
			Log::alert('Seeding only allowed in `testing`.');
		}
	}

}