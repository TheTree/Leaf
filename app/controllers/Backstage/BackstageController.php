<?php

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
	public function index()
	{
		$this->layout->content = $this->view->make('pages.backstage.gate');
	}

	public function postIndex()
	{
		dd($_POST);
	}

}