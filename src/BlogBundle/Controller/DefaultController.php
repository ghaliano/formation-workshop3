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

class DefaultController extends Controller
{
    /**
     * @Route("/blog/list", name="blogs")
     */
    public function indexAction(Request $request)
    {
        return $this->render('BlogBundle:Default:index.html.twig', 
        	array('blogs' => $this->getBlogs(
        		$request->get('query'), 
        		array($request->get("sort", "id") => $request->get("order", "asc"))
    		))
    	);
    }

    /**
     * @Route("/blog/detail/{id}.json", name="blog_detail_json")
     */
    public function detailJsonAction($id)
    {
    	$response = new JsonResponse($this->getBlogById($id));

    	return $response;
    }

    /**
     * @Route("/blog/detail/{id}", name="blog_detail")
     */
    public function detailAction($id)
    {
        return $this->render('BlogBundle:Default:detail.html.twig', array('blog' => $this->getBlogById($id)));
    }

    /**
     * @Route("/blog/list.json", name="blogs_json")
     * @Method("get")
     */
    public function indexJsonAction()
    {
    	$response = new Response();
    	$response->headers->set("Content-Type", "application/json");
    	$response->setContent(json_encode($this->blogs));

    	return $response;
    }

    /**
     * @Route("/blog/create", name="blog_create")
     */
    public function createAction(Request $request)
    {
        $blog = new Blog();
        $form = $this->createFormBuilder($blog)
        ->add('titre', TextType::class)
        ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'placeHolder' => 'Description') ))
        ->add('createdAt', DateType::class)
        ->getForm();

        //Binding
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('blogs');
        }

        return $this->render('BlogBundle:Default:create.html.twig', array('form' => $form->createView()));  
    }

    /**
     * @Route("/blog/edit/{id}", name="blog_edit")
     */
    public function editAction(Request $request, $id)
    {
        $blog = $this->getBlogById($id);
        $form = $this->createFormBuilder($blog)
        ->add('titre', TextType::class)
        ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'placeHolder' => 'Description') ))
        ->add('createdAt', DateType::class)
        ->getForm();

        //Binding
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('blogs');
        }

        return $this->render('BlogBundle:Default:create.html.twig', array('form' => $form->createView()));  
    }

    /**
     * @Route("/blog/delet/{id}", name="blog_delete")
     */
    public function deleteAction($id)
    {
        $blog = $this->getBlogById($id);
        if (!$blog) {
            $this->addFlash('error', "Blog introuvable " . $id);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blog);
            $em->flush();

            $this->addFlash('success', 'Suppression effectué avec succees !');
        }
        return $this->redirectToRoute('blogs');
    }

    private function getBlogs($criteria, $tri) {
    	$filter = $criteria ? array("titre" => $criteria): array();
    	return $this->getDoctrine()
    	->getManager()
    	->getRepository('BlogBundle:Blog')
    	->findBy($filter, $tri);
    }

    private function getBlogById($id) {
        return $this->getDoctrine()
        ->getManager()
        ->getRepository('BlogBundle:Blog')
        ->find($id);
    }
}
