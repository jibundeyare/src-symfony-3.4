<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentControllerTest extends WebTestCase
{
    public function testStudentsIndexPage()
    {
        // créer un client
        $client = static::createClient();

        // se rendre à l'URL `/student/`
        $crawler = $client->request('GET', '/student/');

        // vérifier que le serveur répond un code HTTP 200
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        // vérifier que le texte `Student index` se trouve dans la balise `h1`
        $this->assertContains('Student index', $crawler->filter('h1')->text());
    }

    public function testCreateNewStudentPage()
    {
        // créer un client
        $client = static::createClient();

        // se rendre à l'URL `/student/`
        $crawler = $client->request('GET', '/student/');

        // trouver le lien (balise `a`) avec le texte `Create new`
        $link = $crawler->selectLink('Create new')->link();

        // cliquer sur le lien
        $crawler = $client->click($link);

        // vérifier que le client se trouve à l'URL `/student/new`
        $this->assertSame('/student/new', $client->getRequest()->getPathInfo());
        // vérifier que le texte `Create new Student` se trouve dans la balise `h1`
        $this->assertContains('Create new Student', $crawler->filter('h1')->text());
    }

    public function testCreateNewStudent()
    {
        // créer un client
        $client = static::createClient();

        // se rendre à l'URL `/student/`
        $crawler = $client->request('GET', '/student/');

        // vérifier que le texte `foo.bar@example.com` est absent dans la balise `table`
        $this->assertNotContains('foo.bar@example.com', $crawler->filter('table')->text());

        // se rendre à l'URL `/student/new`
        $crawler = $client->request('GET', '/student/new');

        // trouver le bouton (balise `input type="submit"` ou `button`) avec le texte `Save`
        $form = $crawler->selectButton('Save')->form();

        // remplir le champ dont l'attribut `name` est `student[firstname]` avec la valeur `Foo`
        $form['student[firstname]'] = 'Foo';
        // remplir le champ dont l'attribut `name` est `student[lastname]` avec la valeur `Bar`
        $form['student[lastname]'] = 'Bar';
        // remplir le champ dont l'attribut `name` est `student[email]` avec la valeur `foo.bar@example.com`
        $form['student[email]'] = 'foo.bar@example.com';

        // valider le formulaire
        $crawler = $client->submit($form);

        // suivre la redirection (ceci est nécessaire après la validation d'un formulaire)
        $crawler = $client->followRedirect();

        // vérifier que le client se trouve à l'URL `/student/`
        $this->assertSame('/student/', $client->getRequest()->getPathInfo());
        // vérifier que le texte `foo.bar@example.com` se trouve dans la balise `table`
        $this->assertContains('foo.bar@example.com', $crawler->filter('table')->text());
    }

    public function testEditStudent()
    {
        // créer un client
        $client = static::createClient();

        // se rendre à l'URL `/student/`
        $crawler = $client->request('GET', '/student/');

        // vérifier que le texte `foo.bar@example.com` se trouve dans la balise `table`
        $this->assertContains('foo.bar@example.com', $crawler->filter('table')->text());

        // trouver le lien (balise `a`) avec le texte `edit` dans la même ligne que la cellule qui contient le texte `foo.bar@example.com`
        $link = $crawler
            ->filter('td:contains("foo.bar@example.com")')
            ->parents()
            ->selectLink('edit')
            ->link()
        ;

        // cliquer sur le lien
        $crawler = $client->click($link);

        // trouver le bouton (balise `input type="submit"` ou `button`) avec le texte `Update`
        $form = $crawler->selectButton('Update')->form();

        // remplir le champ dont l'attribut `name` est `student[firstname]` avec la valeur `Lorem`
        $form['student[firstname]'] = 'Lorem';
        // remplir le champ dont l'attribut `name` est `student[lastname]` avec la valeur `Ipsum`
        $form['student[lastname]'] = 'Ipsum';
        // remplir le champ dont l'attribut `name` est `student[email]` avec la valeur `lorem.ipsum@example.com`
        $form['student[email]'] = 'lorem.ipsum@example.com';

        // valider le formulaire
        $crawler = $client->submit($form);

        // suivre la redirection (ceci est nécessaire après la validation d'un formulaire)
        $crawler = $client->followRedirect();

        // vérifier que le client se trouve à l'URL `/student/`
        $this->assertSame('/student/', $client->getRequest()->getPathInfo());
        // vérifier que le texte `lorem.ipsum@example.com` se trouve dans la balise `table`
        $this->assertContains('lorem.ipsum@example.com', $crawler->filter('table')->text());
    }

    public function testDeleteStudentForm()
    {
        // créer un client
        $client = static::createClient();

        // se rendre à l'URL `/student/`
        $crawler = $client->request('GET', '/student/');

        // vérifier que le texte `foo.bar@example.com` se trouve dans la balise `table`
        $this->assertContains('lorem.ipsum@example.com', $crawler->filter('table')->text());

        // trouver le lien (balise `a`) avec le texte `edit` dans la même ligne que la cellule qui contient le texte `lorem.ipsum@example.com`
        $link = $crawler
            ->filter('td:contains("lorem.ipsum@example.com")')
            ->parents()
            ->selectLink('edit')
            ->link()
        ;

        // cliquer sur le lien
        $crawler = $client->click($link);

        // trouver le bouton (balise `input type="submit"` ou `button`) avec le texte `Delete`
        $form = $crawler->selectButton('Delete')->form();

        // valider le formulaire
        $crawler = $client->submit($form);

        // suivre la redirection (ceci est nécessaire après la validation d'un formulaire)
        $crawler = $client->followRedirect();

        // vérifier que le client se trouve à l'URL `/student/`
        $this->assertSame('/student/', $client->getRequest()->getPathInfo());
        // vérifier que le texte `foo.bar@example.com` est absent dans la balise `table`
        $this->assertNotContains('lorem.ipsum@example.com', $crawler->filter('table')->text());
    }
}
