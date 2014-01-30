<?php namespace HaloFour;

use Jenssegers\Mongodb\Model as Eloquent;

class Playlist extends Eloquent {

	protected $collection = "h4_playlists";

	protected $softDelete = true;

	protected $guarded = ['_id'];

	/**
	 * Using softDelete we can delete all playlists prior
	 * to a new update. Then we simply restore the playlists
	 * that still exist in the feed.
	 *
	 * This allows us to prevent messing with Id's as we can
	 * never trust what 343 will do, since PlaylistId's have
	 * changed before in regards to matchmaking.
	 */
	public static function deleteAllPlaylists()
	{
		$playlists = Playlist::all();

		foreach($playlists as $playlist)
		{
			$playlist->delete();
		}
	}
}
