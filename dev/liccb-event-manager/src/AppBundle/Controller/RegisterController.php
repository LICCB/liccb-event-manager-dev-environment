<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use AppBundle\Form\RegistrationForm;
use AppBundle\Entity\Registration;
use AppBundle\Entity\Registrant;
use AppBundle\Entity\Party;
use AppBundle\Entity\Party_participant_list;
use Symfony\Component\HttpFoundation\Request;


class RegisterController extends Controller
{
    public function registerAction(Request $request)
    {
    	$formData = new Registration(); // Form data class

        $flow = $this->get('AppBundle.form.flow.registration'); // Flow service id
	    $flow->bind($formData);

	    // Form current step
        $form = $flow->createForm();
        if($flow->isValid($form)){
            $flow->saveCurrentStepData($form);

            if($flow->nextStep()){
            	// Form for next step
                $form = $flow->createForm();
            } else {
            	// Flow finished, save to DB
            	$em = $this->getDoctrine()->getManager();

	            // Fill registrant info, might wanna add inherit_data
	            $registrant = new Registrant();
	            $registrant->setRegistrantEmail($formData->getEmail());
	            $registrant->setOver18($formData->getOver18());
	            $registrant->setHasSwimExperience($formData->getCanSwim());
	            $registrant->setHasBoatExperience($formData->getBoatExperience());
	            $registrant->setHasCprCertification($formData->getCprCertification());
	            $registrant->setFullName($formData->getFullName());
	            $registrant->setPhone($formData->getPhoneNumber());
	            $registrant->setEmergencyContactName($formData->getEmergencyContactName());
	            $registrant->setEmergencyContactPhone($formData->getEmergencyContactNumber());
	            $registrant->setParticipantType($formData->getRegistrationType());
	            $registrant->setZip(00000);
	            $registrant->setIsPriorVolunteer(false);
	            $registrant->setRoleFamiliarity(false);
	            $registrant->setVehicleCapacity($formData->getBoatSeats());

	            // Fill party info
	            $party = new Party();
	            $party->setRegistrant($registrant);
	            $party->setOrgEvent($formData->getEventSelection());
	            $party->setNumSeats($formData->getBoatSeats());
	            //$party->setWantsPairedWithBoater($formData->getPairingBoater() === "");
	            $party->setWantsPairedWithBoater(false);
	            $party->setSelectionStatus("Unselected");
	            $party->setSelectionStatusReason("None");
	            $party->setConfirmedAttending(false);
	            $party->setNumActuallyAttended(0);

	            $em->persist($registrant);
	            $em->persist($party);
	            $em->flush();

	            // Send email now that DB is saved
	            $message = \Swift_Message::newInstance()
		            ->setSubject("LICBoathouse Event Registration")
		            ->setFrom("test@test.com")
		            ->setTo($party->getRegistrantEmail())
		            ->setBody(
		            	$this->renderView('email/registered.html.twig', array(
		            		'name' => $party->getRegistrant()->getFullName(),
				            'event' => $formData->getEventSelection(),
			            )),
			            'text/html'
		            )
		            ;
	            $this->get('mailer')->send($message);

                $flow->reset(); // Remove step data from session

	            $request->getSession()->set('name', $registrant->getFullName());
	            $request->getSession()->set('eventName', $formData->getEventSelection()->getOrgEventName());
	            return $this->redirectToRoute('registerconfirm'); // redirect
            }
        }

        return $this->render('register/register.html.twig', array(
            'form' => $form->createView(),
            'flow' => $flow,
        ));
    }

    public function confirmAction(Request $request){
    	$name = $request->getSession()->get('name');
    	$eventName = $request->getSession()->get('eventName');
    	return $this->render('register/confirm.html.twig', array(
    		'name' => $name,
		    'eventName' => $eventName,
	    ));
    }


}
