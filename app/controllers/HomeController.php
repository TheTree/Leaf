<?php

use HaloWaypoint\Api;
use HaloWaypoint\Utils;
use HaloFour\Gamertag;

use Illuminate\Http\Request as Request;
use Illuminate\Validation\Factory as Validator;
use Illuminate\View\Factory as View;
use Illuminate\Support\Facades\Redirect;

class HomeController extends BaseController {

	protected $layout = "layouts.index";

	protected $request;
	protected $validator;
	protected $view;

	public function __construct(Request $request, Validator $validator, View $view)
	{
		$this->request = $request;
		$this->validator = $validator;
		$this->view = $view;
	}

	public function index()
	{
		$api = new Api();
		return $this->view->make('pages.homepage.halofour_index')
			->with('latest', Gamertag::getLastXGamertags(5))
			->with('challenges', Utils::prettifyChallenges($api->getChallenges()))
			->with('title', 'Leafapp .:. Halo Stats');
	}

	public function about()
	{
		return $this->view->make('pages.about')
			->with('main_size', 8)
			->with('sidebar_size', 4);
	}

	public function addGamertag()
	{
		$validator = $this->validator->make($this->request->all(), [
				'gamertag' => 'required|min:1|max:15'
			]);

		if ($validator->fails())
		{
			return Redirect::to('')->withErrors($validator);
		}
		else
		{
			return Redirect::to('h4/record/' . Utils::makeSeoGamertag($this->request->get('gamertag')));
		}
	}
}