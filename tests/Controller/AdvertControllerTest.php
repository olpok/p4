<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdvertControllerTest extends WebTestCase
{

    public function testHomeIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');
            
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
    
        $this->assertSame(1, $crawler->filter('html:contains("Billets coupe-file")')->count());
    }
 
}
