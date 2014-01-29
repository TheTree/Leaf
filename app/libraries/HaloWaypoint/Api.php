<?php namespace HaloWaypoint;

use HaloFour\Gamertag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use jyggen\Curl as MCurl;

class WLIDAuthenticationFailedException extends \Exception {}
class SpartanTokenFailedException extends \Exception {}
class APIEndpointFailedException extends \Exception {}

class Api {
	private $url = "https://stats.svc.halowaypoint.com";
	private $auth = "https://settings.svc.halowaypoint.com/RegisterClientService.svc/spartantoken/wlid";
	private $presence = "https://presence.svc.halowaypoint.com";

	private $lang = "en-US";

	private $game = "h4";

	/**
	 * @return array
	 */
	public function getChallenges()
	{
		if (Cache::has('CurrentChallenges'))
		{
			// check it
			$response = Cache::get('CurrentChallenges');

			if (time() > strtotime($response->Challenges['0']->EndDate, true))
			{
				Cache::forget('CurrentChallenges');
				return $this->getChallenges();
			}
			return $response->Challenges;
		}
		else
		{
			$response = $this->grabUrl("challenges", "default", false);
			if ($response === false) throw new APIEndpointFailedException();

			Cache::put('CurrentChallenges', $response, 60 * 24);
			return $response->Challenges;
		}
	}

	public function getPlaylists()
	{
		if (Cache::has('CurrentPlaylists'))
		{
			return Cache::get('CurrentPlaylists');
		}
		else
		{
			$response = $this->grabUrl("playlists", "presence", true);
			if (!isset($response->Playlists)) throw new APIEndpointFailedException();

			// we only care about playlists that are in matchmaking (id 3)
			// and active (isCurrent). So lets trash the rest.
			// For the ones, we do want. Lets remove the Map/Game list, we
			// don't use those portions and it accounts for a lot of space.
			foreach($response->Playlists as $key => $playlist)
			{
				if ($playlist->ModeId == 3 && $playlist->IsCurrent === true)
				{
					unset($response->Playlists[$key]->GameVariants);
					unset($response->Playlists[$key]->MapVariants);
				}
				else
				{
					unset($response->Playlists[$key]);
				}
			}

			Cache::put('CurrentPlaylists', $response->Playlists, 60 * 24);
			return $response->Playlists;
		}
	}

	public function getGamertagData($seoGamertag, $force = false)
	{
		// @todo check if API requests are disabled (only pull from cache)
		if (($record = $this->getGamertagDataViaCache($seoGamertag)) === false || $force === false)
		{
			$safeGamertag = Utils::makeApiSafeGamertag($seoGamertag);

			// at this point our secondary cache does not have this record
			// its entirely possible this record doesn't exist.
			// Lets hit the API, grab the data to see if this account exists
			$service_record = $this->grabUrl($safeGamertag, "service", false, false);
			$wargames_record = $this->grabUrl($safeGamertag, "wargames", true, false);

			$request_service = new MCurl\Request($service_record['url']);
			$request_wargames = new MCurl\Request($wargames_record['url']);

			$request_service->setOption(CURLOPT_HTTPHEADER, $service_record['headers']);
			$request_wargames->setOption(CURLOPT_HTTPHEADER, $wargames_record['headers']);

			$dispatcher = new MCurl\Dispatcher();
			$dispatcher->add($request_service);
			$dispatcher->add($request_wargames);
			$dispatcher->execute();

			if ($request_service->isSuccessful() && $request_wargames->isSuccessful())
			{
				// we have all the data now. We still need to grab the emblems
				// and Spartan picture, but for the most part we are done here.
				$service_record = $this->decodeResponse($request_service);
				$wargames_record = $this->decodeResponse($request_wargames);

				$record = Utils::prepAndStoreApiData(
					$seoGamertag, $service_record, $wargames_record
				);

			}
			else
			{
				// @todo add entry into the missing table
				App::abort(404, Lang::get('errors.gt_not_found', ['gamertag' => $seoGamertag]));

			}
		}
		else
		{
			// lets see if this data needs to be rehashed
			return $this->getGamertagData($seoGamertag, true);
			dd($record);
		}
	}

