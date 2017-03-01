<?php

namespace AppBundle\Controller;

use AppBundle\Form\EventEdit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    public function showAction($id)
    {
    	$event = $this->getDoctrine()
		    ->getRepository('AppBundle:Org_event')
		    ->find($id);

        return $this->render('event/show.html.twig', array(
        'event' => $event
        ));
    }

    public function editAction(Request $request, $id){
    	$event = $this->getDoctrine()
		    ->getRepository('AppBundle:Event')
		    ->find($id);

    	$form = $this->createForm(EventEdit::class, $event);

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){
    		$event = $form->getData();

    		$em = $this->getDoctrine()->getManager();
    		$em->persist($event);
    		$em->flush();

    		return $this->redirectToRoute('event_show',array(
    			'id' => $id
		    ));
	    }

	    return $this->render('event/edit.html.twig', array(
	    	'form' => $form->createView(),
	    ));
    }
}
