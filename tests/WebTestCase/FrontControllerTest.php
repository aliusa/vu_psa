<?php

namespace App\Tests\WebTestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerTest extends WebTestCase
{
    public function testHomepage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('a[href^="/services/"]');
    }

    public function testLoginPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form h1');
        $this->assertSelectorExists('form #email');
        $this->assertSelectorExists('form #password');
        $this->assertSelectorExists('form button[type=submit]');
        $this->assertSelectorExists('form input[name="_csrf_token"]');
    }

    public function testLogoutPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/logout');

        $this->assertResponseRedirects('/users/login');
    }

    public function testServices(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/services');

        $this->assertResponseIsSuccessful();
    }
}
