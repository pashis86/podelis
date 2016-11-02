<?php

namespace NFQ\SandboxBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowControllerTest extends WebTestCase
{
    public function testShowinfo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/showInfo');
    }

}
