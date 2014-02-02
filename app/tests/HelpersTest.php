<?php

use Library\Helpers;

class HelpersTest extends TestCase {

	public function testTime()
	{
		$this->assertEquals('1 minute, 12 seconds', Helpers::time_duration(72));
		$this->assertEquals('4 months, 3 weeks, 4 hours, 59 minutes, 20 seconds', Helpers::time_duration(12351332));
	}
}