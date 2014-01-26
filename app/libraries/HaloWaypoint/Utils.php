<?php

namespace HaloWaypoint;

use Library\Helpers;

class Utils {
	public static function getBadgeColor($id)
	{
		switch($id)
		{
			case 0: return 'warning';
			case 1: return 'info';
			case 2: return 'primary';
			default: return '';
		}
	}

	/**
	 * @param $challenges
	 * @return array
	 */
	public static function prettifyChallenges($challenges)
	{
		foreach($challenges as $key => $challenge)
		{
			$challenges[$key]->Badge = Utils::getBadgeColor($challenge->CategoryId);
			$challenges[$key]->XpReward = number_format($challenge->XpReward);
			$challenges[$key]->EndDate = Helpers::time_duration(strtotime($challenge->EndDate, true) - time());
		}

		return $challenges;
	}
}