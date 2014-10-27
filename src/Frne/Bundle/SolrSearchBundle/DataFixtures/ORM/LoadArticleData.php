<?php


namespace Frne\Bundle\SolrSearchBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Frne\Bundle\SolrSearchBundle\Entity\Article;
use Faker\Factory;

class LoadArticleData implements FixtureInterface
{
    const NUM_ARTICLES_TO_GENERATE = 1000;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < self::NUM_ARTICLES_TO_GENERATE; $i++) {
            $article = new Article();
            $article
                ->setTitle($faker->realText())
                ->setAuthor($faker->name)
                ->setContent($faker->realText(1000));

            $manager->persist($article);

            if($i % 500 == 0) {
                $manager->flush();
                echo '.';
            }
        }

        $manager->flush();
        $manager->clear();
    }
}