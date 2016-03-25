<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InvoiceControllerTest extends WebTestCase
{
    public function testListorganizations()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/organizations');
    }

    public function testShoworganizationinvoicelist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'organizations/{id}/invoices');
    }

}
