<?php

/**
 * Playlist Table
 *
 * Used to record the IDs for future help
 *
 * 95           - Global Challenge
 * 100          - Infinity Slayer
 * 101          - Big Team Infinity Slayer
 * 102          - Dominion
 * 103          - Regicide
 * 104          - Flood
 * 105          - Capture The Flag
 * 113          - Team Snipers
 * 115          - SWAT
 * 116          - Multi-Team
 * 117          - Griffball
 * 119          - Crimson DLC
 * 121          - Team Doubles
 * 123          - Team Throwdown
 * 124          - Majestic Team DLC
 * 126          - Team Action Sack
 * 128          - Rumble Pit
 * 134          - Big Team Skirmish
 * 135          - Legendary Slayer
 */


/**
 * enabled
 *
 * If disabled all features relating too
 *
 * 1) Grabbing new data will be disabled.
 * 2) New accounts for H4 will be disabled.
 * 3) Playlists will stop auto-updating.
 * 4) Challenges will be hidden and stop auto-updating
 * 5) Cron jobs will halt
 */
$config['h4_enabled']   = TRUE;

/**
 * h4_individual_csr
 *
 * Holds playlist ids for individual CSR playlists
 */
$config['h4_individual_csr'] = [101,104,100,116,103,128,115,126,113,134,135,95];

/**
 * h4_team_csr
 *
 * Holds playlist ids for team CSR playlists
 */
$config['h4_team_csr'] = [105,102,117,121,123,119];

/**
 * h4_max_rank
 *
 * This is highest rank obtainable via Halo 4
 */
$config['h4_max_rank'] = intval(130);

/**
 * h4_urls
 *
 * Holds the URLs responsible for downloading assets
 */
$config['h4_urls'] = [
    'emblem_url'    => "https://emblems.svc.halowaypoint.com/h4/emblems/{EMBLEM}?size={SIZE}",
    'spartan_url'   => "https://spartans.svc.halowaypoint.com/players/{GAMERTAG}/h4/spartans/fullbody?target={SIZE}",
    'rank_url'      => "https://assets.halowaypoint.com/games/h4/ranks/v1/{SIZE}/sr-{RANK}.png",
    'medal_url'     => "https://assets.halowaypoint.com/games/h4/medals/v1/{SIZE}/{MEDAL}",
    'csr_url'       => "https://assets.halowaypoint.com/games/h4/csr/v1/{SIZE}/{CSR}.png",
    'weapon_url'    => "https://assets.halowaypoint.com/games/h4/damage-types/v1/{SIZE}/{WEAPON}",
    'spec_url'      => "https://assets.halowaypoint.com/games/h4/specializations/v1/{SIZE}/{SPEC}"
];