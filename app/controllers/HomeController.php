<?php

class HomeController extends BaseController {

	public function indexPage()
	{
		return View::make('index');
	}

}