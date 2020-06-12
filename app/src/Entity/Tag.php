<?php
/**
 * Tag entity.
 */
namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class Tag.
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ORM\Table(name="tags")
 *
 *  @UniqueEntity(fields={"name"})
 */
class Tag
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
     * Name.
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     * )
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $name;

    /**
     * Posts
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Entity\Post[] $posts Posts
     *
     * @ORM\ManyToMany(
     *      targetEntity="App\Entity\Post",
     *      mappedBy="tag")
     *
     * @Assert\Type(type="Doctrine\Common\Collections\ArrayCollection")
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addTag($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeTag($this);
        }

        return $this;
    }
}
