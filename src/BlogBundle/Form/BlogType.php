<?php
namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $option){
		parent::buildForm($builder, $option);
		$builder
		->add('titre', TextType::class, ['error_bubbling' => false])
        ->add('category', EntityType::class, array(
            'class' => 'BlogBundle:Category',

        ))
        ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'placeHolder' => 'Description') ))
        ->add('createdAt', DateType::class);
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults(array('csrf_protection' => false));
	}
}