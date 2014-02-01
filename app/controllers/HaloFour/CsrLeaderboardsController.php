<?php

use HaloWaypoint\Utils;
use Illuminate\View\Environment as View;

class CsrLeaderboardsController extends \BaseController {

	protected $layout = "layouts.index";

	protected $view;

	public function __construct(View $view)
	{
		$this->view = $view;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param string $slug
	 * @internal param string $playlist
	 * @return Response
	 */
	public function index($slug = 'team-slayer')
	{
		$playlist = Utils::getIndividualPlaylistViaSlug($slug);

		if ($playlist === false)
		{

		}

		$this->layout->content =$this->view->make('pages.halofour.csr_leaderboards');
	}
}