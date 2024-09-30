<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Liste des catégories d'activités proposées entre particuliers
        $categories = [
            'Nautique',
            'Aventure',
            'Détente',
            'Culturel',
            'Gastronomie',
            'Nature',
            'Guide touristique',
            'Sports Extrêmes',
            'Ateliers Créatifs',
            'Photographie'
        ];


        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference('category_' . ($categoryName),$category );

            $manager->persist($category);
        }


        $manager->flush();
    }
}
