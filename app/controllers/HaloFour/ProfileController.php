<?php

use Illuminate\View\Factory as View;

class ProfileController extends \BaseController {

	protected $layout = "layouts.index";

	protected $view;

	public function __construct(View $view)
	{
		$this->view = $view;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param string $seoGamertag
	 * @return Response
	 */
	public function index($seoGamertag = '')
	{
		$api = new \HaloWaypoint\Api();
		$record = $api->getGamertagData($seoGamertag);
		$this->layout->content = $this->view->make('pages.halofour.profile');
	}

}