<?php
namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $option){
		$builder
		->add('subject', BaseType\TextType::class)
		->add('email', BaseType\EmailType::class, array('attr' => array('class' => 'form-control', 'placeHolder' => 'Votre email')))
        ->add('body', BaseType\TextareaType::class, array('attr' => array('class' => 'form-control', 'placeHolder' => 'Contenu du message')));
	}
}