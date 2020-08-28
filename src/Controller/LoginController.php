<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        return $this->render('login/index.html.twig');
    }

    /**
     * @Route("/logon", name="logon")
     */
    public function logon()
    {
        return $this->redirectToRoute('accueil');
    }
}
