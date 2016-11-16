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
     * @Route("/blog/create/{id}", name="blog_create")
     */
    public function createAction($id)
    {
    	$blog = new Blog();
    	$blog->setTitre("titre".$id);
    	$blog->setDescription("description".$id);

		$blog2 = new Blog();
    	$blog2->setTitre("titre blog2-".$id);
    	$blog2->setDescription("description blog2-".$id);

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($blog);
    	$em->persist($blog2);
    	$id = $em->flush();

    	return new Response($blog->getId());
    }

    private function getBlogs($criteria, $tri) {
    	$filter = $criteria ? array("titre" => $criteria): array();
    	return $this->getDoctrine()
    	->getManager()
    	->getRepository('BlogBundle:Blog')
    	->findBy($filter, $tri);
    }

    private function getBlogById($id) {
    	foreach ($this->blogs as $blog)
	    {
	        if ($blog['id'] == $id)
	            return $blog;
	    }
    }
}
