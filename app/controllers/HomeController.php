<?php

use HaloWaypoint\Api;
use HaloWaypoint\Utils;

class HomeController extends BaseController {

	protected $layout = "layouts.index";

	public function index()
	{
		$api = new Api();
		return View::make('pages.homepage.halofour_index')
			->with('challenges', Utils::prettifyChallenges($api->getChallenges()));
	}

}