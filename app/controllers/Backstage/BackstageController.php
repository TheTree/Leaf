<?php

use Users\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Factory as View;

class BackstageController extends \BaseController {

	protected $layout = "layouts.backstage.index";

	private $view;

	public function __construct(View $view)
	{
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
		if (Auth::check())
		{
			return Redirect::intended("backstage/dashboard");
		}

		$this->layout->content = $this->view->make('pages.backstage.gate');
	}

	public function postIndex()
	{
		if (($validator = Admin::check(Input::all())) === true)
		{
			if (Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')], true))
			{
				return Redirect::intended('backstage/dashboard');
			}
			else
			{
				return Redirect::back()
					->with('flash_error', 'The email/password was incorrect.')
					->withInput();
			}
		}
		else
		{
			return Redirect::back()
				->withErrors($validator);
		}
	}

	public function missingMethods($parameters = array())
	{
		App::abort(404);
	}

}