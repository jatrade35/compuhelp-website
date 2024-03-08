<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Post;
use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;

class MainController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
        $page = 'welcome.html.twig';
        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $path = "compuhelp/";
                $parameters['ha3key'] = $_ENV['HA3_KEY'];
                $repository = $entityManager->getRepository(Service::class);
                $parameters['services'] = $repository->findAll();
                $repository = $entityManager->getRepository(Post::class);
                $parameters['recent_posts'] = $repository->findBy( 
                                                                   [],
                                                                   ['datetimePosted' => 'DESC'],
                                                                   3,
                                                                   0);
                $parameters['activepage'] = "Home";
                break;
            case 'health2wealthcoaching.compuhelp-webdesign.ca':
                $path = "health2wealth/";
                break;
            case 'formatio.compuhelp-webdesign.ca':
                $path = "formatio/";
                break;
            default:
                $page = '404.html.twig';
                $path = "/error/compuhelp/";
        }            

        $parameters['breadcrumbs'] = false;        

        return $this->render($path . $page, $parameters );
    }

    #[Route('/about-me', name: 'about me')]
    public function about(): Response
    {
        $ha3key = $_ENV['HA3_KEY'];
        $page = 'about-me.html.twig';
        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $path = "compuhelp/";
                break;
            default:
                $path = "compuhelp/";
                $page = '404.html.twig';
        }            
        return $this->render($path . $page, ['preloader_class'=>"rd-navbar-fixed-linked",
                                             'header_class'=>"",
                                             'activepage'=>"AboutMe",
                                             'ha3key'=>$ha3key,
                                             'breadcrumbs'=>false,]);
    }

    #[Route('/blog', name: 'blog')]
    public function blog(EntityManagerInterface $entityManager): Response
    {
        $ha3key = $_ENV['HA3_KEY'];
        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $path = "compuhelp/";
                $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
                $breadcrumbs = false;

                if( isset($_GET['post']))
                {
                    $postId = $_GET['post'];
                    $repository = $entityManager->getRepository(Post::class);
                    $post = $repository->find($postId);
                    if( !is_null($post))
                    {
                        $page = 'article.html.twig';
                        $breadcrumbs = true;

                        $parameters['posttitle'] = $post->getTitle();
                        $parameters['postauthor'] = $post->getAuthor();
                        $parameters['postdatetime'] = $post->getTimePosted();
                        $parameters['postparagraphs'] = $post->getParagraphs();
                        $parameters['postquote'] = ""; //$post->getQuote();
                        $parameters['imagepath'] = $post->getImagePath();
                        $parameters['comments'] = $post->getComments();
                        $parameters['recentposts'] = $repository->getRecentPosts($post->getId());
                    }
                    else
                    {
                        $page = '404.html.twig';
                        $path = '/error/compuhelp/';
                    }
                }
                else 
                {
                    $page = 'blog.html.twig';
                }    
                
                break;
            default:
                $path = "compuhelp/";
                $page = '404.html.twig';
        }
        $parameters['breadcrumbs'] = $breadcrumbs;        
        $parameters['activepage'] = "Blog";
        $parameters['ha3key'] = $ha3key;
       
        return $this->render($path . $page, $parameters);
    }
}