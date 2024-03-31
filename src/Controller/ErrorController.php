<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErrorController extends AbstractController
{
    public function show($exception): Response
    {
        $errorCode = method_exists($exception, 'getStatusCode')? $exception->getStatusCode(): $exception->getCode();

        switch ($_SERVER['SERVER_NAME']) {
            case 'compuhelp-webdesign.ca':
                $path = "/error/compuhelp/";
                $icon = "images/compuhelp/favicon.ico";
                break;
            case 'health2wealthcoaching.compuhelp-webdesign.ca':
                $path = "/error/health2wealth/";
                $icon = "images/health2wealth/favicon.ico";
                break;
            case 'formatio.compuhelp-webdesign.ca':
                $path = "/error/formatio/";
                $icon = "images/formatio/favicon.ico";
                break;
            default:
                $page = '404.html.twig';
                $path = "/error/compuhelp/";
                $icon = "images/compuhelp/favicon.ico";
        }

        return $this->render($path . $errorCode . ".html.twig", ['controller_name' => 'ErrorController']);
    }
}
