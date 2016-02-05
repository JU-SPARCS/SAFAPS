<?php

namespace ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testPutevaluation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/evaluations');
    }

    public function testPutmanager()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/organizations/managers');
    }

    public function testDeletemanager()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/organizations/managers/{manid}');
    }

    public function testGetinvoices()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/organizations/invoices');
    }

}
