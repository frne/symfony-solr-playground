<?php


namespace Frne\Bundle\SolrSearchBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Frne\Bundle\SolrSearchBundle\Entity\Category;
use Frne\Bundle\SolrSearchBundle\Entity\Product;

class LoadProductData implements FixtureInterface
{
    const NUM_PRODUCTS_TO_GENERATE = 500;

    const NUM_CATEGORIES_TO_GENERATE = 30;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $categories = array();

        // generate categories
        for ($i = 0; $i < self::NUM_CATEGORIES_TO_GENERATE; $i++) {
            $category = new Category();
            $category->setName($faker->company);

            $manager->persist($category);
            $categories[] = $category;
        }
        $manager->flush();

        // generate products
        for ($i = 0; $i < self::NUM_PRODUCTS_TO_GENERATE; $i++) {
            $product = new Product();
            $product
                ->setTitle($faker->realText(rand(100, 200)))
                ->setContent($faker->realText(1000))
                ->setCategory($categories[array_rand($categories)]);

            $manager->persist($product);

            if ($i % 100 == 0) {
                $manager->flush();
                echo '.';
            }
        }

        $manager->flush();
        $manager->clear();
    }
}