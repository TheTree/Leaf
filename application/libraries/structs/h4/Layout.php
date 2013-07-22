<?php

/**
 * Our enum of Layout for the Halo 4 profile. Due to the use of MongoDB, it replicates the category names
 * in every file of the collection. Thus you can store
 *
 * array(
 *    'totalKills' => 123123
 * );
 *
 * OR
 *
 * array(
 *      1 => 123123
 * );
 *
 * (in every file of the collection). So each of the above arrays times 200,000.
 *
 * The ID then corresponds to this enum. Saves a few bytes here and there which adds up.
 *
 */
final class Layout{

    const API_VERSION                       = 0x00;
    const ASSISTS_PER_GAME_RATIO            = 0x01;
    const AVERAGE_PERSONAL_SCORE            = 0x02;

    const BEST_GAME_ASSASSINATION_TOTAL     = 0x03;
    const BEST_GAME_ASSASSINATION_GAMEID    = 0x04;
    const BEST_GAME_HEADSHOT_TOTAL          = 0x05;
    const BEST_GAME_HEADSHOT_GAMEID         = 0x06;
    const BEST_GAME_KILL_DISTANCE           = 0x07;
    const BEST_GAME_KILL_DISTANCE_GAMEID    = 0x08;
    const BEST_GAME_TOTAL_KILLS             = 0x09;
    const BEST_GAME_TOTAL_KILLS_GAMEID      = 0x10;
    const BEST_GAME_TOTAL_MEDALS            = 0x0a;
    const BEST_GAME_TOTAL_MEDALS_GAMEID     = 0x0b;

    const BETRAYALS_PER_GAME_RATIO          = 0x0c;

    // @todo
}