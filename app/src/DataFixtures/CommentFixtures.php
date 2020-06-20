<?php
/**
 * Comment fixture.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class CommentFixtures.
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'comments', function ($i) {
            $comment = new Comment();
            $comment->setDate($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $comment->setText($this->faker->sentence);
            $comment->setPost($this->getRandomReference('posts'));
            $comment->setAuthor($this->getRandomReference('users','admins'));


            return $comment;
        });

        $manager->flush();
    }
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [PostFixtures::class, UserFixtures::class];
    }
}