<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProduitTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/panier/');

//        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('h2', 'Plop');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}
