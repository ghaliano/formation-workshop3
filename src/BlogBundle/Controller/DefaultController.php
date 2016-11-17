<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/blog/list", name="blogs")
     */
    public function indexAction(Request $request)
    {
        return $this->render('BlogBundle:Default:index.html.twig', 
        	array('blogs' => $this->get('blog.service')->getBlogs(
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
    	$response = new JsonResponse($this->get('blog.service')->getBlogById($id));

    	return $response;
    }

    /**
     * @Route("/blog/detail/{id}", name="blog_detail")
     */
    public function detailAction($id)
    {
        return $this->render('BlogBundle:Default:detail.html.twig', array('blog' => $this->get('blog.service')->getBlogById($id)));
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
        $form = $this->get('blog.service')->createForm($blog);

        //Binding
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('blog.service')->createBlog($blog);

            //return $this->redirectToRoute('blogs');
        }

        return $this->render('BlogBundle:Default:create.html.twig', array('form' => $form->createView()));  
    }

    /**
     * @Route("/blog/edit/{id}", name="blog_edit")
     */
    public function editAction(Request $request, $id)
    {
        $blog = $this->get('blog.service')->getBlogById($id);
        $form = $this->get('blog.service')->createForm($blog);

        //Binding
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('blog.service')->editBlog($blog);
            return $this->redirectToRoute('blogs');
        }

        return $this->render('BlogBundle:Default:create.html.twig', array('form' => $form->createView()));  
    }

    /**
     * @Route("/blog/delet/{id}", name="blog_delete")
     */
    public function deleteAction($id)
    {
        $blog = $this->get('blog.service')->getBlogById($id);
        if (!$blog) {
            $this->addFlash('error', "Blog introuvable " . $id);
        } else {
            $this->get('blog.service')->deleteBlog($blog);

            $this->addFlash('success', 'Suppression effectué avec succees !');
        }
        return $this->redirectToRoute('blogs');
    }
}
