<?php namespace HaloWaypoint;

use Illuminate\Support\Facades\Redis;

class Leaderboards {

	private $prefix = "csr_";
	private $zeros = 10000;

	public function updateCsrData($gamertag, $kd_ratio, $csr_data)
	{
		$redis = Redis::connection();

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
	}

	private function mergeCsrWithKd($csr, $kd)
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
		$fo =  $csr + $part_1 + $part_2 . $leading_digit;
		return $fo;
	}

	private function countDigits($number)
	{
		if ($number === 0)
		{
			return 0;
		}

		return strlen((string) $number);
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
				($number - floor($number))
			];
		}

		return [
			floor($number) * $negative,
			($number - floor($number)) * $negative
		];
	}
}