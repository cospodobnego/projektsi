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
use Gedmo\Mapping\Annotation as Gedmo;

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

    /**
     *  Code.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     * )
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     *
     * @Gedmo\Slug(fields={"name"})
     */
    private $code;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

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
     * Getter for Name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    /**
     * Setter for Title.
     *
     * @param string $name Name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for tasks.
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Post[] Posts collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }
    /**
     * Add post to collection.
     *
     * @param \App\Entity\Post $post Post entity
     */
    public function addPost(Post $post): void
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addTag($this);
        }
    }
    /**
     * Remove post from collection.
     *
     * @param \App\Entity\Post $post Post entity
     */
    public function removePost(Post $post): void
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeTag($this);
        }

    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;


    }
}
