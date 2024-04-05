<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Post;
use App\Entity\Service;
use App\Entity\Testimonial;
use Doctrine\ORM\EntityManagerInterface;


class SearchController extends AbstractController
{

    #[Route('/search', name: 'app_search')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!isset($_GET['s']))
        {
            die('You must define a search term!');
        }

        $response = $this->search("en", $entityManager); 

        return $response;
    }

    #[Route('/fr/search', name: 'app_recherche')]
    public function frenchIndex(EntityManagerInterface $entityManager): Response
    {
        if (!isset($_GET['s']))
        {
            die('You must define a search term!');
        }

        $response = $this->search("fr", $entityManager); 

        return $response;
    }

    private function search(string $language, EntityManagerInterface $entityManager) :Response
    {
        define('SIDE_CHARS', 60);
        $file_count = 0;
        $search_term = mb_strtolower($_GET['s'], 'UTF-8');

        if ($search_term == "?s=") {
            $search_term = "";
        }

        $search_term = preg_replace('/^\/$/', '"/"', $search_term);
        $search_term = preg_replace('/\+/', ' ', $search_term);
        if (isset($_GET['liveCount'])) {
            $search_live_count = $_GET['liveCount'];
        }
        $final_result = array();

        $search_template = preg_replace('/\+/', ' ', $_GET['template']);
        preg_match_all("/\#\{((?!title|href|token|count)[a-z]*)\}/", $search_template, $template_tokens);
        $template_tokens = $template_tokens[1];

        $files = ['about-me', 'home', 'single-post', 'single-service', 'terms', 'privacy-policy'];

        foreach ($files as $file) {

            $parameters = ['preloader_class' => "rd-navbar-fixed-linked", 'header_class' => ""];
            $parameters['breadcrumbs'] = false;
            $parameters['activepage'] = "Home";
            $parameters['ha3key'] = "";
            $linkPrefix = "";
            if($language == "en")
            {
                $parameters['langSelector'] = "Français";
                $parameters['quote'] = "REQUEST A QUOTE";
            }
            else
            {
                $parameters['langSelector'] = "English";
                $parameters['quote'] = "DEMANDEZ UNE SOUMISSION";
                $linkPrefix = "/fr";
            }

            switch ($file) {
                case 'about-me':
                    $repository = $entityManager->getRepository(Testimonial::class);
                    $testimonials = $repository->findAll();

                    $parameters['testimonials'] = $testimonials;
                    $parameters['layout'] = 2;
                    $parameters['breadcrumbs'] = false;        
                    $parameters['activepage'] = "AboutMe";
                    $parameters['ha3key'] = "";
                    $parameters['langSelectorURL'] = $linkPrefix . "/about-me";
                    if($language == "en")
                    {
                        $parameters['title'] = "About Me";
                        $link = "/about-me";
                    }
                    else
                    {
                        $parameters['title'] = "Qui suis-je ?";
                        $link = "/fr/about-me";
                    }

                    $contents = $this->render("compuhelp/$file.html.twig", $parameters)->getContent();
                    $final_result[$file_count] = $this->getSearchResult($file, $contents, $search_term, $template_tokens);
                    $final_result[$file_count]['page_title'][0] = $parameters['title'];
                    $final_result[$file_count]['href'][0] = $link;

                    $file_count++;
                    break;
                case 'home':
                    $repository = $entityManager->getRepository(Service::class);
                    $parameters['services'] = $repository->findAll();
                    $repository = $entityManager->getRepository(Post::class);
                    $parameters['recent_posts'] = $repository->findBy(
                        [],
                        ['datetimePosted' => 'DESC'],
                        3,
                        0
                    );
                    if($language == "en")
                    {
                        $parameters['langSelectorURL'] = "/fr/accueil";
                        $parameters['title'] = "Homepage";
                        $link = "/";
                    }
                    else
                    {
                        $parameters['langSelectorURL'] = "/";
                        $parameters['title'] = "Accueil";
                        $link = "/fr/accueil";
                    }
            
                    $contents = $this->render("compuhelp/$file.html.twig", $parameters);

                    $final_result[$file_count] = $this->getSearchResult($file, $contents, $search_term, $template_tokens);
                    $final_result[$file_count]['href'][0] = $link;
                    $final_result[$file_count]['page_title'][0] = $parameters['title'];

                    $file_count++;
                    break;
                case 'single-service':
                    $repository = $entityManager->getRepository(Service::class);
                    $services = $repository->findAll();
                    foreach ($services as $service)
                    {
                        $parameters['service'] = $service;
                        $parameters['breadcrumbs'] = false;
                        $parameters['testimonials'] = [];
                        $parameters['langSelectorURL'] = $linkPrefix . "/service?id=" . $service->getId();
                        $parameters['title'] = "Services";
    
                        $contents = $this->render("compuhelp/$file.html.twig", $parameters);

                        $final_result[$file_count] = $this->getSearchResult("service?id=" . $service->getId(), $contents, $search_term, $template_tokens);
                        $final_result[$file_count]['page_title'][0] = mb_substr($service->getTitle($language), 0, 35) . "...";
    
                        $file_count++;
                    }
                    break;
                case 'single-post':
                    $repository = $entityManager->getRepository(Post::class);
                    $posts = $repository->findAll();
                    foreach ($posts as $post)
                    {
                        $parameters['post'] = $post;
                        $parameters['breadcrumbs'] = true;
                        $parameters['recentposts'] = [];
                        $parameters['langSelectorURL'] = $linkPrefix . "/blog?post=".$post->getId();
                        if($language == "en")
                        {
                            $parameters['title'] = "René's blog";
                        }
                        else
                        {
                            $parameters['title'] = "Blog à René";
                        }
    
                        $contents = $this->render("compuhelp/$file.html.twig", $parameters);

                        $final_result[$file_count] = $this->getSearchResult("blog?post=".$post->getId(), $contents, $search_term, $template_tokens);
                        $final_result[$file_count]['page_title'][0] = mb_substr($post->getTitle($language), 0, 35) . "...";
    
                        $file_count++;
                    }
                    break;
                case 'terms':
                    $parameters['langSelectorURL'] = $linkPrefix . "/terms";
                    if($language == "en")
                    {
                        $parameters['title'] = "Terms of Use";
                        $link = "/terms";
                    }
                    else
                    {
                        $parameters['title'] = "Conditions d'utilisation";
                        $link = "/fr/terms";
                    }

                    $contents = $this->render("compuhelp/$file.html.twig", $parameters)->getContent();

                    $final_result[$file_count] = $this->getSearchResult($file, $contents, $search_term, $template_tokens);
                    $final_result[$file_count]['href'][0] = $link;
                    $final_result[$file_count]['page_title'][0] = $parameters['title'];

                    $file_count++;
                    break;
                case 'privacy-policy':
                    $parameters['langSelectorURL'] = $linkPrefix . "/privacy-policy";
                    if($language == "en")
                    {
                        $parameters['title'] = "Privacy Policy";
                        $link = "/privacy-policy";
                    }
                    else
                    {
                        $parameters['title'] = "Politique de confidentialités";
                        $link = "/fr/privacy-policy";
                    }
            
                    $contents = $this->render("compuhelp/$file.html.twig", $parameters)->getContent();

                    $final_result[$file_count] = $this->getSearchResult($file, $contents, $search_term, $template_tokens);
                    $final_result[$file_count]['href'][0] = $link;
                    $final_result[$file_count]['page_title'][0] = $parameters['title'];

                    $file_count++;
                    break;
            }
        }

        if ($file_count > 0) {

            //Sort final result
            foreach ($final_result as $key => $row) {
                $search_result[$key] = $row['search_result'];
            }
            array_multisort($search_result, SORT_DESC, $final_result);
        }

        $sum_of_results = 0;
        $match_count = 0;
        $resultItems = [];
        for ($i = 0; $i < count($final_result); $i++) {
            if (!empty($final_result[$i]['search_result'][0]) || $final_result[$i]['search_result'][0] !== '') {
                $match_count++;
                $sum_of_results += count($final_result[$i]['search_result']);
                if (!(isset($_GET['liveSearch']) and $_GET['liveSearch'] != "" and $i >= $search_live_count)) {
                    $resultItems[] = [
                        'title' => $final_result[$i]['page_title'][0],
                        'href' => $final_result[$i]['href'][0],
                        'token' => $final_result[$i]['search_result'][0],
                        'count' => count($final_result[$i]['search_result'])
                    ];
                }
            }
        }

        return $this->render('search/index.html.twig', [
            'quickresult' => (count($final_result) > 0 and isset($_GET['liveSearch']) and $_GET['liveSearch'] != ""),
            'resultItems' => $resultItems,
            'sum_of_results' => $sum_of_results,
            'search_term' => $search_term,
            's' => $_GET['s'],
            'language' => $language,
            'count' => $match_count
        ]);
    }

    private function getSearchResult($file, $contents, $search_term, $template_tokens): array
    {
        $result = [[]];
        $search_term_length = strlen($search_term);

        if (preg_match("#\<body.*\>(.*)\<\/body\>#si", $contents, $body_content)) { //getting content only between <body></body> tags
            $clean_content = strip_tags($body_content[0]); //remove html tags
            $clean_content = preg_replace('/\s+/', ' ', $clean_content); //remove duplicate whitespaces, carriage returns, tabs, etc
        
            $found = $this->strpos_recursive(mb_strtolower($clean_content, 'UTF-8'), $search_term);
        
            $result['page_title'][] = $file;
            $result['href'][] = "/$file";
        }
        
        for ($j = 0; $j < count($template_tokens); $j++) {
            if (preg_match("/\<meta\s+name=[\'|\"]" . $template_tokens[$j] . "[\'|\"]\s+content=[\'|\"](.*)[\'|\"]\>/", $contents, $res)) {
                $result[$template_tokens[$j]] = $res[1];
            }
        }
        
        if (isset($found) && !empty($found)) {
            for ($z = 0; $z < count($found[0]); $z++) {
                $pos = $found[0][$z][1];
                $side_chars = SIDE_CHARS;
                if ($pos < SIDE_CHARS) {
                    $side_chars = $pos;
                    if (isset($_GET['liveSearch']) and $_GET['liveSearch'] != "") {
                        $pos_end = SIDE_CHARS + $search_term_length + 15;
                    } else {
                        $pos_end = SIDE_CHARS * 9 + $search_term_length;
                    }
                } else {
                    if (isset($_GET['liveSearch']) and $_GET['liveSearch'] != "") {
                        $pos_end = SIDE_CHARS + $search_term_length + 15;
                    } else {
                        $pos_end = SIDE_CHARS * 9 + $search_term_length;
                    }
                }
        
                $pos_start = $pos - $side_chars;
                $str = substr($clean_content, $pos_start, $pos_end);
                $result_parts = preg_split('/(' . $search_term . ')/ui', $str, -1, 2);
                //$result = preg_replace('#'.$search_term.'#ui', '<span class="search">'.$search_term.'</span>', $str);
                $result['search_result'][] = $result_parts;
            }
        } else {
            $result['search_result'][] = '';
        }
        return $result;
    }

    private function strpos_recursive($haystack, $needle, $offset = 0, &$results = array())
    {
        $offset = stripos($haystack, $needle, $offset);
        if ($offset === false) {
            return $results;
        } else {
            $pattern = '/' . $needle . '/ui';
            preg_match_all($pattern, $haystack, $results, PREG_OFFSET_CAPTURE);
            return $results;
        }
    }
}

