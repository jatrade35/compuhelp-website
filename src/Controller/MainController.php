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
        $locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        if(substr($locale, 0, 2) == 'fr')
        {
            $frenchMainController = new FrenchMainController;
            return $this->redirect("/fr/accueil");
        }
        else
        {
            $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
            $parameters['ha3key'] = $_ENV['HA3_KEY'];
            $repository = $entityManager->getRepository(Service::class);
            $parameters['services'] = $repository->findAllByLanguage('en');
            $repository = $entityManager->getRepository(Post::class);
            $parameters['recent_posts'] = $repository->findBy( 
                                                                [],
                                                                ['datetimePosted' => 'DESC'],
                                                                3,
                                                                0);
            $parameters['activepage'] = "Home";
            $parameters['breadcrumbs'] = false;
            $parameters['langSelector'] = "Français";
            $parameters['langSelectorURL'] = "/fr/accueil";
            $parameters['title'] = "Home";
            $parameters['quote'] = "REQUEST A QUOTE";

            return $this->render("compuhelp/home.html.twig", $parameters );
        }
    }

    #[Route('/about-me', name: 'about me')]
    public function about(EntityManagerInterface $entityManager): Response
    {
        $ha3key = $_ENV['HA3_KEY'];
        $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>""];


        $repository = $entityManager->getRepository(Testimonial::class);
        $testimonials = $repository->findAll();
        $parameters['testimonials'] = $testimonials;
        $parameters['layout'] = 2;
        $parameters['breadcrumbs'] = false;        
        $parameters['activepage'] = "AboutMe";
        $parameters['ha3key'] = $ha3key;
        $parameters['langSelector'] = "Français";
        $parameters['langSelectorURL'] = "/fr/about-me";
        $parameters['title'] = "About Me";
        $parameters['quote'] = "REQUEST A QUOTE";

        return $this->render("compuhelp/about-me.html.twig", $parameters);
    }

    #[Route('/blog', name: 'blog')]
    public function blog(EntityManagerInterface $entityManager): Response
    {
        $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
        $breadcrumbs = false;
        $parameters['langSelector'] = "Français";
        $parameters['langSelectorURL'] = "/fr/blog";
        $parameters['title'] = "René's blog";
        $parameters['quote'] = "REQUEST A QUOTE";
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
                $parameters['langSelectorURL'] = "/fr/blog?post=" . $postId;
                $parameters['recentposts'] = $repository->getRecentPosts(["p.id != " . $post->getId()],4);
            }
            else
            {
                throw $this->createNotFoundException('This post does not exist');
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
        $parameters['breadcrumbs'] = $breadcrumbs;        
        $parameters['activepage'] = "Blog";
        $parameters['ha3key'] = $_ENV['HA3_KEY'];;
       
        return $this->render("compuhelp/" . $page, $parameters);
    }

    #[Route('/search-results', name: 'search')]
    public function search(): Response
    {
        $ha3key = $_ENV['HA3_KEY'];
        $parameters=[];
        $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
        $breadcrumbs = false;
        $parameters['breadcrumbs'] = $breadcrumbs;        
        $parameters['activepage'] = "Pages";
        $parameters['ha3key'] = $_ENV['HA3_KEY'];;
        $parameters['langSelector'] = "Français";
        $parameters['langSelectorURL'] = "/fr/search-results";
        $parameters['title'] = "Search Results";
        $parameters['quote'] = "REQUEST A QUOTE";

        return $this->render("compuhelp/search-results.html.twig", $parameters);
    }

    #[Route('/services', name: 'services')]
    public function services(EntityManagerInterface $entityManager): Response
    {
        $parameters=[];
        $repository = $entityManager->getRepository(Service::class);

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
                throw $this->createNotFoundException('The service does not exist');
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
        $parameters['breadcrumbs'] = $breadcrumbs;        
        $parameters['activepage'] = "Page";
        $parameters['ha3key'] = $_ENV['HA3_KEY'];;
        $parameters['langSelector'] = "Français";
        $parameters['langSelectorURL'] = "/fr/services";
        $parameters['title'] = "Services";
        $parameters['quote'] = "REQUEST A QUOTE";

        return $this->render("compuhelp/" . $page, $parameters);
    }

    #[Route('/contacts', name: 'contacts')]
    public function contacts(): Response
    {
        $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
        $breadcrumbs = false;
        $parameters['breadcrumbs'] = $breadcrumbs;        
        $parameters['activepage'] = "Pages";
        $parameters['ha3key'] = $_ENV['HA3_KEY'];;
        $parameters['langSelector'] = "Français";
        $parameters['langSelectorURL'] = "/fr/contacts";
        $parameters['title'] = "Contact Me";
        $parameters['quote'] = "REQUEST A QUOTE";

        return $this->render('compuhelp/contacts.html.twig', $parameters);
    }

    #[Route('/terms', name: 'terms')]
    public function terms(): Response
    {
        $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
        $parameters['breadcrumbs'] = false;        
        $parameters['activepage'] = "Pages";
        $parameters['ha3key'] = $_ENV['HA3_KEY'];;
        $parameters['langSelector'] = "Français";
        $parameters['langSelectorURL'] = "/fr/terms";
        $parameters['title'] = "Terms of Use";
        $parameters['quote'] = "REQUEST A QUOTE";

        return $this->render('compuhelp/terms.html.twig', $parameters);
    }

    #[Route('/privacy-policy', name: 'privacy-policy')]
    public function privacy(): Response
    {
        $parameters = ['preloader_class'=>"rd-navbar-fixed-linked", 'header_class'=>"breadcrumbs-custom-wrap bg-gray-darker"];
        $parameters['breadcrumbs'] = false;        
        $parameters['activepage'] = "Pages";
        $parameters['ha3key'] = $_ENV['HA3_KEY'];;
        $parameters['langSelector'] = "Français";
        $parameters['langSelectorURL'] = "/fr/privacy-policy";
        $parameters['title'] = "Privacy Policy";
        $parameters['quote'] = "REQUEST A QUOTE";

        return $this->render('compuhelp/privacy-policy.html.twig', $parameters);
    }

}

