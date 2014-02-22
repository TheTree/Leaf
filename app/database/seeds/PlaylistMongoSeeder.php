<?php

use Illuminate\Support\Facades\Artisan as Artisan;

class PlaylistMongoSeeder extends Seeder {

	public function run()
	{
		DB::collection('h4_playlists')->delete();
		Artisan::call('command:playlist_update');
	}
}