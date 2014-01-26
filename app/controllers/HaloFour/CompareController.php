<?php

class CompareController extends \BaseController {

	protected $layout = "layouts.index";

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout->content = View::make('pages.halofour.compare');
	}

}