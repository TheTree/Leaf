<?php

use HaloWaypoint\Leaderboards;
use Illuminate\Support\Facades\Redis;

class CsrTest extends TestCase {

	private $redis;
	private $leaderboards;
	private $prefix = "test_";

	public function setUp()
	{
		parent::setUp();

		// delete old redis sets
		$this->redis = Redis::connection();
		$this->redis->del($this->prefix . 001);

		$this->leaderboards = new Leaderboards();
		$this->leaderboards->setPrefix($this->prefix);
	}


	public function testFloats1()
	{
		$numbers = [
			'0' => [$this->leaderboards->mergeCsrWithKd(50, 10.46), 10.46],
			'1' => [$this->leaderboards->mergeCsrWithKd(50, 2.46), 2.46],
			'2' => [$this->leaderboards->mergeCsrWithKd(50, 1.46), 1.46],
			'3' => [$this->leaderboards->mergeCsrWithKd(8, 1.46), 1.46],
			'4' => [$this->leaderboards->mergeCsrWithKd(8, .46), .46]
		];

		foreach($numbers as $user => $number)
		{
			$this->redis->zadd($this->prefix . 001, $number[0], $user);
		}

		$response = $this->leaderboards->getTopGamertagsInPlaylist(001);

		if (is_array($response))
		{
			foreach($response as $item)
			{
				$this->assertEquals(floatval($item['KDRatio']), floatval($numbers[$item['Gamertag']][1]));
			}
		}
		else
		{
			$this->assertEquals(true, false);
		}
	}
}