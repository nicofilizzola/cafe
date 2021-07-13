<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Media;
use App\Form\PostType;
use App\Form\ImageMediaType;
use App\Form\VideoMediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

function manageSubmission($entity, EntityManagerInterface $em){
    $entity->updateTimestamp();
    $em->persist($entity);
    $em->flush();
}

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/post/create", name="app_post_create", methods="GET|POST")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        function manageKeywords(Post $post){
            $keywords = explode(',', $post->getKeywords()[0]);
            for ($i=0; $i<count($keywords); $i++) {
                $keywords[$i] = trim($keywords[$i]);
            }
            $post->setKeywords($keywords);
        }

        $post = new Post;
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        $formView = $form->createView();

        if ($form->isSubmitted() && $form->isValid()){
            manageKeywords($post);
            manageSubmission($post, $em);   

            $this->addFlash('success', 'Added');
            return $this->redirectToRoute('app_post_media', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/create.html.twig', [
            'form' => $formView
        ]);
    }

    /**
     * @Route("/post/{id<\d+>}/media", name="app_post_media", methods="GET|POST")
     */
    public function media(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        function isFileSizeValid($media): bool 
        {
            define("MAX_IMAGE_SIZE", 1000000);
            if($media->getImageFile()->getSize()>=MAX_IMAGE_SIZE){
                return false;
            }else{
                return true;
            }
        }
        function isFileExtValid($media): bool 
        {
            define("AVAILABLE_EXTENSIONS", [
                "png",
                "jpg",
                "jpeg"
            ]);
            $fileName=explode(".", $media->getImageFile()->getOriginalName());
            $fileExt=strtolower($fileName[count($fileName)-1]);

            if(!in_array($fileExt, AVAILABLE_EXTENSIONS)){
                return false;
            }else{
                return true;
            }
        }
        function isUrlValid($media): bool
        {
            $regexPattern = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/";
            $url = $media->getUrl();
            if(!filter_var($url, FILTER_VALIDATE_URL) || !preg_match($regexPattern, $url)){
                return false;
            }else{
                return true;
            }
        }

        $media = new Media;
        $videoForm = $this->createForm(VideoMediaType::class, $media);
        $imageForm = $this->createForm(ImageMediaType::class, $media);
        $videoForm->handleRequest($request);
        $imageForm->handleRequest($request);
        $imageFormView = $imageForm->createView();
        $videoFormView = $videoForm->createView();
        $media->setPost($post);

        if($imageForm->isSubmitted() && $imageForm->isValid() || $videoForm->isSubmitted() && $videoForm->isValid()){
            if($imageForm->isSubmitted() && $imageForm->isValid()){
                if(!isFileSizeValid($media)){
                    $this->addFlash('danger', 'ERROR721 Vos fichiers ne doivent pas dépasser 1 Mo.');
                    return $this->redirectToRoute('app_post_media', [
                        'id' => $post->getId()
                    ]);
                }
                if(!isFileExtValid($media)){
                    $this->addFlash("danger", "ERROR722 Le format de votre fichier est invalide. Veuillez utiliser un fichier PNG, JPG ou JPEG.");
                    return $this->redirectToRoute('app_post_media', [
                        'id' => $post->getId()
                    ]);
                }

                $media->setType(1);
            }

            if($videoForm->isSubmitted() && $videoForm->isValid()){
                if(!isUrlValid($media)){
                    $this->addFlash("danger", "ERROR723 Le lien que vous avez entré est invalide.");
                    return $this->redirectToRoute('app_post_media', [
                        'id' => $post->getId()
                    ]);
                }

                $media->setType(2);
            }
            
            manageSubmission($media, $em);   

            $this->addFlash('success', 'Votre publication a été bien ajoutée !');
            return $this->redirectToRoute('app_post_media', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/media.html.twig', [
            'imageForm' => $imageFormView,
            'videoForm' => $videoFormView
        ]);
    }
}
