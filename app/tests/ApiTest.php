<?php

class ApiTest extends TestCase {

	public function testChallengesApi()
	{
		$api = new \HaloWaypoint\Api();

		$this->assertTrue(true, is_array($api->getChallenges()));
	}

	public function testPrivateAuthEndpoint()
	{
		$api = new \HaloWaypoint\Api();

		$this->assertTrue(true, is_array($api->getSpartanAuthKey()));
	}

}