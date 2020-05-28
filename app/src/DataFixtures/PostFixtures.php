<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;


/**
 * Class PostFixtures
 * @package App\DataFixtures
 */

class PostFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */

    public function loadData(ObjectManager $manager): void
    {

        for($i = 0; $i < 10; ++$i){
            $post = new Post();
            $post->setName($this->faker->name);
            $post->setText($this->faker->sentence);
            $post->setDate($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $this->manager->persist($post);

        }

        $manager->flush();
    }
}
