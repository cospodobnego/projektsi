<?php
/**
 * Post entity.
 */
namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Post.
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table(name="posts")
 * @UniqueEntity(fields={"name"})
 */
class Post
{
    /**
     * Primary key.
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
     * Name.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=45,
     * )
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     *
     */
    private $name;

    /**
     * Text.
     *
     * @var string
     * @ORM\Column(
     *     type="text",
     *     length=65535,
     *     )
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="10",
     *    max="65535",
     * )
     */
    private $text;

    /**
     * Categories.
     *
     * @var \App\Entity\Category Category
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Category",
     *      inversedBy="posts",
     *     )
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * Comments.
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Entity\Comment[] $comments Comments
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Comment",
     *      mappedBy="post",
     *     orphanRemoval=true,
     *     )
     */
    private $comments;

    /**
     * Tags.
     * @var array
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *      inversedBy="posts",
     *     orphanRemoval=true
     * )
     * @ORM\JoinTable(name="posts_tags")
     */
    private $tag;

    /**
     * Post constructor
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tag = new ArrayCollection();
    }

    /**
     * Getter for Id.
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Date.
     * @return DateTimeInterface|null Date.
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Setter for Date.
    * @param \DateTimeInterface $date Date
     */
    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;


    }

    /**
     * Getter for Name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string $name Name
     *
     */
    public function setName(string $name): void
    {
        $this->name = $name;

    }

    /**
     * Getter for Text.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Setter for Text.
     *
     * @param string $text Text
     *
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Getter for category
     *
     * @return \App\Entity\Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category.
     *
     * @param \App\Entity\Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for comments.
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Comment[] Comments collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }
/**
 *  Add comment to collection.
 *
 * @param \App\Entity\Comment $comment Comment entity
 */
    public function addComment(Comment $comment): void
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }
    }

/**
 * Remove comment from collection.
 *
 * @param \App\Entity\Comment $comment Comment entity
 */
    public function removeComment(Comment $comment): void
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }
    }

    /**
     * Getter for tags.
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Tag[] Tags collection
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }
    /**
     * Add tag to collection.
     *
     * @param \App\Entity\Tag $tag Tag entity
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

    }
    /**
     * Remove tag from collection.
     *
     * @param \App\Entity\Tag $tag Tag entity
     */
    public function removeTag(Tag $tag): void
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

    }


}
