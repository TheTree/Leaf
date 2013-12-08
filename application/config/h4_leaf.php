<?php

/**
 * Playlist Table
 *
 * Used to record the IDs for future help
 *
 * 90           - Infinity Challenge Slayer
 * 91           - Infinity Challenge CTF
 * 92           - Infinity Challenge Regicide
 * 93           - Infinity Challenge Dominion
 * 95           - Global Challenge
 * 96           - Global Challenge
 * 97           - Global Challenge
 * 98           - Global Challenge
 * 99           - Global Challenge
 * 100          - Infinity Slayer
 * 101          - Big Team Battle
 * 102          - Dominion
 * 103          - Regicide
 * 104          - Flood
 * 105          - Capture The Flag
 * 107          - Team Oddball
 * 108          - King of The Hill
 * 109          - Team Slayer Pro
 * 112          - Community Forge Playlist
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
 * 137          - Champions Bundle DLC
 * 138          - Ricochet
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
$config['h4_individual_csr'] = [101,104,113,115,126,128,138];

/**
 * h4_team_csr
 *
 * Holds playlist ids for team CSR playlists
 */
$config['h4_team_csr'] = [100,105,117,121,123];

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