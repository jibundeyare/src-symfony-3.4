<?php

namespace App\Tests;

use App\Service\TaxCalculator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaxControllerTest extends WebTestCase
{
    public function testTaxCalculatorPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tax/calculator');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Calculatrice de prix TVA comprise', $crawler->filter('h1')->text());
    }

    public function testTaxCalculatorForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tax/calculator');

        $form = $crawler->filter('button')->form();

        $form['price'] = 10;
        $form['rate'] = TaxCalculator::RATES['normal'];

        $crawler = $client->submit($form);

        $this->assertContains('12.00', $crawler->filter('#tip')->text());
    }
}
