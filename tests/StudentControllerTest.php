<?php

namespace App\Tests;

use App\Service\TaxCalculator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentControllerTest extends WebTestCase
{
    public function testStudentsIndexPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/student/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Student index', $crawler->filter('h1')->text());
    }

    public function testCreateNewStudentPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/student/');

        $link = $crawler->selectLink('Create new')->link();
        $crawler = $client->click($link);

        $this->assertSame('/student/new', $client->getRequest()->getPathInfo());
        $this->assertContains('Create new Student', $crawler->filter('h1')->text());
    }

    public function testCreateNewStudent()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/student/');

        $this->assertNotContains('foo.bar@example.com', $crawler->filter('table')->text());

        $crawler = $client->request('GET', '/student/new');

        $form = $crawler->selectButton('Save')->form();

        $form['student[firstname]'] = 'Foo';
        $form['student[lastname]'] = 'Bar';
        $form['student[email]'] = 'foo.bar@example.com';

        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame('/student/', $client->getRequest()->getPathInfo());
        $this->assertContains('foo.bar@example.com', $crawler->filter('table')->text());
    }

    public function testEditStudent()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/student/');

        $this->assertContains('foo.bar@example.com', $crawler->filter('table')->text());

        $link = $crawler
            ->filter('td:contains("foo.bar@example.com")')
            ->parents()
            ->selectLink('edit')
            ->link()
        ;

        $crawler = $client->click($link);

        $form = $crawler->selectButton('Update')->form();

        $form['student[firstname]'] = 'Lorem';
        $form['student[lastname]'] = 'Ipsum';
        $form['student[email]'] = 'lorem.ipsum@example.com';

        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame('/student/', $client->getRequest()->getPathInfo());
        $this->assertContains('lorem.ipsum@example.com', $crawler->filter('table')->text());
    }

    public function testDeleteStudentForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/student/');

        $this->assertContains('lorem.ipsum@example.com', $crawler->filter('table')->text());

        $link = $crawler
            ->filter('td:contains("lorem.ipsum@example.com")')
            ->parents()
            ->selectLink('edit')
            ->link()
        ;

        $crawler = $client->click($link);

        $form = $crawler->selectButton('Delete')->form();

        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame('/student/', $client->getRequest()->getPathInfo());
        $this->assertNotContains('lorem.ipsum@example.com', $crawler->filter('table')->text());
    }
}
