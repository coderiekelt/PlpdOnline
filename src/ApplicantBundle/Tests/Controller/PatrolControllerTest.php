<?php

namespace ApplicantBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PatrolControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/index');
    }

    public function testSchedule()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/schedule');
    }

}
