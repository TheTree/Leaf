<?php

namespace HaloWaypoint;

use Illuminate\Support\Facades\Cache as Cache;
use jyggen\Curl as MCurl;

class Api
{
	private $url = "https://stats.svc.halowaypoint.com";

	private $lang = "english";

	private $game = "h4";

	/**
	 * @return array
	 */
	public function getChallenges()
	{
		if (Cache::has('CurrentChallenges'))
		{
			// check it
		}
		else
		{
			$response = self::grabUrl("challenges", false);
			Cache::put('CurrentChallenges', $response, 60 * 31);
		}
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

	private function getUrl($endpoint)
	{
		return $this->url . "/" . $this->lang . "/" . $this->game . "/" . $endpoint;
	}

	private function checkStatus($response)
	{
		if (isset($response->StatusCode) && intval($response->StatusCode) == intval(1))
		{
			return $response;
		}

		return false;
	}

	private function getHeaders($auth = false)
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

		return $header_array;
	}

	private function grabUrl($endpoint, $auth = false, $execute = true)
	{
		$url = $this->getUrl($endpoint);
		$headers = $this->getHeaders($auth);

		$request = new MCurl\Request($url);
		$request->setOption(CURLOPT_HTTPHEADER, $headers);
		$request->setOption(CURLOPT_SSL_VERIFYPEER, false);
		$request->setOption(CURLOPT_SSL_VERIFYHOST, false);

		if ($execute === false)
		{
			return [
				'headers' => [
					CURLOPT_HTTPHEADER => $headers
				],
				'url'   => $url
			];
		}
		else
		{
			$request->execute();

			if ($request->isSuccessful())
			{
				$response = $request->getResponse()->getContent();
				return $this->checkStatus(json_decode($response));
			}
			else
			{
				return false;
			}
		}
	}

}
