<?php

namespace App\Controller;

use App\Controller\Traits\initForm;
use App\Entity\Post;
use App\Entity\Media;
use App\Form\PostType;
use App\Form\ImageMediaType;
use App\Form\VideoMediaType;
use App\Repository\MediaRepository;
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
    use initForm;

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
        $form = $this->initForm($request, PostType::class, $post);

        if ($form['form']->isSubmitted() && $form['form']->isValid()){
            manageKeywords($post);
            manageSubmission($post, $em);   

            // $this->addFlash('success', 'Added');
            return $this->redirectToRoute('app_post_media', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/create.html.twig', [
            'form' => $form['view']
        ]);
    }

    /**
     * @Route("/post/{id<\d+>}/media", name="app_post_media", methods="GET|POST")
     */
    public function media(Post $post, Request $request, EntityManagerInterface $em, MediaRepository $mediaRepository): Response
    {
        function isFileSizeValid(Media $media): bool 
        {
            define("MAX_IMAGE_SIZE", 1000000);
            if($media->getImageFile()->getSize()>=MAX_IMAGE_SIZE){
                return false;
            }else{
                return true;
            }
        }
        function isFileExtValid(Media $media): bool 
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
        function isUrlValid(Media $media): bool
        {
            $regexPattern = "/http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?‌​[\w\?‌​=]*)?/";
            $headers = get_headers($media->getUrl());
            $url = $media->getUrl();

            if(!filter_var($url, FILTER_VALIDATE_URL) || !preg_match($regexPattern, $url) || !strpos($headers[0], '200')){
                return false;
            }else{
                return true;
            }
        }
        function getPostMediaData(MediaRepository $mediaRepository, Post $post): array
        {
            $postMedia = $mediaRepository->findBy(['post' => $post]);
            $postMediaData = [];
            $imgSrcPrefix = "/uploads/post/";
            foreach ($postMedia as $media){
                array_push($postMediaData, [
                    "type" => $media->getType(),
                    "src" => $media->getType() === 1 ? $imgSrcPrefix . $media->getImageName() : $media->getUrl(),
                    "id" => $media->getId()
                ]); 
            } 
            return $postMediaData;
        }
        function convertToYoutubeEmbedded(Media $media): string // untested
        {
            function getVideoId(Media $media) {
                function clearArguments($videoId){
                    if (str_contains($videoId, "&")){
                        $videoId = explode("&", $videoId)[0];
                    }
                    return $videoId;
                }
    
                $url = $media->getUrl();
                $components = explode("/", $url);
                $lastComponent = $components[count($components)-1];
    
                if(str_contains($lastComponent, "watch?")){
                    $videoId = explode("v=", $lastComponent)[1];  
                } 
                return clearArguments($videoId);
            }
            function generateEmbeddedLink($videoId){
                $prefix = "https://www.youtube.com/embed/";
                return $prefix . $videoId;
            }
            
            $videoId = getVideoId($media);
            return generateEmbeddedLink($videoId);
        }
        function getMediaTypeFlashString($media): string
        {
            return $media->getType() == 1 ? " image " : " vidéo ";
        }
   
        $media = new Media;
        $imageForm = $this->initForm($request, ImageMediaType::class, $media);
        $videoForm = $this->initForm($request, VideoMediaType::class, $media);
        $media->setPost($post);

        $postMedia = getPostMediaData($mediaRepository, $post);

        if($imageForm['form']->isSubmitted() && $imageForm['form']->isValid() || $videoForm['form']->isSubmitted() && $videoForm['form']->isValid()){
            if($imageForm['form']->isSubmitted() && $imageForm['form']->isValid()){
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

            if($videoForm['form']->isSubmitted() && $videoForm['form']->isValid()){
                if(!isUrlValid($media)){
                    $this->addFlash("danger", "ERROR723 Le lien que vous avez entré est invalide.");
                    return $this->redirectToRoute('app_post_media', [
                        'id' => $post->getId()
                    ]);
                }

                $media->setUrl(convertToYoutubeEmbedded($media));
                $media->setType(2);
                $media->setImageName('video');
            }
            
            manageSubmission($media, $em);   

            $mediaTypeFlashString = getMediaTypeFlashString($media);
            $this->addFlash('success', 'Votre' . $mediaTypeFlashString . 'a été bien ajoutée !');
            return $this->redirectToRoute('app_post_media', [
                'id' => $post->getId()
            ]);
        }

        if (isset($_POST['to-delete'])){
            $mediaToDelete = $mediaRepository->findOneBy(['id' => $_POST['to-delete']]);
            $em->remove($mediaToDelete);
            $em->flush();

            $mediaTypeFlashString = getMediaTypeFlashString($mediaToDelete);
            $this->addFlash('success', 'Votre' . $mediaTypeFlashString . 'a été bien supprimée !');
            return $this->redirectToRoute('app_post_media', [
                'id' => $post->getId()
            ]);

            // to do : can't leave without one media
        }

        return $this->render('post/media.html.twig', [
            'imageForm' => $imageForm['view'],
            'videoForm' => $videoForm['view'],
            'media' => $postMedia,
            'postId' => $post->getId()
        ]);
    }
}
