<?php

use BaseController;

class NewsController extends BaseController {

	protected $layout = "layouts.master";

	public function index()
	{
		$this->layout->content = View::make('pages.news.index');
	}

}