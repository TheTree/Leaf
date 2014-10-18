<?php

use Illuminate\View\Factory as View;

class DashboardController extends \BaseController {

	protected $layout = "layouts.backstage.index";

	private $view;

	public function __construct(View $view)
	{
		$this->beforeFilter('auth');
		$this->beforeFilter('csrf', ['on' => 'post']);
		$this->view = $view;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->layout->content = $this->view->make('pages.backstage.dashboard');
	}

	public function missingMethods($parameters = array())
	{
		App::abort(404);
	}

}