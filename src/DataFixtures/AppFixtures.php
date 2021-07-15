<?php

namespace App\DataFixtures;

use App\Entity\PostCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categoryNames = [
            'Cuisine',
            'Éducatif',
            'Événements',
            'Autres',
        ];

        foreach ($categoryNames as $categoryName){
            $category = new PostCategory;
            $category->setName($categoryName);

            $manager->persist($category);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
