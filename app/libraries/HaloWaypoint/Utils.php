<?php namespace HaloWaypoint;

use HaloFour\Gamertag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Library\Helpers;
use Carbon\Carbon;

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

	/**
     *
	 * @param $seoGamertag
	 * @param $service
	 * @param $wargames
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public static function prepAndStoreApiData($seoGamertag, $service, $wargames)
	{
		// while we can leverage the use of Models greatly, we have two major
		// dumps of data here. So we can't blindly send them both.
		// We could use `upsert` of MongoDB, but it doesn't play nice with
		// Eloquent, so we do old fashion -- making an array and inserting it
		$data = [
			'SeoGamertag'                   => $seoGamertag,
			'Gamertag'                      => $service->Gamertag,
			'Rank'                          => $service->RankName,
			'TotalCommendationProgress'     => floatval($service->TotalCommendationProgress),
			'TotalLoadoutItemsPurchased'    => intval($service->TotalLoadoutItemsPurchased),
			'TotalMedals'                   => intval($service->GameModes[2]->TotalMedals),
			'Specialization'                => $service->Specializations,
			'Expiration'                    => intval(Carbon::now()->addWeek()->timestamp),
			'KDRatio'                       => $service->GameModes[2]->KDRatio
		];

		try
		{
			$gamertag = Gamertag::where('SeoGamertag', $seoGamertag)->firstOrFail();
		}
		catch(ModelNotFoundException $ex)
		{
			$gamertag = new Gamertag;
		}

		foreach($data as $key => $value)
		{
			$gamertag->setAttribute($key, $value);
		}

		$gamertag->save();

		return $data;
	}
}