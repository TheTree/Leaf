<?php

use Users\Admin;
use Illuminate\Support\Facades\Validator as Validator;
use Illuminate\View\Environment as View;

class BackstageController extends \BaseController {

	protected $layout = "layouts.backstage.index";

	private $view;

	public function __construct(View $view)
	{
		$this->view = $view;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$this->layout->content = $this->view->make('pages.backstage.gate');
	}

	public function postIndex()
	{
		if (($validator = Admin::check(Input::all())) === true)
		{

		}
		else
		{
			return Redirect::back()->withErrors($validator);
		}
	}

	public function missingMethods($parameters = array())
	{
		App::abort(404);
	}

}