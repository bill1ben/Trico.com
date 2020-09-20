<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\SubCategory;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("FR_fr");
        $Slugify = new Slugify();
        $users = [];
        $colorsTab = [];
        $genres = ['male','female'];
        for($i = 1 ; $i <=10 ; $i++) {

            $user = new User();
            $content = '<p>'. join("</p><p>",$faker->paragraphs(0.5)) .'</p>' ;
            $url = "https://randomuser.me/api/portraits/";
            $genre = $faker->randomElement($genres);
            $genreId = $faker->numberBetween(0 , 99) . ".jpg";
            $pictur = $url . ($genre == 'male' ? 'men/' : 'women/') . $genreId;
            $hash = $this->encoder->encodePassword($user , 'password');

            $user->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntro($faker->sentence)
                ->setDescription($content)
                ->setHash($hash)
                ->setPicture($pictur);

            $manager->persist($user);
            $users[] = $user;
        }

        $colorsArry = array('red' , 'green' , 'blue');

        foreach ($colorsArry as $val)
        {
            $color = new Color();
            $newColor = $val;
            $color->setName($newColor);
            $manager->persist($color);
            $colorsTab[] = $color;
        }
        $catArry = array('manche courte' , 'manche longue');

        foreach ($catArry as $val)
        {
            $category = new Category();

            $category->setName($val);
            $manager->persist($category);
            $categoryTab[] = $category;
        }

        $subCatArry = array('avec capuche' , 'sans capuche');

        foreach ($subCatArry as $val)
        {
            $subCategory = new SubCategory();

            $subCategory->setName($val);
            $manager->persist($subCategory);
            $subCategoryTab[] = $subCategory;
        }


        for($i =1 ; $i <= 30; $i++)
        {
            $title = "titre de produit $i";
            $descriptions = "description de produit $i";
            $slug = $Slugify->slugify($title);
            $category = $categoryTab[mt_rand(0, count($categoryTab) - 1)];
            $color = $colorsTab[mt_rand(0, count($colorsTab) - 1)];
            $subCategory = $subCategoryTab[mt_rand(0, count($subCategoryTab) - 1)];
            $content = '<p>'. join("</p><p>",$faker->paragraphs(5)) .'</p>' ;
            $user = $users[mt_rand(0, count($users) - 1)];
            $price = mt_rand(500,1000);
            $product = new Product();
            $product->setTitle($title)
                ->setContent( $content)
                ->setDescription($descriptions)
                ->setPrice(round($price))
                ->setSlugName($slug)
                ->setAuthor($user)
                ->setColors($color)
                ->setCategories($category)
                ->setSubCategories($subCategory);




            $manager->persist($product);

        }
        $manager->flush();
    }

}
