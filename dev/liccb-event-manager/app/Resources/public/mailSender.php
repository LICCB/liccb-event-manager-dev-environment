public function mailSender($name, $recipient, $subject, $template, $event, $date, $time, $newDate = False, $newTime = False)
{
    $message = \Swift_Message::newInstance()
        ->setSubject($subject)
		->setFrom('reminders@licboathouse.org')
		->setTo($recipient)
		->setBody(
			$this->renderView(
			$template,
			if($newDate == False)
				array('name' => $name, 'event' => $event, 'date' => $date, 'time' => $time)
			else
				array('name' => $name, 'event' => $event, 'date' => $date, 'time' => $time, 'newDate' = $newDate, 'newTime' = $newTime)
			),
		)
	;
	$this->get('mailer')->send($message);
	return $this->render(...);
}