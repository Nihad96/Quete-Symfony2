<?php
/**
 * Created by PhpStorm.
 * User: nihad
 * Date: 23/11/18
 * Time: 11:45
 */

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ArticleFixtures extends Fixture implements DependentFixtureInterface{

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < 10; $i++) {

            $faker  =  Faker\Factory::create('fr_FR');
            $article=new Article();
            $article->setTitle(mb_strtolower($faker->sentence()));

            $article->setContent('bonjour');
            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_0'));
            // categorie_0 fait reference à la premiere categorie générée.
        }
        for ($i=0; $i < 10; $i++) {

            $faker  =  Faker\Factory::create('fr_FR');
            $article=new Article();
            $article->setTitle(mb_strtolower($faker->sentence()));

            $article->setContent('je');
            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_1'));
            // categorie_0 fait reference à la premiere categorie générée.
        }
        for ($i=0; $i < 10; $i++) {

            $faker  =  Faker\Factory::create('fr_FR');
            $article=new Article();
            $article->setTitle(mb_strtolower($faker->sentence()));

            $article->setContent('m\'appelle');
            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_2'));
            // categorie_0 fait reference à la premiere categorie générée.
        }
        for ($i=0; $i < 10; $i++) {

            $faker  =  Faker\Factory::create('fr_FR');
            $article=new Article();
            $article->setTitle(mb_strtolower($faker->sentence()));

            $article->setContent('Nihad');
            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_3'));
            // categorie_0 fait reference à la premiere categorie générée.
        }
        for ($i=0; $i < 50; $i++) {

            $faker  =  Faker\Factory::create('fr_FR');
            $article=new Article();
            $article->setTitle(mb_strtolower($faker->sentence()));

            $article->setContent('Zatric');
            $manager->persist($article);
            $article->setCategory($this->getReference('categorie_4'));
            // categorie_0 fait reference à la premiere categorie générée.
        }
        $manager->flush();
    }
}