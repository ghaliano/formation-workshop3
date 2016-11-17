<?php
namespace BlogBundle\Services;
use BlogBundle\Entity\Blog;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class BlogMailerService {
	public $em;
	
	public $formFactory;
	
	public function __construct($mailer) {
		$this->mailer = $mailer;
	}

    public function sendContactMessage($data) {
        $message = \Swift_Message::newInstance()
        ->setSubject($data['subject'])
        ->setBody($data['body'])
        ->setfrom($data['email'])
        ->setTo("vertilearn@gmail.com");

        return $this->mailer->send($message);
    }
}