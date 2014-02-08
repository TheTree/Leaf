<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class H4GamertagsIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::collection('h4_gamertags', function($collection)
			{
				$collection->index('KDRatio');
				$collection->index('KADRatio');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::collection('h4_gamertags', function($collection)
			{
				$collection->dropIndex('KDRatio');
				$collection->dropIndex('KADRatio');
			});
	}

}
