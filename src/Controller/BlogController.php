<?php
/**
 * Created by PhpStorm.
 * User: nihad
 * Date: 15/11/18
 * Time: 10:05
 */

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryType;
use App\Form\ArticleType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogController extends AbstractController
{

    /**
     * Show all row from article's entity
     *
     * @Route("/blog/categories", name="blog_create_category")
     * @return Response A response instance
     */
    public function index(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $category=$form->getData();

            $em=$this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        if (!$categories) {
            throw $this->createNotFoundException(
                'No categories found in categorie\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig', [
                'categories' => $categories,
                'form' => $form->createView(),
            ]
        );
    }

        public function configureOptions(OptionsResolver $resolver)
             {
            $resolver->setDefaults([
                'data_class' => Category::class,
            ]);
        }




    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/blog/article/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="blog_show")
     *  @return Response A response instance
     */
    public function show($slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'slug' => $slug,
            ]
        );
    }

    // /**
     // * @param string $category
     // * @return Response
     // * @Route("/category/{category}", name="blog_show_category")
     // */

    /* public function showByCategory(string $category) : Response
    {
        $categorie = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName($category);
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(['category' => $categorie->getId()], ['id' => 'desc'], 3);

        return $this->render('blog/category.html.twig', ['categorie' => $categorie, 'articles' => $articles]);
    }
*/


    /**
     * @param string $category
     * @return Response
     * @Route("blog/category/{category}/all", name="blog_show_all_by_category")
     */

    public function showAllByCategory(string $category) : Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class);

           $categorie = $categories->findOneByName($category);
           $articles = $categorie->getArticles();

        return $this->render('blog/category.html.twig', ['articles' => $articles, 'category' => $categorie]);
    }


    /**
     * @return Response
     * @Route("blog/create-article", name="blog_create_article")
     */

    public function createArticle(Request $request) : Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            echo    'No article found in article\'s table.';
        }

        $article = new Article();
        $form = $this->createForm(
            ArticleType::class,
            $article
        );
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('blog_create_article');
        }



        return $this->render(
            'blog/createArticle.html.twig', [
                'articles'=>$articles,
                'form' => $form->createView(),
            ]
        );


    }


    /**
     * @Route("/category/{id}", name="category_show")
     */
    public function showCategory(Category $category) :Response
    {
        return $this->render('blog/categoryById.html.twig', ['category'=>$category]);
    }


    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function showArticle(Article $article) : Response
    {
        return $this->render('blog/article.html.twig', ['article'=>$article]);
    }

    /**
     * @return Response
     * @Route("{name}", name="show_all_by_tag")
     */

    public function showAllByTag(Tag $tag) : Response
    {
        return $this->render('blog/tag.html.twig', ['tag' => $tag]);
    }

}