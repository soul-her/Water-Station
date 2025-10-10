<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['name' => '19L Spring Water Bottle', 'stock' => 100, 'price' => 5.99],
            ['name' => '5L Purified Water Jug', 'stock' => 200, 'price' => 2.49],
            ['name' => 'Case of 24x500ml Sport', 'stock' => 50, 'price' => 12.00],
        ];

        foreach ($products as $data) {
            $product = (new Product())
                ->setName($data['name'])
                ->setStock($data['stock'])
                ->setPrice($data['price']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
