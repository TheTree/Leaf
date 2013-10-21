<?php

/**
 * Our enum of Layout for the Halo 4 profile. Due to the use of MongoDB, it replicates the category names
 * in every file of the collection. Thus you can store
 *
 * array(
 *    'totalKills' => 123123
 * )';
 *
 * OR
 *
 * array(
 *      1 => 123123
 * )';
 *
 * (in every file of the collection). So each of the above arrays times 200,000.
 *
 * The ID then corresponds to this enum. Saves a few bytes here and there which adds up.
 *
 */
final class H4{

    const API_VERSION                       = '0x00';
    const ASSISTS_PER_GAME_RATIO            = '0x01';
    const AVERAGE_PERSONAL_SCORE            = '0x02';

    const BEST_GAME_ASSASSINATION_TOTAL     = '0x03';
    const BEST_GAME_ASSASSINATION_GAMEID    = '0x04';
    const BEST_GAME_HEADSHOT_TOTAL          = '0x05';
    const BEST_GAME_HEADSHOT_GAMEID         = '0x06';
    const BEST_GAME_KILL_DISTANCE           = '0x07';
    const BEST_GAME_KILL_DISTANCE_GAMEID    = '0x08';
    const BEST_GAME_TOTAL_KILLS             = '0x09';
    const BEST_GAME_TOTAL_KILLS_GAMEID      = '0x0a';
    const BEST_GAME_TOTAL_MEDALS            = '0x0b';
    const BEST_GAME_TOTAL_MEDALS_GAMEID     = '0x0c';

    const BETRAYALS_PER_GAME_RATIO          = '0x0d';
    const DEATHS_PER_GAME_RATIO             = '0x0e';
    const EMBLEM                            = '0x0f';
    const EXPIRATION                        = '0x10';
    const FAVORITE_WEAPON_DESCRIPTION       = '0x11';
    const FAVORITE_WEAPON_ID                = '0x12';
    const FAVORITE_WEAPON_TOTAL_KILLS       = '0x13';
    const FAVORITE_WEAPON_URL               = '0x14';

    const GAMERTAG                          = '0x15';
    const HASHED_GAMERTAG                   = '0x16';
    const HEADSHOTS_PER_GAME_RATIO          = '0x17';
    const INACTIVE_COUNTER                  = '0x18';
    const KD_RATIO                          = '0x19';
    const KAD_RATIO                         = '0x1a';
    const KILLS_PER_GAME_RATIO              = '0x1b';
    const LAST_UPDATE                       = '0x1c';
    const MEDAL_DATA                        = '0x1d';
    const MEDALS_PER_GAME_RATIO             = '0x1e';
    const NEXT_RANK_START_XP                = '0x1f';
    const QUIT_PERCENTAGE                   = '0x20';
    const RANK                              = '0x21';
    const RANK_START_XP                     = '0x22';
    const SEO_GAMERTAG                      = '0x23';
    const SERVICE_TAG                       = '0x24';
    const SKILL_DATA                        = '0x25';
    const SPARTAN_POINTS                    = '0x26';
    const SPEC_DATA                         = '0x27';
    const SPECIALIZATION                    = '0x28';
    const SPECIALIZATION_LEVEL              = '0x29';
    const STATUS                            = '0x2a';
    const SUICIDES_PER_GAME_RATIO           = '0x2b';

    const TOTAL_ASSISTS                     = '0x2c';
    const TOTAL_BETRAYALS                   = '0x2d';
    const TOTAL_CHALLENGES_COMPLETED        = '0x2e';
    const TOTAL_COMMENDATION_PROGRESS       = '0x2f';
    const TOTAL_DEATHS                      = '0x30';
    const TOTAL_GAME_QUITS                  = '0x31';
    const TOTAL_GAME_WINS                   = '0x32';
    const TOTAL_GAMEPLAY                    = '0x33';
    const TOTAL_GAMES_STARTED               = '0x34';
    const TOTAL_HEADSHOTS                   = '0x35';
    const TOTAL_KILLS                       = '0x36';
    const TOTAL_LOADOUT_ITEMS_PURCHASED     = '0x37';
    const TOTAL_MEDALS                      = '0x38';
    const TOTAL_SUICIDES                    = '0x39';
    const WIN_PERCENTAGE                    = '0x3a';
    const XP                                = '0x3b';
    const FAVORITE_WEAPON_NAME              = '0x3c';
    const BADGE                             = '0x3d';

    const BADGE_COLOR                       = '0x3e';

    const DAY                               = '0x3f';
    const MONTH                             = '0x40';
    const YEAR                              = '0x41';
}