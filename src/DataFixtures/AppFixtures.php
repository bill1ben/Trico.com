<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
//        $faker = Factory::create("FR_fr");
//        $Slugify = new Slugify();
//
//        for($i =1 ; $i <= 25; $i++)
//        {
//            $title = "titre de produit $i";
//            $slug = $Slugify->slugify($title);
//            $coverImage = $faker->imageUrl(350,1000);
//            $description = $faker->paragraph(2);
//            $content = '<p>'. join("</p><p>",$faker->paragraphs(5)) .'</p>' ;
//
//            $price = mt_rand(500,1000);
//            $product = new Product();
//            $product->setTitle($slug)
//                ->setContent( $content)
//                ->setDescription($description)
//                ->setCoverImage($coverImage)
//                ->setPrice(round($price));
//
//            $manager->persist($product);
//
//        }
//        $manager->flush();
    }
}
