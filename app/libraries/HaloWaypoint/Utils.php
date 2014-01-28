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

	/**
	 *
	 * Takes a gamertag and converts it to a seo compatible name to be used in URLs
	 *
	 * @param $gamertag
	 * @return mixed
	 */
	public static function makeSeoGamertag($gamertag)
	{
		return preg_replace('/\s+/', '_', strtolower(urldecode(trim($gamertag))));
	}

	/**
	 *
	 * Takes a lowercase seo gamertag and converts it for use in Api
	 *
	 * ex: ibotpeaches_v5 -> ibotpeaches%20v5
	 *
	 * @param $seoGamertag
	 * @return mixed
	 */
	public static function makeApiSafeGamertag($seoGamertag)
	{
		return trim(rawurlencode(str_replace('_', ' ', $seoGamertag)));
	}
}