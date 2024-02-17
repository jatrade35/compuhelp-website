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
        $website = "";

        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $website = "Compuhelp Enterprises'";
                $path = "compuhelp/";
                break;
            case 'health2wealthcoaching.compuhelp-webdesign.ca':
                $website = "Heath2Wealth's";
                $path = "health2wealth/";
                break;
        }            
        return $this->render($path . 'welcome.html.twig', ['website'=>$website]);
    }
}
