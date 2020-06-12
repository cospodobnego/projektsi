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
     *     )
     * @Assert\Type(type="Doctrine\Common\Collections\ArrayCollection")
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

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tag = new ArrayCollection();
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }


}
