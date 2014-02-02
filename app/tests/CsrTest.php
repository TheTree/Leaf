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

	public function testOrder()
	{
		$numbers = [
			'5' => [$this->leaderboards->mergeCsrWithKd(44, 10.46), 10.46],
			'8' => [$this->leaderboards->mergeCsrWithKd(21, 1.46), 1.46],
			'0' => [$this->leaderboards->mergeCsrWithKd(50, 10.46), 10.46],
			'1' => [$this->leaderboards->mergeCsrWithKd(50, 2.46), 2.46],
			'4' => [$this->leaderboards->mergeCsrWithKd(50, .46), .46],
			'2' => [$this->leaderboards->mergeCsrWithKd(50, 1.46), 1.46],
			'3' => [$this->leaderboards->mergeCsrWithKd(50, 1.45), 1.45],
			'6' => [$this->leaderboards->mergeCsrWithKd(44, 2.46), 2.46],
			'7' => [$this->leaderboards->mergeCsrWithKd(42, 1.46), 1.46],
			'9' => [$this->leaderboards->mergeCsrWithKd(6, .46), .46]
		];

		foreach($numbers as $user => $number)
		{
			$this->redis->zadd($this->prefix . 001, $number[0], $user);
		}

		$response = $this->leaderboards->getTopGamertagsInPlaylist(001);

		if (is_array($response))
		{
			// we have a random group of leaderboards above, that should
			// (after redis) be 0-9. We will simply check the index vs
			// the gamertag in order and they should check if they
			// were storted correctly
			foreach($response as $key => $item)
			{
				$this->assertEquals(intval($key), intval($item['Gamertag']));
			}
		}
		else
		{
			$this->assertEquals(true, false);
		}
	}
}