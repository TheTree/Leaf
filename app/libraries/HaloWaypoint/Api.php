<?php

namespace HaloWaypoint;

class Api
{
	private $curl;

	private $url = "@todo put url";

	function __construct() {
		$this->curl = new \Curl;
	}

	/**
	 * @return array
	 */
	public static function getChallenges()
	{

	}

	/**
	 * @return string
	 */
	private function getSpartanAuthKey()
	{
		if (Cache::has('SpartanAuthKey'))
		{
			return Cache::get('SpartanAuthKey')->SpartanToken;
		}
		else
		{
			return "@todo get spartan key";
		}
	}

	private function setHeaders($auth = false)
	{
		if ($auth)
		{
			$key = $this->getSpartanAuthKey();

			$header_array = [
				'Accept: application/json',
				'X-343-Authorization-Spartan: ' . $key
			];
		}
		else
		{
			$header_array = [
				'Accept: application/json'
			];
		}

		$this->curl->option('HTTPHEADER', $header_array);
	}

}
