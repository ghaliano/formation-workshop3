<?php
namespace BlogBundle\Services;
use BlogBundle\Entity\Blog;
use BlogBundle\Form\BlogType;

class BlogService {
	public $em;
	
	public $formFactory;
	
	public function __construct($em, $formFactory) {
		$this->em = $em;
		$this->formFactory = $formFactory;
	}

    public function getBlogs($data, $page = 0, $max = NULL, $getResult = true) {
        $qb = $this->em->createQueryBuilder(); 
        $query = isset($data['query']) && $data['query']?$data['query']:null; 
 
        $qb 
            ->select('b') 
            ->from('BlogBundle:Blog', 'b') 
        ; 
 
        if ($query) { 
            $qb 
                ->andWhere('b.titre like :query OR b.description like :query') 
                ->setParameter('query', $query."%") 
            ; 
        }  
        if ($max) { 
            $preparedQuery = $qb->getQuery() 
                ->setMaxResults($max) 
                ->setFirstResult($page * $max) 
            ; 
        } else { 
            $preparedQuery = $qb->getQuery(); 
        } 
 
        return $getResult?$preparedQuery->getResult():$preparedQuery; 
    }

    public function createBlog(Blog $blog){
        $this->em->persist($blog);
        $this->em->flush();

     	return $blog;   
    }

    public function editBlog(Blog $blog){
        $this->em->persist($blog);
        $this->em->flush();

     	return $blog;   
    }

    public function deleteBlog(Blog $blog){
        $this->em->remove($blog);
        $this->em->flush();

     	return $blog;  
    }

    public function getBlogById($id) {
        return $this->em
        ->getRepository('BlogBundle:Blog')
        ->find($id);
    }

    public function createForm(Blog $blog, $options){
    	return $this->formFactory->create(BlogType::class, $blog, $options);
    }
}