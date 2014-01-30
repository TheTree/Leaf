<?php

use \HaloFour\Gamertag as Gamertag;
use Illuminate\Support\Facades\Artisan;

class GamertagTest extends TestCase {

	protected static $record;

	public function setUp()
	{
		parent::setUp();
		Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--env' => 'testing']);

		self::$record = Gamertag::where('SeoGamertag', 'ibotpeaches_v5')->firstOrFail();
	}

	public function testRecord()
	{
		$this->assertEquals('iBotPeaches v5', self::$record['Gamertag']);
	}

	public function testUnpack()
	{
		//$this->assertEquals(true, is_array(self::$record['0x27']));
	}
}