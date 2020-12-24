<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class HomeController extends AbstractController
{
    private $em; 

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, ArticleRepository $repoArticle): Response
    {
        /*
        $article = new Article;
        $form = $this->createForm(ArticleType::class, $article); 

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $JsonResponse = file_get_contents("https://unfurl.io/api/v2/preview?api_token=xC3jXNC2vXfiOt6bpZcq6cPs3ncJAZYxIX7YQfgGrXS9HbhJRVcARc0ZbTJP&url=".$form->get('link')->getData());
            $response = json_decode($JsonResponse, true);
            
            if(isset($response['open_graph']['images'][0]['url'])){
                $image = $response['open_graph']['images'][0]['url'];
                $current = file_get_contents($image);
                 $filesystem = new Filesystem();
                $projectDir = $this->getParameter('kernel.project_dir');
                
                
                if($form->get('title')->getData() == '')
                    isset($response['title']) ? $title = $response['title']: 'Titre';
                else
                    $title = $form->get('title')->getData();

                $filesystem->touch($projectDir.'/public/upload/img/'.$this->getUser()->getId().'/'.str_replace(' ', '_', $title).'.png');
                file_put_contents(
                            $projectDir.'/public/upload/img/'.$this->getUser()->getId().'/'.str_replace(' ', '_', $title).'.png',
                            $current
                );

                $img = '/'.$this->getUser()->getId().'/'.str_replace(' ', '_',$title).'.png';
            }else{
                $img= null;
                $title = "Titre";
            }
            
            $article->setImgUrl($img);
            $article->setTitle($title);
            
            $this->em->persist($article);
            $this->em->flush();
        }
        */
        $liste = $repoArticle->findBy([], ['creatAt' => 'DESC']);
        return $this->render('home/index.html.twig', [
        ]);
    }
}
