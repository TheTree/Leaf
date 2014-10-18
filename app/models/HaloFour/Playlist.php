<?php namespace HaloFour;

use Jenssegers\Mongodb\Eloquent\SoftDeletingTrait;
use Jenssegers\Mongodb\Model as Eloquent;

class Playlist extends Eloquent {
	use SoftDeletingTrait;

	protected $collection = "h4_playlists";

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

	public function getNameAttribute($value)
	{
		return " " . $value;
	}

	public function setTypeAttribute($value)
	{
		switch($value)
		{
			case "Team":
				$style = "info";
				break;

			case "Individual":
				$style = "primary";
				break;

			default:
			case "Unknown":
				$style = "default";
				break;
		}

		$this->attributes['Type'] = $value;
		$this->attributes['TypeStyle'] = "label-" . $style;
	}
}
