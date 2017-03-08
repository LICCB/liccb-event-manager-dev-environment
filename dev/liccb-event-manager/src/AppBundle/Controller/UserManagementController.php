<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function usersAction()
    {
		/*
		Reference the User repository, when it exists
    	$eventArray = $this->getDoctrine()
		    ->getRepository('AppBundle:Event')
		    ->findAll();
		*/
		
        return $this->render('user_management.html.twig',);
    }
}
