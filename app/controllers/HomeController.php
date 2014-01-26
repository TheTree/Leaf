<?php

use HaloWaypoint\Api;

class HomeController extends BaseController {

	protected $layout = "layouts.index";

	public function index()
	{
		$api = new Api();

		$challenges = $api->getChallenges();
		$this->layout->content = View::make('index');
	}

}