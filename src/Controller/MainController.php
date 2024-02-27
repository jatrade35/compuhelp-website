<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $page = 'welcome.html.twig';
        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $website = "Compuhelp Enterprises'";
                $path = "compuhelp/";
                $icon = "images/compuhelp/favicon.ico";
                break;
            case 'health2wealthcoaching.compuhelp-webdesign.ca':
                $website = "Heath2Wealth's";
                $path = "health2wealth/";
                $icon = "images/health2wealth/favicon.ico";
                break;
            case 'formatio.compuhelp-webdesign.ca':
                $website = "fromatio's";
                $path = "formatio/";
                $icon = "images/formatio/favicon.ico";
                break;
            default:
                $website = "";
                $page = '404.html.twig';
                $path = "compuhelp/";
                $icon = "images/compuhelp/favicon.ico";
        }            
        return $this->render($path . $page, ['attributes'=>"class=\"rd-navbar-fixed-linked, lang=\"en\""]);
    }

    #[Route('/about-me', name: 'about me')]
    public function about(): Response
    {
        $page = 'about-me.html.twig';
        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $path = "compuhelp/";
                break;
            default:
                $path = "compuhelp/";
                $page = '404.html.twig';
        }            
        return $this->render($path . $page, ['attributes'=>"class=\"rd-navbar-fixed-linked, lang=\"en\""]);
    }

    #[Route('/blog', name: 'blog')]
    public function blog(): Response
    {
        $page = 'blog.html.twig';
        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $path = "compuhelp/";
                break;
            default:
                $path = "compuhelp/";
                $page = '404.html.twig';
        }            
        return $this->render($path . $page, ['attributes'=>"class=\"rd-navbar-fixed-linked, lang=\"en\""]);
    }
}