<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function splashAction()
    {

        return $this->render('splash.html.twig');
    }
}
