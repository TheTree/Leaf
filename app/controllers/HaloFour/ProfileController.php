<?php

class ProfileController extends \BaseController {

	protected $layout = "layouts.index";

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
		$this->layout->content = View::make('pages.halofour.profile');
	}

}