	private function getGamertagDataViaApi($seoGamertag)
	{

	}

	/**
	 * @param $seoGamertag
	 * @return bool
	 */
	private function getGamertagDataViaCache($seoGamertag)
	{
		try
		{
			$record = Gamertag::where('SeoGamertag', $seoGamertag)->firstOrFail();
		}
		catch (ModelNotFoundException $ex)
		{
			return false;
		}
		return $record;
	}

	/**
	* @throws SpartanTokenFailedException
	* @throws WLIDAuthenticationFailedException
	* @return string
	*/
	public function getSpartanAuthKey()
	{
		if (Cache::has('SpartanAuthKey'))
		{
			return Cache::get('SpartanAuthKey')->SpartanToken;
		}
		else
		{
			$url = Config::get('secret.SpartanAuthUrl');
			$request = new MCurl\Request($url);

			$request->execute();

			if ($request->isSuccessful())
			{
				$response = $this->decodeResponse($request);

				if (time() > intval($response->expiresIn))
				{
					return $this->getSpartanAuthKey();
				}
				else
				{
					// at this point, we have a WLID AuthenticationToken
					// we must use this against the RegisterService of 343
					// to get a SpartanToken, which lasts for an hour
					// which we can use against authenticated api calls
					$request = new MCurl\Request($this->auth);
					$request->setOption(CURLOPT_HTTPHEADER, [
							'Accept: application/json',
							'X-343-Authorization-WLID: ' . 'v1=' . $response->accessToken
						]);
					$request->setOption(CURLOPT_SSL_VERIFYPEER, false);
					$request->setOption(CURLOPT_SSL_VERIFYHOST, false);
					$request->execute();

					if ($request->isSuccessful())
					{
						$response = $this->decodeResponse($request);
						Cache::put('SpartanAuthKey', $response, 50);
						return $response->SpartanToken;
					}

					Cache::forget('SpartanAuthKey');
					throw new SpartanTokenFailedException();
				}
			}
			else
			{
				throw new WLIDAuthenticationFailedException();
			}
		}
	}

	private function getUrl($endpoint, $type = "default")
	{
		switch ($type)
		{
			case "presence":
				return $this->presence . "/" . $this->lang . "/" . $this->game . "/" . $endpoint;

			case "service":
				return $this->url . "/" . $this->lang . "/players/" . $endpoint . "/" . $this->game . "/servicerecord";

			case "wargames":
				return $this->url . "/" . $this->lang . "/players/" . $endpoint . "/" . $this->game . "/servicerecord/wargames";

			default:
				return $this->url . "/" . $this->lang . "/" . $this->game . "/" . $endpoint;

		}
	}

	private function checkStatus($response)
	{
		if (isset($response->StatusCode))
		{
			return $response;
		}

		return false;
	}

	private function decodeResponse($data)
	{
		if (is_array($data))
		{
			foreach($data as $key => $item)
			{
				if ($item instanceof MCurl\Request)
				{
					$data[$key] = json_decode($item->getResponse()->getContent());
				}
			}
		}
		else
		{
			if ($data instanceof MCurl\Request)
			{
				$data = json_decode($data->getResponse()->getContent());
			}
		}

		return $data;
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

	private function grabUrl($endpoint, $type = "default", $auth = false, $execute = true)
	{
		$url = $this->getUrl($endpoint, $type);
		$headers = $this->getHeaders($auth);

		$request = new MCurl\Request($url);
		$request->setOption(CURLOPT_HTTPHEADER, $headers);
		$request->setOption(CURLOPT_SSL_VERIFYPEER, false);
		$request->setOption(CURLOPT_SSL_VERIFYHOST, false);

		if ($execute === false)
		{
			return [
				'headers' => $headers,
				'url'   => $url
			];
		}
		else
		{
			$request->execute();

			if ($request->isSuccessful())
			{
				$response = $this->decodeResponse($request);
				return $this->checkStatus($response);
			}
			else
			{
				return false;
			}
		}
	}

}
