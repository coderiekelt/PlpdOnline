<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/index');
    }

    public function testReview()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/review');
    }

    public function testAccept()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/accept');
    }

    public function testDeny()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deny');
    }

    public function testAssign()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/assign');
    }

    public function testRemove()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/remove');
    }

}
