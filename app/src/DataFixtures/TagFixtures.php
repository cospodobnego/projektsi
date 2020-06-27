<?php
/**
 * Tag fixture.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Tags that were already added.
     *
     * @var array
     */
    private $value = [];

    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'tags', function ($i) {
            $tag = new Tag();
            $newName = $this->faker->unique()->word;
            $this->value[] = $newName;
            $tag->setName($newName);

            return $tag;
        });

        $manager->flush();
    }
}
