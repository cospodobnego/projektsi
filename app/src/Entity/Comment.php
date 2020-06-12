<?php
/**
 * Comment entity.
 */
namespace App\Entity;

use DateTimeInterface;
use App\Repository\CommentRepository;
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
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Date.
     *
     * @var DateTimeInterface
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $date;
    /**
     * Text.
     *
     * @var string
     * @ORM\Column(type="string", length=255)
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
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Post",
     *      inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     *
     *
     */
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
