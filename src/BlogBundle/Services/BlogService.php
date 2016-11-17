<?php
namespace BlogBundle\Services;
use BlogBundle\Entity\Blog;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class BlogService {
	public $em;
	
	public $formFactory;
	
	public function __construct($em, $formFactory) {
		$this->em = $em;
		$this->formFactory = $formFactory;
	}

	public function getBlogs($criteria = array(), $options = array(), $limit = 100, $offset = 0) {
    	$filter = $criteria ? array("titre" => $criteria): array();
    	
    	return $this->em
    	->getRepository('BlogBundle:Blog')
    	->findBy($filter, $options, $limit, $offset);
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

    public function createForm(Blog $blog){
    	return $this->formFactory->createBuilder(FormType::class, $blog)
        ->add('titre', TextType::class)
        ->add('category', EntityType::class, array(
            'class' => 'BlogBundle:Category',

        ))
        ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'placeHolder' => 'Description') ))
        ->add('createdAt', DateType::class)
        ->getForm();
    }
}