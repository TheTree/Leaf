<?php

class BasicPageTest extends TestCase {

	public function testAboutPageRender()
	{
		$crawler = $this->client->request('GET', '/about');

		$this->assertTrue($this->client->getResponse()->isOk());
		$this->assertTrue(true, $crawler->filter('img:contains("http://www.gravatar.com/avatar/5504265788801935369f655841dd145c?s=200")'));
	}
}