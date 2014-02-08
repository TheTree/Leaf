<?php

use HaloWaypoint\Utils;
use HaloWaypoint\Leaderboards;
use HaloFour\Playlist;

use Illuminate\View\Environment as View;

class CsrLeaderboardsController extends \BaseController {

	protected $layout = "layouts.halofour.csr";

	private $default_slug = 'team-slayer';

	protected $view;

	public function __construct(View $view)
	{
		$this->view = $view;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Redirect::to('csr_leaderboards/' . $this->default_slug);
	}

	public function playlist($slug = 'team-slayer', $page = 0)
	{
		$leaderboards = new Leaderboards();
		$playlists = Playlist::orderBy('Name', 'asc')->get();
		$playlist = Utils::findIndividualPlaylistViaSlug($playlists, $slug);

		if ($playlist === false)
		{
			App::abort(404);
		}
		else
		{
		   	// contact redis to get our data
			$results = $leaderboards->getTopGamertagsInPlaylist($playlist->Id, 15.0, (float) $page);

			$this->layout->content = $this->view->make('pages.halofour.csr_leaderboards')
				->with('results', Utils::createLeaderboardRanks($results, $page))
				->with('playlists', $playlists)
				->with('active', $playlist);
		}
	}
}