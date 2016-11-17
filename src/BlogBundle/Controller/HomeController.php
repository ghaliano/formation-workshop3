<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use BlogBundle\Form\ContactType;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        return $this->render('BlogBundle:Home:index.html.twig', 
        	array('blogs' => $this->get('blog.service')->getBlogs(array(), array(), 1, 1))
    	);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
    	$form =  $this->createForm(ContactType::class);
        //Binding
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('blog.mailer.service')->sendContactMessage($form->getData());

            $this->addFlash('success', 'Message envoyé avec succees !');

            return $this->redirectToRoute('home');
        }

        return $this->render('BlogBundle:Home:contact.html.twig', array('form' => $form->createView()));
    }
}