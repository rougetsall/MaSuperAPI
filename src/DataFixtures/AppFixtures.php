<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Product;
use Faker;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 30; $i++) {
            $user = new User();
            $user->setNom($faker->lastname);
            $user->setPrenom($faker->firstname);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $manager->persist($user);
        }
        for ($i = 0; $i < 50; $i++) {
            $product = new Product();
            $product->setNom($faker->name);
            $product->setPhoto("image".$i);
            $product->setPrix(20+$i);
            $product->setDescription("description".$i);
            $product->setQuantite($i);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
