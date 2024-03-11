<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\Service;
use App\Entity\Testimonial;
use Doctrine\ORM\EntityManagerInterface;

class MainController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
        $page = 'home.html.twig';
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
    public function about(EntityManagerInterface $entityManager): Response
    {
        $ha3key = $_ENV['HA3_KEY'];
        $page = 'about-me.html.twig';
        $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>""];


        $repository = $entityManager->getRepository(Testimonial::class);
        $testimonials = $repository->findAll();
        $parameters['testimonials'] = $testimonials;
        $parameters['layout'] = 2;

        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $path = "compuhelp/";
                break;
            default:
                $path = "compuhelp/";
                $page = '404.html.twig';
        }            

        $parameters['breadcrumbs'] = false;        
        $parameters['activepage'] = "AboutMe";
        $parameters['ha3key'] = $ha3key;

        return $this->render($path . $page, $parameters);
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
                $repository = $entityManager->getRepository(Post::class);


                if( isset($_GET['post']))
                {
                    $postId = $_GET['post'];
                    $post = $repository->find($postId);
                    if( !is_null($post))
                    {
                        $page = 'single-post.html.twig';
                        $breadcrumbs = true;

                        $parameters['post'] = $post;
                        $parameters['recentposts'] = $repository->getRecentPosts(["p.id != " . $post->getId()],4);
                    }
                    else
                    {
                        $page = '404.html.twig';
                        $path = '/error/compuhelp/';
                    }
                }
                else
                {
                    $count = (isset($_GET['count'])? $_GET['count']:3);
                    $parameters['count'] =  $count;

                    $criteria=[];
                    $filterType = (isset($_GET['filterType'])? $_GET['filterType']:"");
                    switch ($filterType)
                    {
                        case 'category':
                            if(isset($_GET['filterValue']))
                            {
                                $filterValue = $_GET['filterValue'];
                                $criteria[] = "p.category_id = $filterValue";
                            }
                            break;
                        case 'datetimePosted':
                            $filter[$filterType] = $_GET['filterValue'];                                
                            break;
                    }
                    $parameters['posts'] = $repository->getRecentPosts( $criteria, $count); 
                    $parameters['postCount']= count($parameters['posts']);
                    $repository = $entityManager->getRepository(PostCategory::class);
                    $parameters['categories'] = $repository->findAll();
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

    #[Route('/search-results', name: 'search')]
    public function search(EntityManagerInterface $entityManager): Response
    {
        $ha3key = $_ENV['HA3_KEY'];
        $parameters=[];
        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $path = "compuhelp/";
                $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
                $breadcrumbs = false;
                break;
            default:
                $path = "compuhelp/";
                $page = '404.html.twig';
        }
        $page = 'search-results.html.twig';
        $parameters['breadcrumbs'] = $breadcrumbs;        
        $parameters['activepage'] = "Pages";
        $parameters['ha3key'] = $ha3key;

        return $this->render($path . $page, $parameters);
    }

    #[Route('/services', name: 'services')]
    public function services(EntityManagerInterface $entityManager): Response
    {
        $ha3key = $_ENV['HA3_KEY'];
        $parameters=[];
        $repository = $entityManager->getRepository(Service::class);

        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $path = "compuhelp/";
                $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
                $breadcrumbs = false;

                if( isset($_GET['id']))
                {
                    $serviceId = $_GET['id'];
                    $service = $repository->find($serviceId);
                    if( !is_null($service))
                    {
                        $page = 'single-service.html.twig';
                        $breadcrumbs = false;

                        $parameters['service'] = $service;
                    }
                    else
                    {
                        $page = '404.html.twig';
                        $path = '/error/compuhelp/';
                    }
                }
                else
                {
                    $parameters['services'] = $repository->findAll(); 
                    $page = 'services.html.twig';
                }
                
                $repository = $entityManager->getRepository(Testimonial::class);
                $testimonials = $repository->findAll();
                $parameters['testimonials'] = $testimonials;
                $parameters['layout'] = 1;
        
                break;
            default:
                $path = "compuhelp/";
                $page = '404.html.twig';
        }
        $parameters['breadcrumbs'] = $breadcrumbs;        
        $parameters['activepage'] = "Page";
        $parameters['ha3key'] = $ha3key;

        return $this->render($path . $page, $parameters);
    }

    #[Route('/contacts', name: 'contacts')]
    public function contacts(): Response
    {
        $ha3key = $_ENV['HA3_KEY'];
        $parameters=[];
        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $path = "compuhelp/";
                $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
                $breadcrumbs = false;
                break;
            default:
                $path = "compuhelp/";
                $page = '404.html.twig';
        }
        $page = 'contacts.html.twig';
        $parameters['breadcrumbs'] = $breadcrumbs;        
        $parameters['activepage'] = "Pages";
        $parameters['ha3key'] = $ha3key;

        return $this->render($path . $page, $parameters);
    }

}

