<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Media;
use App\Form\PostType;
use App\Entity\PostCategory;
use App\Form\ImageMediaType;
use App\Form\VideoMediaType;
use App\Repository\PostRepository;
use App\Controller\Traits\initForm;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostCategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    use initForm;

    /**
     * @Route("/post", name="app_post", methods={"GET"})
     */
    public function index(PostCategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $isEmpty = true;
        $posts = [];
        foreach ($categoryRepository->findBy([], ['displayOrder' => "ASC"]) as $category){
            $posts[$category->getName()] = [];
            foreach ($category->getPosts() as $post){
                if (!empty($post->getMedia()[0])){
                    array_push($posts[$category->getName()], $post);
                    if (count($posts[$category->getName()]) == 3){
                        break;
                    }
                }
            }
        }

        foreach ($posts as $category){
            if (!empty($category)){
                $isEmpty = false;
                break;
            }
        }

        if ($isEmpty){
            $posts = [];
        } else {
            foreach ($posts as $key=>$category){
                if (empty($category)){
                    unset($posts[$key]);
                }
            }
        }      
        
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/create", name="app_post_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post;

        // could be refactored with edit route
        require_once('Requires/manageSubmission.php');
        require_once('Requires/manageKeywords.php');

        $form = $this->initForm($request, PostType::class, $post);

        if ($form['form']->isSubmitted() && $form['form']->isValid()){
            manageKeywords($post);
            manageSubmission($post, $em);   

            return $this->redirectToRoute('app_post_media', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/create.html.twig', [
            'form' => $form['view']
        ]);
    }  

    /**
     * @Route("/post/{id<\d+>}/edit", name="app_post_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em, Post $post): Response
    {
        // could be refactored with create route
        require_once('Requires/manageSubmission.php');
        require_once('Requires/manageKeywords.php');

        $form = $this->initForm($request, PostType::class, $post);

        if ($form['form']->isSubmitted() && $form['form']->isValid()){
            manageKeywords($post);
            manageSubmission($post, $em);   

            return $this->redirectToRoute('app_post_media', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form['view']
        ]);
    }  

    /**
     * @Route("/post/{id<\d+>}/save", name="app_post_save", methods={"POST"})
     */
    public function save(Post $post): Response
    {
        if (empty($post->getMedia()[0])){
            $this->addFlash('danger', 'ERROR724 Veuillez ajouter une image ou une vidéo au minimum.');
            return $this->redirectToRoute('app_post_media', [
                'id' => $post->getId()
            ]); 
        }

        $this->addFlash('success', 'Votre publication a été bien enregistrée !');
        return $this->redirectToRoute('app_post'); 
    }

    /**
     * @Route("/post/{id<\d+>}", name="app_post_view", methods={"GET"})
     */
    public function view(Post $post): Response
    {

        return $this->render('post/view.html.twig', [
            'post' => $post,
        ]);
    }
}
