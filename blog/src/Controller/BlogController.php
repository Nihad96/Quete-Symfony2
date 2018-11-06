<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog/{slug}", requirements={"slug"="[a-z0-9-]+"}, name="blog_list")
     */
    public function show($slug="article-sans-nom")
    {

        $slug=str_replace("-"," ", $slug);
        $slug=ucwords($slug);
        return $this->render('blog/index.html.twig', [
            'slug' => $slug
        ]);
    }

}


