<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializationContext;

class RestBlogController extends Controller
{
    /**
     * @Route("/api/blog/list.json", name="blogs_json")
     * @Method("get")
     */
    public function indexJsonAction(Request $request)
    {
		$length = $request->get('length', 10);
		$start = ($request->get('start', 0)/$length);
		//$start = $start?$start:$start+1;

    	$columns = [
    		'id','titre','description','category','createdAt'
    	];

    	$data = [
    		'data' => []
    	];

    	$order = $request->get("order")?$request->get("order"):[
    		['column' => 0, 'dir' => 'asc']
    	];

    	$search = $request->get('search')?$request->get('search'):["value" => Null];

    	$blogs = $this->get('blog.service')->getBlogs(
    		['query' => $search['value']],
    		$start,
    		$length,
    		true
		);
        
        $data['recordsTotal'] = $data['recordsFiltered'] = count($this->get('blog.service')->getBlogs(
    		['query' => $search['value']]
		));
    
		$data['data'] = $blogs;

    	$response = new Response();
    	$response->headers->set("Content-Type", "application/json");
    	
    	$response->setContent($this->get('jms_serializer')->serialize(
            $data, 'json', SerializationContext::create()->setGroups(array('datatable'))
        ));

    	return $response;
    }

    /**
     * @Route("/api/blog/detail/{id}.json", name="blog_detail_json")
     */
    public function detailJsonAction($id)
    {
        $response = new Response();
        $response->headers->set("Content-Type", "application/json");
        $response->setContent($this->get('jms_serializer')->serialize(
            $this->get('blog.service')->getBlogById($id),
            "json",
            SerializationContext::create()->setGroups(array('datatable'))
        ));

        return $response;
    }

    /**
     * @Route("/api/blog/create.json", name="blog_create_json")

     */
    public function createJsonAction(Request $request)
    {
        $blog = new Blog();

        $form = $this->get('blog.service')->createForm($blog, ['validation_groups' => 'create']);
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('blog.service')->createBlog($blog);
            $result = ['success' => true, 'message' => 'Création effectué avec succès.'];
        } else {
            $result = ['success' => false, 'message' => 'Création échoué', 'errors' => $this->getErrorMessages($form)];
        }

        return new JsonResponse($result);
    }


    /**
     * @Route("/api/blog/edit/{id}.json", name="blog_edit_json")

     */
    public function editJsonAction(Request $request, $id)
    {
        $blog = $this->get('blog.service')->getBlogById($id);

        $form = $this->get('blog.service')->createForm($blog, ['validation_groups' => 'edit']);
        
        $form->handleRequest($request);

        $this->get('logger')->log('');

        if ($form->isValid()) {
            $this->get('blog.service')->editBlog($blog);
            $result = ['success' => true, 'message' => 'Edition effectué avec succès.'];
        } else {
            $result = ['success' => false, 'message' => 'Edition échoué.', 'errors' => $this->getErrorMessages($form)];
        }

        return new JsonResponse($result);
    }

    private function getErrorMessages( $form) {      
        $errors = array();
        foreach ($form->getErrors(true, true) as $error) {
            // My personnal need was to get translatable messages
            // $errors[] = $this->trans($error->current()->getMessage());
            $errors[] = $error->getMessage();
        }

        return $errors;
    }
}