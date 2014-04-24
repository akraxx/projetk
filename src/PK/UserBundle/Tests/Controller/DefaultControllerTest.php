<?php

namespace PK\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
    }
    
    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register/');

        $this->assertTrue($crawler->filter('html:contains("Inscription")')->count() > 0);
    }
    
    public function testProfile()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profile');

        //$this->assertTrue($crawler->filter('html:contains("Identification")')->count() > 0);
    }
    
    public function testLogin() {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => "root",
            '_password'  => "pssword",
            ));     
        
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->followRedirect();
    }
    
    public function testLogout() {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/logout');

        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->followRedirect();
    }
}
