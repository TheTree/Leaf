<?php

use HaloWaypoint\Api;
use HaloWaypoint\Utils;
use Illuminate\Support\Facades\Validator;

class HomeController extends BaseController {

	protected $layout = "layouts.index";

	public function index()
	{
		$api = new Api();
		return View::make('pages.homepage.halofour_index')
			->with('challenges', Utils::prettifyChallenges($api->getChallenges()))
			->with('title', 'Leafapp .:. Halo Stats');
	}

	public function about()
	{
		return View::make('pages.about')
			->with('main_size', 8)
			->with('sidebar_size', 4);
	}

	public function addGamertag()
	{
		$validator = Validator::make(Input::all(), [
				'gamertag' => 'required|min:1|max:15|alpha_num'
			]);

		if ($validator->fails())
		{
			return Redirect::to('')->withErrors($validator);
		}
		else
		{
			return Redirect::to('h4/record/' . Utils::makeSeoGamertag(Input::get('gamertag')));
		}
	}
}