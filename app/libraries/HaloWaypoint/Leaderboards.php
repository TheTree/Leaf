<?php namespace HaloWaypoint;

use Illuminate\Support\Facades\Redis;

class Leaderboards {

	private $prefix = "csr_";
	private $zeros = 10000;

	/**
	 * @param $gamertag
	 * @param $kd_ratio
	 * @param $csr_data
	 * @return bool
	 */
	public function updateCsrData($gamertag, $kd_ratio, $csr_data)
	{
		$redis = $this->getRedis();

		if (is_array($csr_data))
		{
			foreach($csr_data as $playlist)
			{
				if ($playlist->CurrentSkillRank != 0)
				{
					$command = $redis->createCommand('ZADD', [($this->prefix . $playlist->PlaylistId), $this->mergeCsrWithKd($playlist->CurrentSkillRank, $kd_ratio), $gamertag]);
					$redis->executeCommand($command);
				}
			}
		}

		return true;
	}

	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}

	private function getRedis()
	{
		return $redis = Redis::connection();
	}

	public function getTopGamertagsInPlaylist($playlist_id, $count = 15.0, $offset = 0.0)
	{
		$redis = $this->getRedis();

		$command = $redis->zrevrange($this->prefix . $playlist_id, $offset, $count, 'WITHSCORES');
		return $this->fixUpZRangeResponseFromRedis($command);
	}

	private function fixUpZRangeResponseFromRedis($response)
	{
		$return_array = [];

		if (is_array($response))
		{
			$x = 0;

			foreach($response as $pair)
			{
				$return_array[] = [
					'Gamertag' => $pair[0],
					'MergedKD' => round($pair[1], 7)
				];
			}
		}
		else
		{
			return false;
		}

		// iterate through our collected records, we need to do this
		// to cleanup our ugly stored stuff, so we can unwrap back
		// to CSR and KDRatio, (since redis stored it as one)
		foreach($return_array as $key => $pair)
		{
			$return_array[$key] = $this->unmergeCsrWithKd($pair);
		}

		return $return_array;
	}

	private function unmergeCsrWithKd($pair)
	{
		// we have the full mess of a number like
		// 8.0001681, which means CSR 8, 1.68 kd.
		$number_pair = Leaderboards::decodeNumber($pair['MergedKD']);

		$part_2 = substr($number_pair[1], 0, -1) * $this->zeros;
		$last_digit = substr($number_pair[1], -1);
		echo $last_digit . "\n";

		// the last digit (1), means we move the
		// decimal point (1) point to the left
		// after the last zero. This makes the
		// .0001681 -> 0001.681. Strip the
		// leading zeros and final digit.
		// thus CSR 8 and 1.68 kd.
		return [
			'Gamertag' => $pair['Gamertag'],
			'KDRatio' => $part_2,
			'CSR' => $number_pair[0]
		];
	}

	public function mergeCsrWithKd($csr, $kd)
	{
		// first we need to split the kd ratio from 1.46
		// to [1] and [.46], this helps us determine
		// the differences between huge and small kd's
		$number_pair = Leaderboards::decodeNumber($kd);
		$leading_digit = $this->countDigits($number_pair[0]);

		// now we take those values and divide by zeros
		// this should not affect the place of CSR
		// but allow ties to be broken via this
		$part_1 = $number_pair[0] / $this->zeros;
		$part_2 = $number_pair[1] / $this->zeros;

		// take the following two numbers
		// KD: 1.44, CSR 50 = 50.0001441
		// KD. 0.67, CSR 50 = 50.0000670
		// as you can see, the 1.44 KD user won
		// even though, they both tied in CSR
		// now in the front-end, we have both CSR/KD
		// at our disposal even though Redis stored
		// it as one value.
		$fo =  ($csr + $part_1 + $part_2) . $leading_digit;
		echo $fo . "\n";
		return (string) $fo;
	}

	private function countDigits($number)
	{
		if ($number === 0)
		{
			return 0;
		}
		else if ($number > 0 && $number < 10)
		{
			return 1;
		}
		else if ($number > 10 && $number < 100)
		{
			return 2;
		}
		else if ($number > 100 && $number < 1000)
		{
			return 3;
		}
	}

	private function decodeNumber($number, $return_unsigned = false)
	{
		$negative = 1;

		if ($number < 0)
		{
			$negative = -1;
			$number *= -1;
		}

		if ($return_unsigned)
		{
			return [
				floor($number),
				round(($number - floor($number)), 7)
			];
		}

		return [
			floor($number) * $negative,
			round(($number - floor($number)), 7) * $negative
		];
	}
}