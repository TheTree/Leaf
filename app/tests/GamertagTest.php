<?php

use \HaloFour\Gamertag as GamertagModel;

class GamertagTest extends TestCase {

	protected static $record;

	public function setUp()
	{
		parent::setUp();
		self::$record = GamertagModel::findOrFail('51edd8efa8fff1f61e74e6e8');
	}

	public function testRecord()
	{
		$this->assertEquals('iBotPeaches v5', self::$record['0x15']);
	}

	public function testUnpack()
	{
		$this->assertEquals(true, is_array(self::$record['0x27']));
	}
}