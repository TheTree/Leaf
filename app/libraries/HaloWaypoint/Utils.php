<?php namespace HaloWaypoint;

use HaloFour\Gamertag;
use HaloFour\Playlist;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
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
		if ($service->GameModes[2]->TotalGamesStarted == 0)
		{
			return false;
		}

		// while we can leverage the use of Models greatly, we have two major
		// dumps of data here. So we can't blindly send them both.
		// We could use `upsert` of MongoDB, but it doesn't play nice with
		// Eloquent, so we do old fashion -- making an array and inserting it
		$data = [
			'SeoGamertag'                   => $seoGamertag,
			'Gamertag'                      => $service->Gamertag,
			'Rank'                          => $service->RankName,
			'SpartanPoints'                 => $service->SpartanPoints,
			'Emblem'                        => $service->EmblemImageUrl->AssetUrl,
			'TotalCommendationProgress'     => $service->TotalCommendationProgress,
			'TotalChallengesCompleted'      => $service->TotalChallengesCompleted,
			'TotalLoadoutItemsPurchased'    => $service->TotalLoadoutItemsPurchased,
			'TotalGameWins'                 => $service->GameModes[2]->TotalGamesWon,
			'TotalGamesCompleted'           => $service->GameModes[2]->TotalGamesCompleted,
			'TotalGamesStarted'             => $service->GameModes[2]->TotalGamesStarted,
			'TotalGameQuits'                => $service->GameModes[2]->TotalGamesStarted,
			'TotalMedals'                   => $service->GameModes[2]->TotalMedals,
			'TotalGameplay'                 => $service->GameModes[2]->TotalDuration,
			'TotalKills'                    => $service->GameModes[2]->TotalKills,
			'TotalDeaths'                   => $service->GameModes[2]->TotalDeaths,
			'TotalMedalStats'               => $wargames->TotalMedalsStats,
			'TotalBetrayals'                => $wargames->TotalBetrayals,
			'TotalSuicides'                 => $wargames->TotalSuicides,
			'FavoriteWeaponId'              => $service->FavoriteWeaponId,
			'FavoriteWeaponTotalKills'      => $service->FavoriteWeaponTotalKills,
			'BestGameTotalKills'            => $wargames->BestGameTotalKills,
			'BestGameTotalMedals'           => $wargames->BestGameTotalMedals,
			'BestGameTotalHeadshots'        => $wargames->BestGameHeadshotTotal,
			'BestGameTotalAssassinations'   => $wargames->BestGameAssassinationTotal,
			'BestGameKillDistance'          => $wargames->BestGameKillDistance,
			'BestGameTotalKillsId'          => $wargames->BestGameTotalKillsGameId,
			'BestGameTotalMedalsId'         => $wargames->BestGameTotalMedalsGameId,
			'BestGameTotalHeadshotsId'      => $wargames->BestGameHeadshotTotalGameId,
			'BestGameTotalAssassinationsId' => $wargames->BestGameAssassinationTotalGameId,
			'BestGameKillDistanceId'        => $wargames->BestGameKillDistanceGameId,
			'AveragePersonalScore'          => $service->GameModes[2]->AveragePersonalScore,
			'ServiceTag'                    => $service->ServiceTag,
			'TotalHeadshots'                => $wargames->TotalHeadshots,
			'TotalAssists'                  => $wargames->TotalAssists,
			'Specialization'                => $service->Specializations,
			'Expiration'                    => Carbon::now()->addWeek()->timestamp,
			'KDRatio'                       => $service->GameModes[2]->KDRatio,
			'Xp'                            => $service->XP,
			'TotalSkillStats'               => $service->SkillRanks,
			'RankStartXp'                   => $service->RankStartXP,
			'NextRankStartXp'               => $service->NextRankStartXP,
			'MedalsPerGameRatio'            => $service->GameModes[2]->TotalMedals,
			'DeathsPerGameRatio'            => $service->GameModes[2]->TotalDeaths,
			'KillsPerGameRatio'             => $service->GameModes[2]->TotalKills,
			'BetrayalsPerGameRatio'         => $wargames->TotalBetrayals,
			'SuicidesPerGameRatio'          => $wargames->TotalSuicides,
			'AssistsPerGameRatio'           => $wargames->TotalAssists,
			'HeadshotsPerGameRatio'         => $wargames->TotalHeadshots,
			'WinPercentage'                 => $service->GameModes[2]->TotalGamesWon,
			'QuitPercentage'                => $service->GameModes[2]->TotalGamesStarted,
			'APIVersion'                    => Config::get('leaf.HaloFourApiVersion')
		];

		try
		{
			$gamertag = Gamertag::where('SeoGamertag', $seoGamertag)->firstOrFail();

			// a quick check to see if their stats are actually changing
			// this allows us to prevent re-caching old accounts, thus wasting
			// bandwidth for me and 343.
			if ($gamertag->TotalGameplay == $service->GameModes[2]->TotalDuration)
			{
				$data['InactiveCounter'] += 1;
			}
			else
			{
				$data['InactiveCounter'] = 0;
			}
		}
		catch(ModelNotFoundException $ex)
		{
			$gamertag = new Gamertag;

			// lets set some default guides
			$data['InactiveCounter'] = intval(0);
			$data['Path'] = date('Y') . "/" . date('m') . "/" . date('d');
			$data['Status'] = intval(0);
		}

		foreach($data as $key => $value)
		{
			$gamertag->setAttribute($key, $value);
		}

		$gamertag->save();

		return $data;
	}

	public static function updatePlaylists($force = false)
	{
		// if we want to re-pull from 343 servers. Lets just clear out the cache
		// this will force a pull and update the dB accordingly
		if ($force === true)
		{
			Cache::forget('CurrentPlaylists');
		}

		$api = new Api();
		$playlists = $api->getPlaylists();

		if (is_array($playlists))
		{
			Playlist::deleteAllPlaylists();

			foreach($playlists as $playlist)
			{
				$record = Utils::getIndividualPlaylist($playlist->Id);

				if ($record instanceof Playlist)
				{
					$record->setAttribute('Name', $playlist->Name);
					$record->setAttribute('Id', $playlist->Id);

					if ($record->getAttribute('Type') == null)
					{
						$record->setAttribute('Type', 'Unknown');
					}

					$record->save();
					$record->restore();
				}

				return true;
			}
		}

		return false;
	}

	/**
	 * @param $id
	 * @param bool $create
	 * @return bool|Playlist
	 */
	private static function getIndividualPlaylist($id, $create = true)
	{
		try
		{
			$playlist = Playlist::WithTrashed()->where('Id', $id)->firstOrFail();
		}
		catch(ModelNotFoundException $ex)
		{
			if ($create)
			{
				$playlist = new Playlist;
			}
		}

		if (isset($playlist))
		{
			return $playlist;
		}

		return false;
	}
}