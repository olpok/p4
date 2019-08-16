<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdvertControllerTest extends WebTestCase
{

    public function testHomeIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');
            
        //echo $client->getResponse()->getContent();
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
    
        $this->assertSame(1, $crawler->filter('html:contains("J\'aime Symfony!")')->count());
    }

    public function testMailIsSentAndContentIsOk()
    {
        $client = static::createClient();

        // enables the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('POST', '/path/to/above/action');

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // checks that an email was sent
        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Hello Email', $message->getSubject());
        $this->assertSame('send@example.com', key($message->getFrom()));
        $this->assertSame('recipient@example.com', key($message->getTo()));
        $this->assertSame(
            'You should see me from the profiler!',
            $message->getBody()
        );
    }   
 
}
