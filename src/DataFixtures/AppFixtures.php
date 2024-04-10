<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Label;
use App\Entity\Product;
use App\Entity\Region;
use App\Entity\Unit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        // create user Admin
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'password'));

        $manager->persist($admin);

        // create 5 user Seller
        for ($i = 0; $i < 5; $i++) {
            $seller = new User();
            $seller->setEmail('seller' . ($i + 1) . '@gmail.com');
            $seller->setRoles(['ROLE_SELLER']);
            $seller->setPassword($this->hasher->hashPassword($seller, 'password'));

            $sellers[] = $seller;



            

            $manager->persist($seller);
        }

        // create 10 user Buyer
        for ($i = 0; $i < 10; $i++) {
            $buyer = new User();
            $buyer->setEmail('buyer' . ($i + 1) . '@gmail.com');
            $buyer->setRoles(['ROLE_BUYER']);
            $buyer->setPassword($this->hasher->hashPassword($buyer, 'password'));

            $manager->persist($buyer);
        }

        // create categories
        $categories = ['Charcuterie', 'Viandes', 'Poissons', 'Fruits & Légumes', 'Fromages'];

        $categoryEntities = [];

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setTitle($categoryName);

            $manager->persist($category);
            $categoryEntities[] = $category;
        }

        // create products
        foreach ($sellers as $seller) {
            $randomNumber = $faker->numberBetween(1, 15);
                for ($i = 0; $i < $randomNumber; $i++) {
                    $product = new Product();
                    $product->setDescription($faker->text);
                    $product->setTitle($faker->sentence(3));
                    $product->setPrice($faker->randomFloat(2, 10, 1000));
                    $product->setStock($faker->numberBetween(1, 100));
                    $product->setCategory($faker->randomElement($categoryEntities));
                    $product->setProductMedia($faker->imageUrl());
                    $product->setSeller($seller);

            $manager->persist($product);
        }
    }

            // create labels
            $labels = ['Agriculture biologique', 'LabelRouge', 'AOC', 'IGP', 'STG'];

            $labelEntities = [];
    
            foreach ($labels as $labelTitle) {
                $label = new Label();
                $label->setTitle($labelTitle);
    
                $manager->persist($label);
                $labelEntities[] = $label;
            }

                    // create units
        $units = ['Kilogramme', 'Gramme', 'Litre', 'Centilitre', 'Unité'];

        $unitEntities = [];

        foreach ($units as $unitName) {
            $unit = new Unit();
            $unit->setName($unitName);

            $manager->persist($category);
            $unitEntities[] = $unit;
        }

                // create regions
                $regions = [
                    'Auvergne-Rhône-Alpes',
                    'Bourgogne-Franche-Comté',
                    'Bretagne',
                    'Centre-Val de Loire',
                    'Corse',
                    'Grand Est',
                    'Hauts-de-France',
                    'Île-de-France',
                    'Normandie',
                    'Nouvelle-Aquitaine',
                    'Occitanie',
                    'Pays de la Loire',
                    'Provence-Alpes-Côte d\'Azur'
                ];
        $regionEntities = [];

        foreach ($regions as $regionName) {
            $region = new Region();
            $region->setName($regionName);

            $manager->persist($region);
            $regionEntities[] = $region;
        }

        $manager->flush();
    }
}