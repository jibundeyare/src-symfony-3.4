<?php
// src/Controller/MainController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;

/**
 * @Route("/")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_index")
     */
    public function index(Request $request)
    {
        return $this->render('main/index.html.twig', [
            // ...
        ]);
    }

    /**
     * @Route("/hello/{name}", name="main_hello")
     */
    public function hello(Request $request, $name)
    {
        $greeting = "Hello {$name}!";

        return $this->render('main/hello.html.twig', [
            'greeting' => $greeting,
        ]);
    }

    /**
     * @Route("/secured", name="main_secured")
     */
    public function secured(Request $request)
    {
        return $this->render('main/secured.html.twig', [
            // ...
        ]);
    }
}
