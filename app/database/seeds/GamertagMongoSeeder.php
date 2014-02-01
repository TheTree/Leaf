<?php

use \HaloFour\Gamertag as Gamertag;

class GamertagMongoSeeder extends Seeder {

	public function run()
	{
		DB::collection('h4_gamertags')->delete();

		Gamertag::create(
			[
				"TotalGamesCompleted" => 620,
				"TotalGamesStarted" => 692,
				"TotalKills" => 8499,
				"TotalDeaths" => 7479,
				"TotalAssists" => 4101,
				"TotalGameQuits" => 72,
				"APIVersion" => 1,
				"AssistsPerGameRatio" => 5.93,
				"AveragePersonalScore" => 211,
				"BestGameKillDistance" => 155,
				"BestGameKillDistanceId" => "5aee23c49d5cf614",
				"BestGameTotalAssassinations" => 5,
				"BestGameTotalAssassinationsId" => "241071c4ae2cbac0",
				"BestGameTotalHeadshots" => 30,
				"BestGameTotalHeadshotsId" => "18d353a574761084",
				"BestGameTotalKills" => 44,
				"BestGameTotalKillsId" => "d2629f297ca99780",
				"BestGameTotalMedals" => 75,
				"BestGameTotalMedalsId" => "9d1a402ca9185b23",
				"BetrayalsPerGameRatio" => 0.07000000000000001,
				"DeathsPerGameRatio" => 10.81,
				"Emblem" => "yellow_valkyrie-on-recruit",
				"Expiration" => 1391869494,
				"FavoriteWeaponId" => 16,
				"FavoriteWeaponTotalKills" => 3012,
				"Gamertag" => "PyroSquirrell",
				"HeadshotsPerGameRatio" => 7.39,
				"InactiveCounter" => 0,
				"KADRatio" => 1.68,
				"KDRatio" => 1.14,
				"KillsPerGameRatio" => 12.28,
				"MedalsPerGameRatio" => 28.33,
				"NextRankStartXp" => 24490,
				"Path" => "2014/01/30",
				"QuitPercentage" => 0.1,
				"Rank" => "82",
				"RankStartXp" => 11730,
				"SeoGamertag" => "pyrosquirrell",
				"ServiceTag" => "RUFY",
				"SpartanPoints" => 52,
				"Specialization" => "Pathfinder",
				"SpecializationLevel" => 2,
				"Status" => 0,
				"SuicidesPerGameRatio" => 0.05,
				"TotalBetrayals" => 49,
				"TotalChallengesCompleted" => 56,
				"TotalCommendationProgress" => 0.45,
				"TotalGameWins" => 390,
				"TotalGameplay" => 312927,
				"TotalHeadshots" => 5115,
				"TotalLoadoutItemsPurchased" => 31,
				"TotalMedalStats" => '',
				"TotalMedals" => 19606,
				"TotalSkillStats" => new StdClass(),
				"TotalSuicides" => 35,
				"WinPercentage" => 0.5600000000000001,
				"Xp" => 16551
			]
		);

	}
}