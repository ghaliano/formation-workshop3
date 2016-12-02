<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Blog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializationContext;

class RestfullBlogController extends FOSRestController
{
    /**
     * @REST\Get("list")
     * @REST\View(serializerGroups={"datatable"})
     */
    public function indexJsonAction(Request $request)
    {
        $length = $request->get('length', 10);
        $start = ($request->get('start', 0)/$length);

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
        
        return $data;
    }

    /**
     * @REST\Get("detail/{id}")
     * @REST\View(serializerGroups={"default"})
     */
    public function detailAction($id)
    {
        return $this->get('blog.service')->getBlogById($id);
    }

    /**
     * @REST\Post("create")
     */
    public function createAction(Request $request)
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

        return $result;
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