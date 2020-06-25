<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *  Class Comment.
 *
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\Table(name="comments")
 */
class Comment
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Date.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $date;

    /**
     * Text.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     *
     * )
     */
    private $text;

    /**
     * Posts.
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Post",
     *      inversedBy="comments")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    /**
     * @var \App\Entity\User
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;
    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * Getter for Date.
     *
     * @return DateTimeInterface|null date
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }
    /**
     * Setter for Date.
     *
     * @param \DateTimeInterface $date Date
     */
    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }
    /**
     * Getter for Text.
     *
     * @return string|null Text
     */
    public function getText(): ?string
    {
        return $this->text;
    }
    /**
     * Setter for Text.
     *
     * @param string $text Text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }
    /**
     * Getter for post.
     *
     * @return Post|null Post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }
    /**
     * Setter for post.
     *
     * @param Post $post Post
     */
    public function setPost(?Post $post): void
    {
        $this->post = $post;
    }
    /**
     * Getter for author.
     *
     * @return User[]|null User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }
    /**
     * Setter for author.
     *
     * @param User $author User
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }
}
